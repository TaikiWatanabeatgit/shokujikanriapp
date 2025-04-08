<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($config['app_name']); ?> - 入力</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <h1><?php echo htmlspecialchars($config['app_name']); ?> - 入力</h1>
    
    <div class="nav-links">
        <a href="shokujiSummary.php">記録サマリー</a>
        <a href="shokujiKensaku.php">記録検索</a>
        <a href="setting.php">ユーザー情報</a>
    </div>

    <?php if (!empty($error_message)): ?>
        <div class="error-message">
            <?php echo htmlspecialchars($error_message); ?>
        </div>
    <?php endif; ?>

    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post" class="meal-form">
        <div class="form-group">
            <label for="date">日付：</label>
            <input type="date" id="date" name="date" value="<?php echo htmlspecialchars($date); ?>" required>
        </div>

        <div class="form-group">
            <label for="breakfast">朝食：</label>
            <input type="text" id="breakfast" name="breakfast" value="<?php echo htmlspecialchars($breakfast); ?>" required>
        </div>

        <div class="form-group">
            <label for="lunch">昼食：</label>
            <input type="text" id="lunch" name="lunch" value="<?php echo htmlspecialchars($lunch); ?>" required>
        </div>

        <div class="form-group">
            <label for="dinner">夕食：</label>
            <input type="text" id="dinner" name="dinner" value="<?php echo htmlspecialchars($dinner); ?>" required>
        </div>

        <div class="form-group">
            <label for="snack">間食：</label>
            <input type="text" id="snack" name="snack" value="<?php echo htmlspecialchars($snack); ?>">
        </div>

        <button type="submit">保存</button>
    </form>
</body>
</html> 