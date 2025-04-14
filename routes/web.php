<?php

use App\Http\Controllers\MealRecordController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('meal-records.index');
});

Route::get('/meal-records', [MealRecordController::class, 'index'])->name('meal-records.index');
Route::get('/meal-records/create', [MealRecordController::class, 'create'])->name('meal-records.create');
Route::post('/meal-records', [MealRecordController::class, 'store'])->name('meal-records.store');
Route::get('/meal-records/{id}', [MealRecordController::class, 'show'])->name('meal-records.show');
Route::get('/meal-records/{id}/edit', [MealRecordController::class, 'edit'])->name('meal-records.edit');
Route::put('/meal-records/{id}', [MealRecordController::class, 'update'])->name('meal-records.update');
Route::delete('/meal-records/{id}', [MealRecordController::class, 'destroy'])->name('meal-records.destroy');
