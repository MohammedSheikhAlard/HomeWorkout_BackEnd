<?php

namespace App\Http\Controllers;

use App\Models\BurnedCalories;
use App\Http\Requests\StoreBurnedCaloriesRequest;
use App\Http\Requests\UpdateBurnedCaloriesRequest;
use App\Http\Resources\BurnedCaloriesResource;
use App\Traits\apiResponseTrait;
use Illuminate\Http\Request;

class BurnedCaloriesController extends Controller
{

    use  apiResponseTrait;

    public function addExerciseCaloriesToday(Request $request)
    {
        $request->validate([
            'calories' => 'required|integer',
        ]);

        $user = $request->user();

        if ($user == null) {
            return $this->apiResponse(null, "there is something went wrong", 400);
        }

        $today = now()->format('Y-m-d');


        $dailyBurnedCalories = BurnedCalories::firstOrNew([
            'user_id' => $user->id,
            'day_date' => $today
        ]);


        $dailyBurnedCalories->total_calories_burned_in_day =
            ($dailyBurnedCalories->exists)
            ? $dailyBurnedCalories->total_calories_burned_in_day + $request->calories
            : $request->calories;

        $dailyBurnedCalories->save();


        return $this->apiResponse(new BurnedCaloriesResource($dailyBurnedCalories), "this is your daily calories burned_calories", 200);
    }
}
