<?php
require_once __DIR__ . '/../models/MealSummary.php';

class SummaryController {
    private $mealSummary;
    private $todayRecords = [];
    private $pastRecords = [];
    private $monthlyTotalCalories = 0;
    private $pastAverageCalories = 0;

    public function __construct() {
        $this->mealSummary = new MealSummary();
    }

    public function handleRequest() {
        $this->todayRecords = $this->mealSummary->getTodayRecords();
        $this->pastRecords = $this->mealSummary->getPastRecords();
        
        // 今月の合計カロリーを計算
        $currentMonth = date('Y-m');
        $allRecords = $this->mealSummary->getAllRecords();
        $monthlyRecords = array_filter($allRecords, function($record) use ($currentMonth) {
            return substr($record['date'], 0, 7) === $currentMonth;
        });
        $this->monthlyTotalCalories = $this->mealSummary->calculateTotalCalories($monthlyRecords);
        
        if (!empty($this->pastRecords)) {
            $this->pastAverageCalories = $this->mealSummary->calculateAverageCalories($this->pastRecords);
        }
    }

    public function getTodayRecords() {
        return $this->todayRecords;
    }

    public function getPastRecords() {
        return $this->pastRecords;
    }

    public function getMonthlyTotalCalories() {
        return $this->monthlyTotalCalories;
    }

    public function getPastAverageCalories() {
        return $this->pastAverageCalories;
    }
} 