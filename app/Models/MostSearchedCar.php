<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MostSearchedCar extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'model',
        'price',
        'image',
        'search_count',
        'active'
    ];
}