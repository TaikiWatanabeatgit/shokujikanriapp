<?php
class MealSummary {
    private $mealRecord;

    public function __construct() {
        require_once __DIR__ . '/MealRecord.php';
        $this->mealRecord = new MealRecord();
    }

    public function getTodayRecords() {
        $records = $this->mealRecord->getAll();
        $today = date('Y-m-d');
        return array_filter($records, function($record) use ($today) {
            return $record['date'] === $today;
        });
    }

    public function getPastRecords() {
        $records = $this->mealRecord->getAll();
        $today = date('Y-m-d');
        return array_filter($records, function($record) use ($today) {
            return $record['date'] < $today;
        });
    }

    public function getAllRecords() {
        return $this->mealRecord->getAll();
    }

    public function calculateTotalCalories($records) {
        $total = 0;
        foreach ($records as $record) {
            $total += $record['breakfast_calories'] + 
                     $record['lunch_calories'] + 
                     $record['dinner_calories'] + 
                     ($record['snack_calories'] ?? 0);
        }
        return $total;
    }

    public function calculateAverageCalories($records) {
        if (empty($records)) {
            return 0;
        }
        $total = $this->calculateTotalCalories($records);
        return round($total / count($records));
    }
} 