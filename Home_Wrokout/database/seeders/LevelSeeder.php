<?php

namespace Database\Seeders;

use App\Models\Level;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LevelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Level::create([
            'name' => 'beginner',
            'description' => 'A beginner level has the basic exercises'
        ]);

        Level::create([
            'name' => 'intermediate',
            'description' => 'An intermediate level has the intermediate exercises'
        ]);

        Level::create([
            'name' => 'advance',
            'description' => 'An advance level has the advance exercises'
        ]);
    }
}
