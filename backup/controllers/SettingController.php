<?php
require_once __DIR__ . '/../models/UserSetting.php';

class SettingController {
    private $userSetting;
    private $message = '';
    private $error = '';
    private $currentSettings = null;

    public function __construct() {
        $this->userSetting = new UserSetting();
    }

    public function handleRequest() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->handlePostRequest();
        }
        $this->currentSettings = $this->userSetting->getCurrentSettings();
    }

    private function handlePostRequest() {
        $height = filter_input(INPUT_POST, 'height', FILTER_VALIDATE_FLOAT);
        $weight = filter_input(INPUT_POST, 'weight', FILTER_VALIDATE_FLOAT);
        $gender = filter_var($_POST['gender'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $age = filter_input(INPUT_POST, 'age', FILTER_VALIDATE_INT);

        if ($this->userSetting->validateSettings($height, $weight, $gender, $age)) {
            if ($this->userSetting->saveSettings($height, $weight, $gender, $age)) {
                $this->message = "設定を保存しました";
            } else {
                $this->error = "設定の保存に失敗しました";
            }
        } else {
            $this->error = "入力値が不正です";
        }
    }

    public function getMessage() {
        return $this->message;
    }

    public function getError() {
        return $this->error;
    }

    public function getCurrentSettings() {
        return $this->currentSettings;
    }
} 