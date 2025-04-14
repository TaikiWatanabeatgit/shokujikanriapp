<?php

namespace App\Http\Controllers;

use App\Models\MealRecord;
use Illuminate\Http\Request;

class MealRecordController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $mealRecords = MealRecord::orderBy('date', 'desc')->paginate(10);
        return view('meal-records.index', compact('mealRecords'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('meal-records.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'meal_type' => 'required|in:breakfast,lunch,dinner,snack',
            'food_name' => 'required|string|max:255',
            'calories' => 'nullable|integer|min:0',
            'notes' => 'nullable|string',
        ]);

        MealRecord::create($validated);

        return redirect()->route('meal-records.index')
            ->with('success', '食事記録が正常に保存されました。');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $mealRecord = MealRecord::findOrFail($id);
        return view('meal-records.edit', compact('mealRecord'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $mealRecord = MealRecord::findOrFail($id);

        $validated = $request->validate([
            'date' => 'required|date',
            'meal_type' => 'required|in:breakfast,lunch,dinner,snack',
            'food_name' => 'required|string|max:255',
            'calories' => 'nullable|integer|min:0',
            'notes' => 'nullable|string',
        ]);

        $mealRecord->update($validated);

        return redirect()->route('meal-records.index')
            ->with('success', '食事記録が正常に更新されました。');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $mealRecord = MealRecord::findOrFail($id);
        $mealRecord->delete();

        return redirect()->route('meal-records.index')
            ->with('success', '食事記録が正常に削除されました。');
    }

    /**
     * Display the summary of meal records.
     */
    public function summary()
    {
        $today = now()->format('Y-m-d');
        $currentMonth = now()->format('Y-m');
        
        // 今日の記録
        $todayRecords = MealRecord::whereDate('date', $today)
            ->orderBy('meal_type')
            ->get();
        
        // 過去の記録（今日以外）
        $pastRecords = MealRecord::whereDate('date', '<', $today)
            ->orderBy('date', 'desc')
            ->get();
        
        // 今月の合計カロリー
        $monthlyTotalCalories = MealRecord::whereYear('date', now()->year)
            ->whereMonth('date', now()->month)
            ->sum('calories');
        
        // 過去の平均カロリー（今日以外）
        $pastAverageCalories = $pastRecords->avg('calories') ?? 0;
        
        return view('meal-records.summary', compact(
            'todayRecords',
            'pastRecords',
            'monthlyTotalCalories',
            'pastAverageCalories'
        ));
    }
}
