<?php

namespace Database\Seeders;

use App\Models\Plan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Plan::create([
            'name' => 'Beginner',
            'description' => 'Perfect for those just starting their fitness journey. Build a strong foundation with low-impact workouts and gradual progression.',
            'price' => null,
            'admin_id' => 1,
            'number_of_day_to_train' => 3,
            'level_id' => 1
        ]);

        Plan::create([
            'name' => 'Intermediate',
            'description' => 'Designed for those with some experience. Improve your strength, endurance, and technique with more challenging routines.',
            'price' => null,
            'admin_id' => 1,
            'number_of_day_to_train' => 4,
            'level_id' => 2
        ]);

        Plan::create([
            'name' => 'Advanced',
            'description' => 'For experienced athletes ready to push their limits. High-intensity workouts focused on performance, strength, and peak conditioning.',
            'price' => null,
            'admin_id' => 1,
            'number_of_day_to_train' => 5,
            'level_id' => 3
        ]);
    }
}
