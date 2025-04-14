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

// トークンの生成
if (!isset($_SESSION['token'])) {
    $_SESSION['token'] = bin2hex(random_bytes(32));
}

// Controllerの読み込みと実行
require_once 'controllers/MealController.php';
$controller = new MealController();
$controller->handleRequest();

// Viewに渡す変数の準備
$error_message = $controller->getErrorMessage();
$records = $controller->getRecords();
$greeting = getGreeting();  // 挨拶を取得

// MealRecordクラスのインスタンスを作成
require_once 'models/MealRecord.php';
$mealRecord = new MealRecord();

// Viewの読み込み
require_once 'views/mealInput.php'; 