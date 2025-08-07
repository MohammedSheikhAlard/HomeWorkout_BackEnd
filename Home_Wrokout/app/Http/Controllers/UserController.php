<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BurnedCalories;
use App\Traits\apiResponseTrait;
use App\Http\Resources\UserResource;

class UserController extends Controller
{
    use apiResponseTrait;


    public function editUserReminder(Request $request)
    {

        $request->validate([
            'reminder' => 'required'
        ]);

        $user = $request->user();

        if ($user == null) {
            return $this->apiResponse(null, "something went wrong", 200);
        }

        $user->reminder = $request->reminder;

        $user->save();

        return $this->apiResponse(new UserResource($user), "Your reminder updated successfully", 200);
    }

    public function getUserReminder(Request $request)
    {
        $user = $request->user();

        if ($user == null) {
            return $this->apiResponse(null, "something went wrong", 200);
        }

        return $this->apiResponse(new UserResource($user), "this is your reminder", 200);
    }

    public function editUserName(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:users,name'
        ]);

        $user = $request->user();

        if ($user == null) {
            return $this->apiResponse(null, "something went wrong", 200);
        }

        $user->name = $request->name;

        $user->save();

        return $this->apiResponse(new UserResource($user), "this is your new User Name", 200);
    }

    public function editPassword(Request $request)
    {
        $request->validate([
            'password' => 'required|min:6'
        ]);

        $user = $request->user();

        if ($user == null) {
            return $this->apiResponse(null, "something went wrong", 400);
        }

        $user->password = $request->password;

        $user->save();

        return $this->apiResponse(new UserResource($user), "this is your new password", 200);
    }

    public function getActivityData(Request $request)
    {
        $user = $request->user();

        if ($user == null) {
            return $this->apiResponse(null, "something went wrong", 400);
        }


        $today = now()->format('Y-m-d');


        $dailyBurnedCalories = BurnedCalories::firstOrNew([
            'user_id' => $user->id,
            'day_date' => $today
        ]);

        return $this->apiResponse([
            'total_calories_burned_in_day' => $dailyBurnedCalories->total_calories_burned_in_day,
            'target_calories' => $user->target_calories,
            'BMI' => $user->BMI
        ], "this is Your activety data", 200);
    }

    public function editCaloriesGoal(Request $request)
    {
        $request->validate([
            'target_calories' => 'required|integer'
        ]);

        $user = $request->user();

        if ($user == null) {
            return $this->apiResponse(null, "something went wrong", 400);
        }

        $user->target_calories = $request->target_calories;

        $user->save();

        return $this->apiResponse(new UserResource($user), "This is Your New Target", 200);
    }

    public function editBMI(Request $request)
    {
        $request->validate([
            'tall' => 'required|integer',
            'weight' => 'required|integer'
        ]);

        $user = $request->user();

        if ($user == null) {
            return $this->apiResponse(null, "something went wrong", 400);
        }

        $user->tall = $request->tall;
        $user->weight = $request->weight;

        $tall = $user->tall / 100;

        $weight = $user->weight;

        $user->BMI = round($weight / ($tall * $tall), 1);

        $user->save();

        return $this->apiResponse(new UserResource($user), "This is Your New Target", 200);
    }

    public function updateLevel(Request $request)
    {
        $request->validate([
            'level_id' => 'required|exists:levels,id'
        ]);

        $user = $request->user();

        if ($user == null) {
            return $this->apiResponse(null, "there is something went wrong", 400);
        }

        $user->level_id = $request->level_id;

        $user->save();

        return $this->apiResponse(new UserResource($user), "this is Your updated level id", 200);
    }

    public function updateTargetCalories(Request $request)
    {
        $request->validate([
            'target_calories' => 'required|integer'
        ]);

        $user = $request->user();

        if ($user == null) {
            return $this->apiResponse(null, "something went wrong", 400);
        }

        $user->target_calories = $request->target_calories;

        return $this->apiResponse(new UserResource($user), "this is Your New Target Caloires", 200);
    }
}
