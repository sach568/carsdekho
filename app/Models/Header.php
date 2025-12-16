<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Header extends Model
{
    use HasFactory;

    protected $fillable = [
        'logo',
        'menu_items',
        'phone',
        'email',
        'active'
    ];

    protected $casts = [
        'menu_items' => 'array',
        'active' => 'boolean'
    ];
}