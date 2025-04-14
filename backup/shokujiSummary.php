<?php
// 初期化ファイルの読み込み
require_once 'includes/init.php';

// 設定ファイルの読み込み
$config = require 'config/app.php';

// タイムゾーンの設定
date_default_timezone_set($config['timezone']);

// セッション設定
ini_set('session.cookie_lifetime', $config['session_lifetime']);
session_name($config['session_name']);
session_start();

// データベース関連のクラスを読み込み
require_once 'models/MealRecord.php';

// エラーメッセージの初期化
$error_message = '';

// データの読み込み
function loadMealRecords() {
    $mealRecord = new MealRecord();
    return $mealRecord->getAll();
}

// トークンの生成
if (!isset($_SESSION['token'])) {
    $_SESSION['token'] = bin2hex(random_bytes(32));
}

// Controllerの読み込みと実行
require_once 'controllers/SummaryController.php';
$controller = new SummaryController();
$controller->handleRequest();

// Viewに渡す変数の準備
$todayRecords = $controller->getTodayRecords();
$pastRecords = $controller->getPastRecords();
$monthlyTotalCalories = $controller->getMonthlyTotalCalories();
$pastAverageCalories = $controller->getPastAverageCalories();

// Viewの読み込み
require_once 'views/summary.php'; 