<?php
return array(
    // アプリケーション基本設定
    'app_name' => '食事管理アプリ',
    'timezone' => 'Asia/Tokyo',
    
    // データ保存設定
    'data_file' => 'meal_records.txt',
    
    // カロリー計算設定
    'calories_per_char' => 10,
    
    // 表示設定
    'max_records' => 100,  // 表示する最大記録数
    'date_format' => 'Y-m-d',
    
    // バリデーション設定
    'required_fields' => array('breakfast', 'lunch', 'dinner'),
    'max_input_length' => 1000,
    
    // セッション設定
    'session_name' => 'meal_session',
    'session_lifetime' => 7200,  // 2時間
    
    // デバッグ設定
    'debug' => true,
    'error_reporting' => E_ALL
); 