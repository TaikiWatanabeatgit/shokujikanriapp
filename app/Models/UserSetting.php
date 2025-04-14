<?php
class UserSetting {
    private $pdo;

    public function __construct() {
        $dbConfig = require __DIR__ . '/../config/database.php';
        $dsn = "mysql:host={$dbConfig['host']};dbname={$dbConfig['dbname']};charset={$dbConfig['charset']}";
        try {
            $this->pdo = new PDO($dsn, $dbConfig['username'], $dbConfig['password']);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("データベース接続エラー: " . $e->getMessage());
        }
        $this->createTableIfNotExists();
    }

    private function createTableIfNotExists() {
        $createTableQuery = "CREATE TABLE IF NOT EXISTS user_settings (
            id INT AUTO_INCREMENT PRIMARY KEY,
            height FLOAT,
            weight FLOAT,
            gender ENUM('male', 'female'),
            age INT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        )";
        $this->pdo->exec($createTableQuery);
    }

    public function saveSettings($height, $weight, $gender, $age) {
        $stmt = $this->pdo->prepare("INSERT INTO user_settings (height, weight, gender, age) 
                                    VALUES (?, ?, ?, ?) 
                                    ON DUPLICATE KEY UPDATE 
                                    height = VALUES(height), 
                                    weight = VALUES(weight), 
                                    gender = VALUES(gender), 
                                    age = VALUES(age)");
        return $stmt->execute([$height, $weight, $gender, $age]);
    }

    public function getCurrentSettings() {
        $stmt = $this->pdo->query("SELECT * FROM user_settings ORDER BY id DESC LIMIT 1");
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function validateSettings($height, $weight, $gender, $age) {
        return $height && $weight && $gender && $age;
    }
} 