<?php

namespace Database\Seeders;

use App\Models\PlanDay;
use GuzzleHttp\Promise\Create;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PlanDaySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */

    public function addNewPlanDay($counter, $planID)
    {
        PlanDay::create([
            'day_number' => $counter,
            'total_calories' => 0,
            'is_rest_day' => false,
            'plan_id' => $planID
        ]);
    }

    public function AddRestDay($counter, $planID)
    {
        PlanDay::create([
            'day_number' => $counter,
            'total_calories' => 0,
            'is_rest_day' => true,
            'plan_id' => $planID
        ]);
    }


    public function run(): void
    {
        for ($counter = 1; $counter < 31; $counter++) {
            if (
                $counter == 1 || $counter == 3 || $counter == 5 ||
                $counter == 8 || $counter == 10 || $counter == 12 ||
                $counter == 15 || $counter == 17 || $counter == 20 ||
                $counter == 22 || $counter == 24 || $counter == 26
            ) {
                $this->addNewPlanDay($counter, 1);
            } else {
                $this->AddRestDay($counter, 1);
            }
            if (
                $counter == 1 || $counter == 2 || $counter == 4 || $counter == 5 ||
                $counter == 8 || $counter == 9 || $counter == 11 || $counter == 12 ||
                $counter == 15 || $counter == 16 || $counter == 18 || $counter == 19 ||
                $counter == 22 || $counter == 23 || $counter == 25 || $counter == 26
            ) {
                $this->addNewPlanDay($counter, 2);
            } else {
                $this->AddRestDay($counter, 2);
            }
            if (
                $counter == 1 || $counter == 2 || $counter == 3 || $counter == 5 || $counter == 6 ||
                $counter == 8 || $counter == 9 || $counter == 10 || $counter == 12 || $counter == 13 ||
                $counter == 15 || $counter == 16 || $counter == 17 || $counter == 19 || $counter == 20 ||
                $counter == 22 || $counter == 23 || $counter == 24 || $counter == 26 || $counter == 27
            ) {
                $this->addNewPlanDay($counter, 3);
            } else {
                $this->AddRestDay($counter, 3);
            }
        }
    }
}
