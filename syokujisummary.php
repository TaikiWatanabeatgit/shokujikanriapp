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
    $mealRecord = new MealRecord();
    return $mealRecord->getAll();
}

// トークンの生成
if (!isset($_SESSION['token'])) {
    $_SESSION['token'] = bin2hex(random_bytes(32));
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($config['app_name']); ?> - サマリー</title>
    <style>
        :root {
            --primary-color: rgb(0, 93, 11);
            --secondary-color: rgb(0, 159, 13);
            --accent-color: rgb(71, 197, 75);
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

        .container {
            display: grid;
            gap: 30px;
        }

        .summary-section {
            background-color: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        .meal-list {
            margin-top: 20px;
        }

        .meal-item {
            background-color: var(--background-color);
            padding: 15px;
            margin-bottom: 15px;
            border-radius: 8px;
            border: 1px solid var(--border-color);
        }

        .meal-item h3 {
            color: var(--primary-color);
            margin-top: 0;
            margin-bottom: 10px;
        }

        .calories {
            color: var(--secondary-color);
            font-weight: bold;
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
            background-color: var(--accent-color);
            color: white;
        }

        .total-calories {
            margin-top: 20px;
            padding-top: 20px;
            border-top: 2px solid var(--border-color);
            font-weight: bold;
            text-align: right;
        }
    </style>
</head>
<body>
    <h1><?php echo htmlspecialchars($config['app_name']); ?> - サマリー</h1>
    
    <div class="nav-links">
        <a href="syokujinyuryoku.php">記録入力</a>
        <a href="syokujikensaku.php">記録検索</a>
    </div>

    <div class="container">
        <div class="summary-section">
            <h2>今日の食事</h2>
            <div class="meal-list">
                <?php
                $records = loadMealRecords();
                $today = date('Y-m-d');
                $todayRecords = array_filter($records, function($record) use ($today) {
                    return $record['date'] === $today;
                });

                if (empty($todayRecords)) {
                    echo '<p>今日は記録していないようです。</p>';
                } else {
                    $totalCalories = 0;
                    foreach ($todayRecords as $record) {
                        echo '<div class="meal-item">';
                        echo '<h3>' . htmlspecialchars($record['date']) . '</h3>';
                        echo '朝食：' . htmlspecialchars($record['breakfast']) . 
                             ' <span class="calories">(' . $record['breakfast_calories'] . 'kcal)</span><br>';
                        echo '昼食：' . htmlspecialchars($record['lunch']) . 
                             ' <span class="calories">(' . $record['lunch_calories'] . 'kcal)</span><br>';
                        echo '夕食：' . htmlspecialchars($record['dinner']) . 
                             ' <span class="calories">(' . $record['dinner_calories'] . 'kcal)</span><br>';
                        if (!empty($record['snack'])) {
                            echo '間食：' . htmlspecialchars($record['snack']) . 
                                 ' <span class="calories">(' . $record['snack_calories'] . 'kcal)</span><br>';
                        }
                        echo '</div>';
                        
                        $totalCalories += $record['breakfast_calories'] + 
                                        $record['lunch_calories'] + 
                                        $record['dinner_calories'] + 
                                        ($record['snack_calories'] ?? 0);
                    }
                    echo '<div class="total-calories">合計カロリー: ' . $totalCalories . 'kcal</div>';
                }
                ?>
            </div>
        </div>

        <div class="summary-section">
            <h2>過去の記録</h2>
            <div class="meal-list">
                <?php
                $records = loadMealRecords();
                $pastRecords = array_filter($records, function($record) use ($today) {
                    return $record['date'] < $today;
                });

                if (empty($pastRecords)) {
                    echo '<p>過去の記録はありません。</p>';
                } else {
                    $totalPastCalories = 0;
                    $recordCount = 0;
                    foreach ($pastRecords as $record) {
                        echo '<div class="meal-item">';
                        echo '<h3>' . htmlspecialchars($record['date']) . '</h3>';
                        echo '朝食：' . htmlspecialchars($record['breakfast']) . 
                             ' <span class="calories">(' . $record['breakfast_calories'] . 'kcal)</span><br>';
                        echo '昼食：' . htmlspecialchars($record['lunch']) . 
                             ' <span class="calories">(' . $record['lunch_calories'] . 'kcal)</span><br>';
                        echo '夕食：' . htmlspecialchars($record['dinner']) . 
                             ' <span class="calories">(' . $record['dinner_calories'] . 'kcal)</span><br>';
                        if (!empty($record['snack'])) {
                            echo '間食：' . htmlspecialchars($record['snack']) . 
                                 ' <span class="calories">(' . $record['snack_calories'] . 'kcal)</span><br>';
                        }
                        echo '</div>';
                        
                        // 1日の合計カロリーを計算
                        $dailyCalories = $record['breakfast_calories'] + 
                                        $record['lunch_calories'] + 
                                        $record['dinner_calories'] + 
                                        ($record['snack_calories'] ?? 0);
                        
                        $totalPastCalories += $dailyCalories;
                        $recordCount++;
                    }
                    
                    // 平均カロリーを計算
                    $averageCalories = $recordCount > 0 ? round($totalPastCalories / $recordCount) : 0;
                    echo '<div class="total-calories">過去の平均カロリー: ' . $averageCalories . 'kcal</div>';
                }
                
                ?>
            </div>
        </div>
    </div>
</body>
</html>