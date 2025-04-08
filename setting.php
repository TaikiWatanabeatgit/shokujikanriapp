<?php
// 設定ファイルの読み込み
$config = require_once 'config/app.php';

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
require_once 'controllers/SettingController.php';
$controller = new SettingController();
$controller->handleRequest();

// Viewに渡す変数の準備
$message = $controller->getMessage();
$error = $controller->getError();
$currentSettings = $controller->getCurrentSettings();

// Viewの読み込み
require_once 'views/setting.php';
