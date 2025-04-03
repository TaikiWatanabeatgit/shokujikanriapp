<?php
// 設定ファイルの読み込み
$config = require_once 'config/app.php';

// タイムゾーンの設定
date_default_timezone_set($config['timezone']);

// セッション設定
ini_set('session.cookie_lifetime', $config['session_lifetime']);
session_name($config['session_name']);
session_start();

// データベース関連のクラスを読み込み
require_once 'includes/MealRecord.php';

// エラーメッセージの初期化
$error_message = '';

// データの読み込み
function loadMealRecords() {
    global $dataFile;
    if (file_exists($dataFile)) {
        return unserialize(file_get_contents($dataFile)) ?: [];
    }
    return [];
}

// データの保存
function saveMealRecords($records) {
    global $dataFile;
    file_put_contents($dataFile, serialize($records));
}

// カロリー計算
function calculateCalories($meal) {
    global $config;
    return strlen($meal) * $config['calories_per_char'];
}

// トークンの生成
if (!isset($_SESSION['token'])) {
    $_SESSION['token'] = bin2hex(random_bytes(32));
}

// フォーム送信時の処理
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // トークンの検証
    if (!isset($_POST['token']) || $_POST['token'] !== $_SESSION['token']) {
        $error_message = 'セッションが切れました。もう一度お試しください。';
        // 新しいトークンを生成
        $_SESSION['token'] = bin2hex(random_bytes(32));
    } else {
        // アクションの確認
        if (isset($_POST['action'])) {
            $mealRecord = new MealRecord();
            $id = $_POST['record_id'];

            if ($_POST['action'] === 'delete') {
                // 削除処理
                if ($mealRecord->delete($id)) {
                    header('Location: ' . $_SERVER['PHP_SELF']);
                    exit;
                }
            } elseif ($_POST['action'] === 'edit') {
                // 編集処理
                $breakfast_calories = calculateCalories($_POST['breakfast']);
                $lunch_calories = calculateCalories($_POST['lunch']);
                $dinner_calories = calculateCalories($_POST['dinner']);
                $snack_calories = calculateCalories($_POST['snack']);

                if ($mealRecord->update(
                    $id,
                    $_POST['date'],
                    $_POST['breakfast'],
                    $_POST['lunch'],
                    $_POST['dinner'],
                    $_POST['snack'],
                    $breakfast_calories,
                    $lunch_calories,
                    $dinner_calories,
                    $snack_calories
                )) {
                    header('Location: ' . $_SERVER['PHP_SELF']);
                    exit;
                }
            }
        } else {
            // 新規記録の処理
            // フォームの再送信防止
            if (isset($_SESSION['last_post']) && $_SESSION['last_post'] === $_POST) {
                // 同じデータの再送信の場合はリダイレクト
                header('Location: ' . $_SERVER['PHP_SELF']);
                exit;
            }

            $mealRecord = new MealRecord();
            $breakfast_calories = calculateCalories($_POST["breakfast"]);
            $lunch_calories = calculateCalories($_POST["lunch"]);
            $dinner_calories = calculateCalories($_POST["dinner"]);
            $snack_calories = calculateCalories($_POST["snack"]);

            if ($mealRecord->create(
                $_POST["date"],
                $_POST["breakfast"],
                $_POST["lunch"],
                $_POST["dinner"],
                $_POST["snack"],
                $breakfast_calories,
                $lunch_calories,
                $dinner_calories,
                $snack_calories
            )) {
                // 送信データを保存
                $_SESSION['last_post'] = $_POST;
                
                // 新しいトークンを生成
                $_SESSION['token'] = bin2hex(random_bytes(32));
                
                // リダイレクト
                header('Location: ' . $_SERVER['PHP_SELF']);
                exit;
            }
        }
    }
}
?>
<!DOCTYPE html>
<html>
    <head>
        <title><?php echo htmlspecialchars($config['app_name']); ?></title>
        <!-- Debug: <?php echo htmlspecialchars($config['app_name']); ?> -->
        <style>
            :root {
                --primary-color:rgb(0, 93, 11);
                --secondary-color:rgb(0, 159, 13);
                --accent-color:rgb(71, 197, 75);
                --background-color: #f5f5f5;
                --text-color: #666666;
                --border-color: #ddd;
            }

            body {
                font-family: 'Helvetica Neue', Arial, sans-serif;
                max-width: 800px;
                margin: 0 auto;
                padding: 20px;
                background-color: var(--background-color);
                color: var(--text-color);
            }

            h1 {
                color: var(--primary-color);
                text-align: center;
                margin-bottom: 30px;
                font-size: 2.2em;
            }

            h2 {
                color: var(--secondary-color);
                margin-top: 30px;
                padding-bottom: 10px;
                border-bottom: 2px solid var(--secondary-color);
            }

            form {
                background-color: white;
                padding: 25px;
                border-radius: 10px;
                box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            }

            .form-group {
                margin-bottom: 20px;
            }

            label {
                display: block;
                margin-bottom: 5px;
                color: var(--primary-color);
                font-weight: bold;
            }

            input[type="text"],
            input[type="date"] {
                width: 100%;
                padding: 8px;
                border: 1px solid var(--border-color);
                border-radius: 4px;
                font-size: 16px;
            }

            input[type="submit"] {
                background-color: var(--accent-color);
                color: white;
                padding: 10px 20px;
                border: none;
                border-radius: 4px;
                cursor: pointer;
                font-size: 16px;
                margin-right: 10px;
            }

            input[type="submit"]:hover {
                background-color: #45a049;
            }

            input[type="reset"] {
                background-color: #f44336;
                color: white;
                padding: 10px 20px;
                border: none;
                border-radius: 4px;
                cursor: pointer;
                font-size: 16px;
            }

            input[type="reset"]:hover {
                background-color: #da190b;
            }

            .history {
                margin-top: 40px;
            }

            .record {
                background-color: white;
                border: 1px solid var(--border-color);
                padding: 20px;
                margin-bottom: 15px;
                border-radius: 8px;
                box-shadow: 0 2px 5px rgba(0,0,0,0.05);
                transition: transform 0.2s;
            }

            .record:hover {
                transform: translateY(-2px);
            }

            .record h3 {
                color: var(--primary-color);
                margin-top: 0;
                margin-bottom: 15px;
                font-size: 1.2em;
            }

            .calories {
                color: var(--secondary-color);
                font-size: 0.9em;
                font-weight: bold;
            }

            @media (max-width: 600px) {
                body {
                    padding: 10px;
                }
                
                form {
                    padding: 15px;
                }
                
                input[type="submit"],
                input[type="reset"] {
                    width: 100%;
                    margin-bottom: 10px;
                }
            }

            .error-message {
                background-color: #ffebee;
                color: #c62828;
                padding: 10px;
                margin-bottom: 20px;
                border-radius: 4px;
                border: 1px solid #ef9a9a;
            }

            .record-actions {
                margin-top: 10px;
                text-align: right;
            }

            .record-actions form {
                display: inline;
                margin: 0;
                padding: 0;
                background: none;
                box-shadow: none;
            }

            .edit-btn, .delete-btn {
                padding: 5px 10px;
                margin-left: 5px;
                border: none;
                border-radius: 4px;
                cursor: pointer;
                font-size: 14px;
                outline: none !important;
                -webkit-tap-highlight-color: transparent;
                -webkit-appearance: none;
                -moz-appearance: none;
                appearance: none;
                background: none;
            }

            .edit-btn {
                background-color: var(--accent-color);
                color: white;
            }

            .delete-btn {
                background-color: #f44336;
                color: white;
            }

            .edit-btn:focus, .delete-btn:focus,
            .edit-btn:active, .delete-btn:active,
            .edit-btn:hover:focus, .delete-btn:hover:focus,
            .edit-btn:active:focus, .delete-btn:active:focus {
                outline: none !important;
                box-shadow: none !important;
                -webkit-tap-highlight-color: transparent;
            }

            .modal {
                display: none;
                position: fixed;
                z-index: 1;
                left: 0;
                top: 0;
                width: 100%;
                height: 100%;
                background-color: rgba(0,0,0,0.4);
            }

            .modal-content {
                background-color: #fefefe;
                margin: 15% auto;
                padding: 20px;
                border: 1px solid #888;
                width: 80%;
                max-width: 500px;
                border-radius: 8px;
            }

            .close {
                color: #aaa;
                float: right;
                font-size: 28px;
                font-weight: bold;
                cursor: pointer;
            }

            .nav-links {
                text-align: center;
                margin-bottom: 20px;
            }

            .nav-links a {
            color: var(--primary-color);
            text-decoration: none;
            margin: 0 10px;
            padding: 5px 10px;
            border-radius: 4px;
            }

            .close:hover {
                color: black;
            }
        </style>
    </head>
    <body>
        <h1><?php echo htmlspecialchars($config['app_name']); ?> - 記録入力</h1>
        
        <div class="nav-links">
        <a href="syokujisummary.php">記録サマリー</a>
        <a href="syokujikensaku.php">記録検索</a>
    </div>

        <?php if ($error_message): ?>
            <div class="error-message">
                <?php echo htmlspecialchars($error_message); ?>
            </div>
        <?php endif; ?>
        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
            <input type="hidden" name="token" value="<?php echo htmlspecialchars($_SESSION['token']); ?>">
            <div class="form-group">
                <label>日付：</label>
                <input type="date" name="date" value="<?php echo date($config['date_format']); ?>" required>
            </div>
            <div class="form-group">
                <label>朝食：</label>
                <input type="text" name="breakfast" placeholder="例：トースト、卵、サラダ" required>
            </div>
            <div class="form-group">
                <label>昼食：</label>
                <input type="text" name="lunch" placeholder="例：カレーライス、サラダ" required>
            </div>
            <div class="form-group">
                <label>夕食：</label>
                <input type="text" name="dinner" placeholder="例：味噌汁、ご飯、焼き魚" required>
            </div>
            <div class="form-group">
                <label>間食：</label>
                <input type="text" name="snack" placeholder="例：果物、ヨーグルト">
            </div>
            <input type="submit" value="記録する">
            <input type="reset" value="リセット">
        </form>

        <div class="history">
            <h2>過去の記録</h2>
            <?php
            $mealRecord = new MealRecord();
            $records = $mealRecord->getAll();
            if (empty($records)) {
                echo "<p>食事を記録してみましょう。</p>";
            } else {
                foreach ($records as $record) {
                    echo "<div class='record'>";
                    echo "<h3>" . htmlspecialchars($record['date']) . "</h3>";
                    echo "朝食：" . htmlspecialchars($record['breakfast']) . 
                         " <span class='calories'>(" . $record['breakfast_calories'] . "kcal)</span><br>";
                    echo "昼食：" . htmlspecialchars($record['lunch']) . 
                         " <span class='calories'>(" . $record['lunch_calories'] . "kcal)</span><br>";
                    echo "夕食：" . htmlspecialchars($record['dinner']) . 
                         " <span class='calories'>(" . $record['dinner_calories'] . "kcal)</span><br>";
                    if (!empty($record['snack'])) {
                        echo "間食：" . htmlspecialchars($record['snack']) . 
                             " <span class='calories'>(" . $record['snack_calories'] . "kcal)</span><br>";
                    }
                    echo "<div class='record-actions'>";
                    echo "<button onclick='openEditModal({$record['id']})' class='edit-btn'>編集</button>";
                    echo "<form method='POST' style='display: inline;' onsubmit='return confirm(\"この記録を削除してもよろしいですか？\");'>";
                    echo "<input type='hidden' name='token' value='" . htmlspecialchars($_SESSION['token']) . "'>";
                    echo "<input type='hidden' name='action' value='delete'>";
                    echo "<input type='hidden' name='record_id' value='{$record['id']}'>";
                    echo "<button type='submit' class='delete-btn'>削除</button>";
                    echo "</form>";
                    echo "</div>";
                    echo "</div>";
                }
            }
            ?>
        </div>

        <!-- 編集用モーダル -->
        <div id="editModal" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <h2>記録の編集</h2>
                <form id="editForm" method="POST">
                    <input type="hidden" name="token" value="<?php echo htmlspecialchars($_SESSION['token']); ?>">
                    <input type="hidden" name="action" value="edit">
                    <input type="hidden" name="record_id" id="editRecordId">
                    <div class="form-group">
                        <label>日付：</label>
                        <input type="date" name="date" id="editDate" required>
                    </div>
                    <div class="form-group">
                        <label>朝食：</label>
                        <input type="text" name="breakfast" id="editBreakfast" required>
                    </div>
                    <div class="form-group">
                        <label>昼食：</label>
                        <input type="text" name="lunch" id="editLunch" required>
                    </div>
                    <div class="form-group">
                        <label>夕食：</label>
                        <input type="text" name="dinner" id="editDinner" required>
                    </div>
                    <div class="form-group">
                        <label>間食：</label>
                        <input type="text" name="snack" id="editSnack">
                    </div>
                    <input type="submit" value="保存">
                </form>
            </div>
        </div>

        <script>
            // モーダル関連の変数
            const modal = document.getElementById("editModal");
            const span = document.getElementsByClassName("close")[0];
            let records = <?php echo json_encode($records); ?>;

            // 編集モーダルを開く
            function openEditModal(id) {
                const record = records.find(r => r.id === id);
                document.getElementById("editRecordId").value = id;
                document.getElementById("editDate").value = record.date;
                document.getElementById("editBreakfast").value = record.breakfast;
                document.getElementById("editLunch").value = record.lunch;
                document.getElementById("editDinner").value = record.dinner;
                document.getElementById("editSnack").value = record.snack || '';
                modal.style.display = "block";
            }

            // モーダルを閉じる
            span.onclick = function() {
                modal.style.display = "none";
            }

            window.onclick = function(event) {
                if (event.target == modal) {
                    modal.style.display = "none";
                }
            }
        </script>
    </body>
</html>