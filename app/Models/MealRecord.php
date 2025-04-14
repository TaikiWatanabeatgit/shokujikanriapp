<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MealRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'meal_type',
        'food_name',
        'calories',
        'notes'
    ];

    protected $casts = [
        'date' => 'date',
        'calories' => 'integer'
    ];
} 