<?php
require_once __DIR__ . '/../includes/init.php';
require_once __DIR__ . '/../models/MealSearch.php';

header('Content-Type: application/json');

$mealSearch = new MealSearch();
$response = [
    'error' => '',
    'records' => []
];

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['search_date'])) {
    try {
        $response['records'] = $mealSearch->searchByDate($_POST['search_date']);
    } catch (Exception $e) {
        $response['error'] = '検索中にエラーが発生しました。';
    }
}

echo json_encode($response); 