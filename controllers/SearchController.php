<?php
require_once __DIR__ . '/../models/MealSearch.php';

class SearchController {
    private $mealSearch;
    private $error_message = '';
    private $records = [];
    private $search_date = '';
    private $search_name = '';

    public function __construct() {
        $this->mealSearch = new MealSearch();
    }

    public function handleRequest() {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (isset($_POST['search_date'])) {
                $this->search_date = $_POST['search_date'];
                $this->records = $this->mealSearch->searchByDate($this->search_date);
            } elseif (isset($_POST['search_name'])) {
                $this->search_name = $_POST['search_name'];
                $this->records = $this->mealSearch->searchByName($this->search_name);
            } elseif (isset($_POST['start_date']) && isset($_POST['end_date'])) {
                $start_date = $_POST['start_date'];
                $end_date = $_POST['end_date'];
                
                if (strtotime($start_date) <= strtotime($end_date)) {
                    $this->records = $this->mealSearch->searchByDateRange($start_date, $end_date);
                } else {
                    $this->error_message = '開始日は終了日より前の日付を指定してください。';
                }
            }
        }
    }

    public function getError() {
        return $this->error_message;
    }

    public function getRecords() {
        return $this->records;
    }

    public function getSearchDate() {
        return $this->search_date;
    }

    public function getSearchName() {
        return $this->search_name;
    }
} 