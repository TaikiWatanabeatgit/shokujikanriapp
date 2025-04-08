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

// データ保存用のファイルパス
$dataFile = $config['data_file'];

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

// 検索結果の初期化
$records = [];
$search_date = '';
$search_name = '';

// Controllerの読み込みと実行
require_once 'controllers/SearchController.php';
$controller = new SearchController();
$controller->handleRequest();

// Viewに渡す変数の準備
$error_message = $controller->getError();
$records = $controller->getRecords();
$search_date = $controller->getSearchDate();
$search_name = $controller->getSearchName();

// Viewの読み込み
require_once 'views/search.php'; 