<?php
class MealRecord {
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
        $createTableQuery = "CREATE TABLE IF NOT EXISTS meal_records (
            id INT AUTO_INCREMENT PRIMARY KEY,
            date DATE NOT NULL,
            breakfast TEXT,
            lunch TEXT,
            dinner TEXT,
            snack TEXT,
            breakfast_calories INT,
            lunch_calories INT,
            dinner_calories INT,
            snack_calories INT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        )";
        $this->pdo->exec($createTableQuery);
    }

    public function create($date, $breakfast, $lunch, $dinner, $snack, $breakfast_calories, $lunch_calories, $dinner_calories, $snack_calories) {
        $stmt = $this->pdo->prepare("INSERT INTO meal_records (date, breakfast, lunch, dinner, snack, breakfast_calories, lunch_calories, dinner_calories, snack_calories) 
                                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        return $stmt->execute([$date, $breakfast, $lunch, $dinner, $snack, $breakfast_calories, $lunch_calories, $dinner_calories, $snack_calories]);
    }

    public function update($id, $date, $breakfast, $lunch, $dinner, $snack, $breakfast_calories, $lunch_calories, $dinner_calories, $snack_calories) {
        $stmt = $this->pdo->prepare("UPDATE meal_records 
                                    SET date = ?, breakfast = ?, lunch = ?, dinner = ?, snack = ?,
                                        breakfast_calories = ?, lunch_calories = ?, dinner_calories = ?, snack_calories = ?
                                    WHERE id = ?");
        return $stmt->execute([$date, $breakfast, $lunch, $dinner, $snack, $breakfast_calories, $lunch_calories, $dinner_calories, $snack_calories, $id]);
    }

    public function delete($id) {
        $stmt = $this->pdo->prepare("DELETE FROM meal_records WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function getById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM meal_records WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getAll() {
        $stmt = $this->pdo->query("SELECT * FROM meal_records ORDER BY date DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function calculateCalories($meal) {
        $config = require __DIR__ . '/../config/app.php';
        return strlen($meal) * $config['calories_per_char'];
    }

    public function existsForDate($date) {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM meal_records WHERE date = ?");
        $stmt->execute([$date]);
        return $stmt->fetchColumn() > 0;
    }
} 