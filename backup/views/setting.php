<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($config['app_name']); ?> - ユーザー情報</title>
    <link rel="stylesheet" href="css/style.css">
    <script src="js/setting.js" defer></script>
</head>
<body>
    <h1><?php echo htmlspecialchars($config['app_name']); ?> - ユーザー情報</h1>
    
    <div class="nav-links">
        <a href="shokujiNyuryoku.php">記録入力</a>
        <a href="shokujiKensaku.php">記録検索</a>
        <a href="shokujiSummary.php">記録サマリー</a>
    </div>

    <?php if (!empty($error)): ?>
        <div class="error-message">
            <?php echo htmlspecialchars($error); ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($message)): ?>
        <div class="success-message">
            <?php echo htmlspecialchars($message); ?>
        </div>
    <?php endif; ?>

    <form method="post" action="">
        <div class="form-group">
            <label for="height">身長 (cm):</label>
            <input type="number" id="height" name="height" step="0.1" 
                   value="<?php echo htmlspecialchars($currentSettings['height'] ?? ''); ?>" required>
        </div>

        <div class="form-group">
            <label for="weight">体重 (kg):</label>
            <input type="number" id="weight" name="weight" step="0.1" 
                   value="<?php echo htmlspecialchars($currentSettings['weight'] ?? ''); ?>" required>
        </div>

        <div class="form-group">
            <label for="gender">性別:</label>
            <select id="gender" name="gender" required>
                <option value="">選択してください</option>
                <option value="male" <?php echo ($currentSettings['gender'] ?? '') === 'male' ? 'selected' : ''; ?>>男性</option>
                <option value="female" <?php echo ($currentSettings['gender'] ?? '') === 'female' ? 'selected' : ''; ?>>女性</option>
            </select>
        </div>

        <div class="form-group">
            <label for="age">年齢:</label>
            <input type="number" id="age" name="age" 
                   value="<?php echo htmlspecialchars($currentSettings['age'] ?? ''); ?>" required>
        </div>

        <div class="form-actions">
            <button type="submit" class="submit-btn">保存</button>
            <button type="button" class="reset-btn" style="background-color: #f44336; color: white; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer;">リセット</button>
        </div>
    </form>
</body>
</html> 