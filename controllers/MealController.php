<?php
require_once __DIR__ . '/../models/MealRecord.php';

class MealController {
    private $mealRecord;
    private $error_message = '';
    private $records = [];

    public function __construct() {
        $this->mealRecord = new MealRecord();
    }

    public function handleRequest() {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $this->handlePostRequest();
        }
        $this->records = $this->mealRecord->getAll();
    }

    private function handlePostRequest() {
        // トークンの検証
        if (!isset($_POST['token']) || $_POST['token'] !== $_SESSION['token']) {
            $this->error_message = 'セッションが切れました。もう一度お試しください。';
            $_SESSION['token'] = bin2hex(random_bytes(32));
            return;
        }

        // アクションの確認
        if (isset($_POST['action'])) {
            $id = $_POST['record_id'];

            if ($_POST['action'] === 'delete') {
                // 削除処理
                if ($this->mealRecord->delete($id)) {
                    header('Location: ' . $_SERVER['PHP_SELF']);
                    exit;
                }
            } elseif ($_POST['action'] === 'edit') {
                // 編集処理
                if (empty($_POST['date'])) {
                    $this->error_message = '日付を入力してください。';
                    return;
                }
                
                $breakfast_calories = $this->mealRecord->calculateCalories($_POST['breakfast']);
                $lunch_calories = $this->mealRecord->calculateCalories($_POST['lunch']);
                $dinner_calories = $this->mealRecord->calculateCalories($_POST['dinner']);
                $snack_calories = $this->mealRecord->calculateCalories($_POST['snack']);

                if ($this->mealRecord->update(
                    $id,
                    $_POST['date'],
                    $_POST['breakfast'],
                    $_POST['lunch'],
                    $_POST['dinner'],
                    $_POST['snack'],
                    $breakfast_calories,
                    $lunch_calories,
                    $dinner_calories,
                    $snack_calories
                )) {
                    header('Location: ' . $_SERVER['PHP_SELF']);
                    exit;
                }
            }
        } else {
            // 新規記録の処理
            // フォームの再送信防止
            if (isset($_SESSION['last_post']) && $_SESSION['last_post'] === $_POST) {
                header('Location: ' . $_SERVER['PHP_SELF']);
                exit;
            }

            $breakfast_calories = $this->mealRecord->calculateCalories($_POST["breakfast"]);
            $lunch_calories = $this->mealRecord->calculateCalories($_POST["lunch"]);
            $dinner_calories = $this->mealRecord->calculateCalories($_POST["dinner"]);
            $snack_calories = $this->mealRecord->calculateCalories($_POST["snack"]);

            // 日付の重複チェック
            if ($this->mealRecord->existsForDate($_POST["date"])) {
                $this->error_message = 'この日付の記録は既に存在します。編集または削除してください。';
                return;
            }

            if ($this->mealRecord->create(
                $_POST["date"],
                $_POST["breakfast"],
                $_POST["lunch"],
                $_POST["dinner"],
                $_POST["snack"],
                $breakfast_calories,
                $lunch_calories,
                $dinner_calories,
                $snack_calories
            )) {
                $_SESSION['last_post'] = $_POST;
                $_SESSION['token'] = bin2hex(random_bytes(32));
                header('Location: ' . $_SERVER['PHP_SELF']);
                exit;
            }
        }
    }

    public function getErrorMessage() {
        return $this->error_message;
    }

    public function getRecords() {
        return $this->records;
    }
} 