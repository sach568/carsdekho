<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Footer extends Model
{
    use HasFactory;

    protected $fillable = [
        'logo',
        'description',
        'address',
        'phone',
        'email',
        'quick_links',
        'social_links'
    ];

    protected $casts = [
        'quick_links' => 'array',
        'social_links' => 'array'
    ];
}