<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\BurnedCalories;
use App\Models\UserPlanProgress;
use App\Traits\apiResponseTrait;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\StoreUserPlanProgressRequest;
use App\Http\Requests\UpdateUserPlanProgressRequest;
use App\Models\PlanDay;

class UserPlanProgressController extends Controller
{
    use apiResponseTrait;

    public function saveUserDailyProgress(Request $request, BurnedCaloriesController $caloriesController)
    {
        $request->validate([
            'plan_day_id' => 'required|exists:plan_days,id',
        ]);

        $user = $request->user();

        if ($user == null) {
            $this->apiResponse(null, "something went wrong", 400);
        }

        $userPlan = $user->userPlans->first();

        $userPlanProgress = UserPlanProgress::create([
            'user_plan_id' => $userPlan->id,
            'plan_day_id' => $request->plan_day_id,
            'is_trained' => true,
            'date' => Carbon::now()
        ]);

        $planDay = PlanDay::find($request->plan_day_id);

        if ($planDay != null) {

            $caloriesController = new BurnedCaloriesController();

            $request = new Request([
                'calories' => $planDay->total_calories
            ]);

            $caloriesController->addExerciseCaloriesToday($request);
        }


        if ($userPlanProgress == null) {
            return $this->apiResponse(null, "Plan Day Saved Falid", 400);
        }

        $nextDay = DB::table('plan_days')
            ->where('plan_id', $userPlan->plan_id) // User's current plan
            ->where('day_number', '>', $userPlan->current_day) // Next in sequence
            ->where('is_rest_day', false) // Only training days
            ->orderBy('day_number', 'asc') // Get next sequential day
            ->first();

        if ($nextDay != null) {
            $userPlan->current_day = $nextDay->day_number;
            $userPlan->save();
        }

        return $this->apiResponse($userPlanProgress, "This is Your Day Progress", 200);
    }

    public function getAllUserPlan(Request $request)
    {
        $request->validate([
            'plan_id' => $request->user_plan_id
        ]);

        $user = $request->user();

        $userPlanProgress = DB::table('user_plan_progress')->where("user_plan_id", "=", $request->user_plan_id)->get();

        return $this->apiResponse($userPlanProgress, "this is Your plan progress", 200);
    }
}
