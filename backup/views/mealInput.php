<!DOCTYPE html>
<html>
<head>
    <title><?php echo htmlspecialchars($config['app_name']); ?> - 食事入力</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <h1><?php echo htmlspecialchars($config['app_name']); ?></h1>
        <p class="greeting"><?php echo htmlspecialchars($greeting); ?></p>

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

        <?php
        // 今日の日付の記録が存在するかチェック
        $today = date('Y-m-d');
        $hasTodayRecord = $mealRecord->existsForDate($today);
        ?>

        <form method="post" action="">
            <input type="hidden" name="token" value="<?php echo htmlspecialchars($_SESSION['token']); ?>">
            
            <div class="form-group">
                <label for="date">日付:</label>
                <input type="date" id="date" name="date" value="<?php 
                    if (isset($_POST['action']) && $_POST['action'] === 'edit' && isset($_POST['record_id'])) {
                        $record = $mealRecord->getById($_POST['record_id']);
                        echo htmlspecialchars($record['date']);
                    } else {
                        echo date('Y-m-d');
                    }
                ?>" required>
            </div>

            <div class="form-group">
                <label for="breakfast">朝食:</label>
                <input type="text" id="breakfast" name="breakfast" placeholder="朝食の内容を入力" value="<?php 
                    if (isset($_POST['action']) && $_POST['action'] === 'edit' && isset($_POST['record_id'])) {
                        echo htmlspecialchars($record['breakfast']);
                    }
                ?>">
            </div>

            <div class="form-group">
                <label for="lunch">昼食:</label>
                <input type="text" id="lunch" name="lunch" placeholder="昼食の内容を入力" value="<?php 
                    if (isset($_POST['action']) && $_POST['action'] === 'edit' && isset($_POST['record_id'])) {
                        echo htmlspecialchars($record['lunch']);
                    }
                ?>">
            </div>

            <div class="form-group">
                <label for="dinner">夕食:</label>
                <input type="text" id="dinner" name="dinner" placeholder="夕食の内容を入力" value="<?php 
                    if (isset($_POST['action']) && $_POST['action'] === 'edit' && isset($_POST['record_id'])) {
                        echo htmlspecialchars($record['dinner']);
                    }
                ?>">
            </div>

            <div class="form-group">
                <label for="snack">間食:</label>
                <input type="text" id="snack" name="snack" placeholder="間食の内容を入力" value="<?php 
                    if (isset($_POST['action']) && $_POST['action'] === 'edit' && isset($_POST['record_id'])) {
                        echo htmlspecialchars($record['snack']);
                    }
                ?>">
            </div>

            <div class="form-actions">
                <?php if (isset($_POST['action']) && $_POST['action'] === 'edit'): ?>
                    <input type="hidden" name="action" value="edit">
                    <input type="hidden" name="record_id" value="<?php echo htmlspecialchars($_POST['record_id']); ?>">
                    <button type="submit" class="submit-btn">更新</button>
                <?php else: ?>
                    <button type="submit" class="submit-btn">保存</button>
                <?php endif; ?>
                <button type="reset" class="reset-btn">リセット</button>
            </div>
        </form>

        <div class="history">
            <h2>過去の記録</h2>
            <?php if (empty($records)): ?>
                <p>記録がありません</p>
            <?php else: ?>
                <?php foreach ($records as $record): ?>
                    <div class="record">
                        <h3><?php echo htmlspecialchars($record['date']); ?></h3>
                        <p>朝食: <?php echo htmlspecialchars($record['breakfast']); ?> (<?php echo $record['breakfast_calories']; ?> kcal)</p>
                        <p>昼食: <?php echo htmlspecialchars($record['lunch']); ?> (<?php echo $record['lunch_calories']; ?> kcal)</p>
                        <p>夕食: <?php echo htmlspecialchars($record['dinner']); ?> (<?php echo $record['dinner_calories']; ?> kcal)</p>
                        <p>間食: <?php echo htmlspecialchars($record['snack']); ?> (<?php echo $record['snack_calories']; ?> kcal)</p>
                        
                        <div class="record-actions">
                            <form method="post" action="" class="inline-form">
                                <input type="hidden" name="token" value="<?php echo htmlspecialchars($_SESSION['token']); ?>">
                                <input type="hidden" name="action" value="edit">
                                <input type="hidden" name="record_id" value="<?php echo $record['id']; ?>">
                                <button type="submit" class="edit-btn">編集</button>
                            </form>
                            <form method="post" action="" class="inline-form">
                                <input type="hidden" name="token" value="<?php echo htmlspecialchars($_SESSION['token']); ?>">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="record_id" value="<?php echo $record['id']; ?>">
                                <button type="submit" class="delete-btn">削除</button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</body>
</html> 