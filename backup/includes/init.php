<?php
/**
 * アプリケーション初期化ファイル
 */

// 設定ファイルの読み込み
$config = require_once __DIR__ . '/../config/app.php';

// エンコーディング設定の適用
if (isset($config['security']['output_encoding'])) {
    $encoding = $config['security']['output_encoding'];
    
    // デフォルト文字エンコーディングの設定
    if (isset($encoding['default_charset'])) {
        ini_set('default_charset', $encoding['default_charset']);
    }
    
    // Content-Typeヘッダーの設定
    if (isset($encoding['content_type'])) {
        header('Content-Type: ' . $encoding['content_type']);
    }
    
    // 強制エンコーディングの設定
    if (isset($encoding['force_encoding']) && $encoding['force_encoding']) {
        mb_internal_encoding($encoding['default_charset']);
        mb_http_output($encoding['default_charset']);
        mb_regex_encoding($encoding['default_charset']);
    }
}

// エラーレポート設定
if (isset($config['error_reporting'])) {
    error_reporting($config['error_reporting']);
}

// デバッグモード設定
if (isset($config['debug'])) {
    ini_set('display_errors', $config['debug'] ? '1' : '0');
}

/**
 * 時間帯に応じた挨拶を返す
 * @return string 挨拶の文字列
 */
function getGreeting() {
    $hour = date('H');
    if ($hour >= 5 && $hour < 12) {
        return 'おはようございます';
    } elseif ($hour >= 12 && $hour < 18) {
        return 'こんにちは';
    } else {
        return 'こんばんは';
    }
} 