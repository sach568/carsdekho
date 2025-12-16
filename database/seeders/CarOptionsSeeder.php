<?php

namespace Database\Seeders;

use App\Models\CarOption;
use Illuminate\Database\Seeder;

class CarOptionsSeeder extends Seeder
{
    public function run()
    {
        $options = [
            ['name' => 'Hatchback', 'active' => true],
            ['name' => 'Sedan', 'active' => true],
            ['name' => 'SUV', 'active' => true],
        ];

        foreach ($options as $option) {
            CarOption::create($option);
        }
    }
}