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

// データ保存用のファイルパス
$dataFile = $config['data_file'];

// エラーメッセージの初期化
$error_message = '';

// データの読み込み
function loadMealRecords() {
    $mealRecord = new MealRecord();
    return $mealRecord->getAll();
}

// トークンの生成
if (!isset($_SESSION['token'])) {
    $_SESSION['token'] = bin2hex(random_bytes(32));
}

// 検索結果の初期化
$records = [];
$search_date = '';
$search_name = '';

// POSTリクエストの処理
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $all_records = loadMealRecords();
    
    // 単一の日付での検索
    if (isset($_POST['search_date'])) {
        $search_date = $_POST['search_date'];
        foreach ($all_records as $record) {
            if ($record['date'] === $search_date) {
                $records[] = $record;
            }
        }
    }
    // 料理名での検索
    elseif (isset($_POST['search_name'])) {
        $search_name = $_POST['search_name'];
        foreach ($all_records as $record) {
            // 朝食、昼食、夕食、間食のいずれかに検索語が含まれているかチェック
            if (stripos($record['breakfast'], $search_name) !== false ||
                stripos($record['lunch'], $search_name) !== false ||
                stripos($record['dinner'], $search_name) !== false ||
                (!empty($record['snack']) && stripos($record['snack'], $search_name) !== false)) {
                $records[] = $record;
            }
        }
    }
    // 期間指定での検索
    elseif (isset($_POST['start_date']) && isset($_POST['end_date'])) {
        $start_date = $_POST['start_date'];
        $end_date = $_POST['end_date'];
        
        // 日付の妥当性チェック
        if (strtotime($start_date) <= strtotime($end_date)) {
            foreach ($all_records as $record) {
                if (strtotime($record['date']) >= strtotime($start_date) && 
                    strtotime($record['date']) <= strtotime($end_date)) {
                    $records[] = $record;
                }
            }
        } else {
            $error_message = '開始日は終了日より前の日付を指定してください。';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($config['app_name']); ?> - 検索</title>
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

        .search-forms {
            display: flex;
            gap: 20px;
            margin-bottom: 30px;
        }

        .search-form {
            flex: 1;
            background-color: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        /* レスポンシブデザイン対応 */
        @media screen and (max-width: 600px) {
            .search-forms {
                flex-direction: column;
                gap: 15px;
            }

            .search-form {
                width: 100%;
            }

            body {
                padding: 15px;
            }

            h1 {
                font-size: 1.8em;
                margin-bottom: 20px;
            }

            .record {
                padding: 15px;
            }
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

        input[type="date"],
        input[type="text"] {
            width: 100%;
            padding: 8px;
            border: 1px solid var(--border-color);
            border-radius: 4px;
            font-size: 16px;
            box-sizing: border-box;
        }

        button[type="submit"] {
            background-color: var(--accent-color);
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            width: 100%;
        }

        button[type="submit"]:hover {
            background-color: #45a049;
        }

        .record {
            background-color: white;
            border: 1px solid var(--border-color);
            padding: 20px;
            margin-bottom: 15px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
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

        .no-results {
            text-align: center;
            padding: 20px;
            background-color: white;
            border-radius: 8px;
            margin-top: 20px;
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

        .nav-links a:hover {
            background-color: rgba(0, 93, 11, 0.1);
        }
    </style>
</head>
<body>
    <h1><?php echo htmlspecialchars($config['app_name']); ?> - 検索</h1>
    
    <div class="nav-links">
        <a href="syokujinyuryoku.php">記録入力</a>
        <a href="syokujisummary.php">記録サマリー</a>
    </div>

    <div class="search-forms">
        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post" class="search-form">
            <div class="form-group">
                <label for="search_date">検索日：</label>
                <input type="date" id="search_date" name="search_date" value="<?php echo htmlspecialchars($search_date); ?>" required>
            </div>
            <button type="submit">検索</button>
        </form>
        
        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post" class="search-form">
            <div class="form-group">
                <label for="search_name">料理名：</label>
                <input type="text" id="search_name" name="search_name" value="<?php echo htmlspecialchars($search_name); ?>" required>
            </div>
            <button type="submit">検索</button>
        </form>
    </div>

    <?php if (!empty($error_message)): ?>
        <div class="error-message">
            <?php echo htmlspecialchars($error_message); ?>
        </div>
    <?php endif; ?>

    <?php if ($_SERVER["REQUEST_METHOD"] == "POST"): ?>
        <?php if (empty($records)): ?>
            <div class="no-results">
                <p><?php 
                    if (isset($_POST['search_name'])) {
                        echo htmlspecialchars($search_name) . 'を含む記録は見つかりませんでした。';
                    } else {
                        echo 'その日は記録していないようです。';
                    }
                ?></p>
            </div>
        <?php else: ?>
            <h2>検索結果</h2>
            <?php foreach ($records as $record): ?>
                <div class="record">
                    <h3><?php echo htmlspecialchars($record['date']); ?></h3>
                    <p>朝食：<?php echo htmlspecialchars($record['breakfast']); ?> 
                       <span class="calories">(<?php echo $record['breakfast_calories']; ?>kcal)</span></p>
                    <p>昼食：<?php echo htmlspecialchars($record['lunch']); ?> 
                       <span class="calories">(<?php echo $record['lunch_calories']; ?>kcal)</span></p>
                    <p>夕食：<?php echo htmlspecialchars($record['dinner']); ?> 
                       <span class="calories">(<?php echo $record['dinner_calories']; ?>kcal)</span></p>
                    <?php if (!empty($record['snack'])): ?>
                        <p>間食：<?php echo htmlspecialchars($record['snack']); ?> 
                           <span class="calories">(<?php echo $record['snack_calories']; ?>kcal)</span></p>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    <?php endif; ?>
</body>
</html>
