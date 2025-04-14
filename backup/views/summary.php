<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($config['app_name']); ?> - サマリー</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <h1><?php echo htmlspecialchars($config['app_name']); ?> - サマリー</h1>
    
    <div class="nav-links">
        <a href="shokujiNyuryoku.php">記録入力</a>
        <a href="shokujiKensaku.php">記録検索</a>
        <a href="setting.php">ユーザー情報</a>
    </div>

    <div class="container">
        <div class="summary-section">
            <h2>今月の合計カロリー</h2>
            <p class="monthly-calories"><?php echo $monthlyTotalCalories; ?> kcal</p>
        </div>
        
        <div class="summary-section">
            <h2>今月の平均カロリー</h2>
            <p class="monthly-average"><?php echo $pastAverageCalories; ?> kcal</p>
        </div>
    </div>

    <div class="meal-list">
        <h2>最近の記録</h2>
        <?php foreach ($todayRecords as $record): ?>
            <div class="meal-item">
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
    </div>
</body>
</html> 