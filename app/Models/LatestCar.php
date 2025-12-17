<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LatestCar extends Model
{
    use HasFactory;
    protected $table = 'latest_cars';
    protected $fillable = [
        'name',
        'model',
        'price',
        'image',
        'features',
        'active'
    ];
}