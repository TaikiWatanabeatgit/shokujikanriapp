@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>食事記録の編集</h1>
        <a href="{{ route('meal-records.index') }}" class="btn btn-secondary">戻る</a>
    </div>

    <form action="{{ route('meal-records.update', $mealRecord->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="date" class="form-label">日付</label>
            <input type="date" class="form-control" id="date" name="date" value="{{ old('date', $mealRecord->date->format('Y-m-d')) }}" required>
        </div>

        <div class="mb-3">
            <label for="meal_type" class="form-label">食事の種類</label>
            <select class="form-select" id="meal_type" name="meal_type" required>
                <option value="breakfast" {{ old('meal_type', $mealRecord->meal_type) == 'breakfast' ? 'selected' : '' }}>朝食</option>
                <option value="lunch" {{ old('meal_type', $mealRecord->meal_type) == 'lunch' ? 'selected' : '' }}>昼食</option>
                <option value="dinner" {{ old('meal_type', $mealRecord->meal_type) == 'dinner' ? 'selected' : '' }}>夕食</option>
                <option value="snack" {{ old('meal_type', $mealRecord->meal_type) == 'snack' ? 'selected' : '' }}>間食</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="food_name" class="form-label">食事名</label>
            <input type="text" class="form-control" id="food_name" name="food_name" value="{{ old('food_name', $mealRecord->food_name) }}" required>
        </div>

        <div class="mb-3">
            <label for="calories" class="form-label">カロリー</label>
            <input type="number" class="form-control" id="calories" name="calories" value="{{ old('calories', $mealRecord->calories) }}" min="0">
        </div>

        <div class="mb-3">
            <label for="notes" class="form-label">メモ</label>
            <textarea class="form-control" id="notes" name="notes" rows="3">{{ old('notes', $mealRecord->notes) }}</textarea>
        </div>

        <button type="submit" class="btn btn-primary">更新</button>
    </form>
@endsection 