<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\MealSearch;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/search', function (Request $request) {
    $mealSearch = new MealSearch();
    $response = [
        'error' => '',
        'records' => []
    ];

    try {
        $response['records'] = $mealSearch->searchByDate($request->input('search_date'));
    } catch (Exception $e) {
        $response['error'] = '検索中にエラーが発生しました。';
    }

    return response()->json($response);
}); 