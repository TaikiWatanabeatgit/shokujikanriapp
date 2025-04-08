<?php
class MealSearch {
    private $mealRecord;

    public function __construct() {
        require_once __DIR__ . '/MealRecord.php';
        $this->mealRecord = new MealRecord();
    }

    public function searchByDate($date) {
        $all_records = $this->mealRecord->getAll();
        $records = [];
        foreach ($all_records as $record) {
            if ($record['date'] === $date) {
                $records[] = $record;
            }
        }
        return $records;
    }

    public function searchByName($name) {
        $all_records = $this->mealRecord->getAll();
        $records = [];
        foreach ($all_records as $record) {
            if (stripos($record['breakfast'], $name) !== false ||
                stripos($record['lunch'], $name) !== false ||
                stripos($record['dinner'], $name) !== false ||
                (!empty($record['snack']) && stripos($record['snack'], $name) !== false)) {
                $records[] = $record;
            }
        }
        return $records;
    }

    public function searchByDateRange($start_date, $end_date) {
        $all_records = $this->mealRecord->getAll();
        $records = [];
        foreach ($all_records as $record) {
            if (strtotime($record['date']) >= strtotime($start_date) && 
                strtotime($record['date']) <= strtotime($end_date)) {
                $records[] = $record;
            }
        }
        return $records;
    }
} 