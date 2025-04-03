<?php
require_once __DIR__ . '/Database.php';

class MealRecord {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function create($date, $breakfast, $lunch, $dinner, $snack, $breakfast_calories, $lunch_calories, $dinner_calories, $snack_calories) {
        $sql = "INSERT INTO meal_records (date, breakfast, lunch, dinner, snack, breakfast_calories, lunch_calories, dinner_calories, snack_calories) 
                VALUES (:date, :breakfast, :lunch, :dinner, :snack, :breakfast_calories, :lunch_calories, :dinner_calories, :snack_calories)";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':date' => $date,
            ':breakfast' => $breakfast,
            ':lunch' => $lunch,
            ':dinner' => $dinner,
            ':snack' => $snack,
            ':breakfast_calories' => $breakfast_calories,
            ':lunch_calories' => $lunch_calories,
            ':dinner_calories' => $dinner_calories,
            ':snack_calories' => $snack_calories
        ]);
    }

    public function getAll() {
        $sql = "SELECT * FROM meal_records ORDER BY date DESC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }

    public function getById($id) {
        $sql = "SELECT * FROM meal_records WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    public function update($id, $date, $breakfast, $lunch, $dinner, $snack, $breakfast_calories, $lunch_calories, $dinner_calories, $snack_calories) {
        $sql = "UPDATE meal_records 
                SET date = :date, breakfast = :breakfast, lunch = :lunch, dinner = :dinner, 
                    snack = :snack, breakfast_calories = :breakfast_calories, 
                    lunch_calories = :lunch_calories, dinner_calories = :dinner_calories, 
                    snack_calories = :snack_calories 
                WHERE id = :id";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':id' => $id,
            ':date' => $date,
            ':breakfast' => $breakfast,
            ':lunch' => $lunch,
            ':dinner' => $dinner,
            ':snack' => $snack,
            ':breakfast_calories' => $breakfast_calories,
            ':lunch_calories' => $lunch_calories,
            ':dinner_calories' => $dinner_calories,
            ':snack_calories' => $snack_calories
        ]);
    }

    public function delete($id) {
        $sql = "DELETE FROM meal_records WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }
} 