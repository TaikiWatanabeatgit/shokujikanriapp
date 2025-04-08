<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($config['app_name']); ?> - 検索</title>
    <link rel="stylesheet" href="css/style.css">
    <script src="js/search.js" defer></script>
    <style>
        .loading {
            text-align: center;
            padding: 20px;
            font-style: italic;
            color: #666;
        }
    </style>
</head>
<body>
    <h1><?php echo htmlspecialchars($config['app_name']); ?> - 検索</h1>
    
    <div class="nav-links">
        <a href="shokujiNyuryoku.php">記録入力</a>
        <a href="shokujiSummary.php">記録サマリー</a>
        <a href="setting.php">ユーザー情報</a>
    </div>

    <div class="search-forms">
        <form action="api/search.php" method="post" class="search-form" id="dateSearchForm">
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

    <?php if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['search_name'])): ?>
        <?php if (empty($records)): ?>
            <div class="no-results">
                <p><?php echo htmlspecialchars($search_name) . 'を含む記録は見つかりませんでした。'; ?></p>
            </div>
        <?php else: ?>
            <h2>検索結果</h2>
            <div class="meal-list">
                <?php foreach ($records as $record): ?>
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
        <?php endif; ?>
    <?php endif; ?>
</body>
</html> 