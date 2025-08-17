<?php

namespace App\Http\Controllers;

use App\Models\UserPlan;
use App\Http\Requests\StoreUserPlanRequest;
use App\Http\Requests\UpdateUserPlanRequest;
use App\Http\Resources\PlanResource;
use App\Models\Plan;
use Illuminate\Http\Request;
use App\Models\User;
use App\Traits\apiResponseTrait;
use Illuminate\Support\Facades\Auth;


class UserPlanController extends Controller
{
    use apiResponseTrait;

    public function LinkPlanToUser(Request $request)
    {
        $user = $request->user();

        $request->validate([
            'plan_id' => 'required|exists:plans,id',
        ]);

        $alreadyExists = UserPlan::where('user_id', $user->id)->where('plan_id', $request->plan_id)->exists();

        if ($alreadyExists) {
            return $this->apiResponse(null, "This plan is already attached to the user", 404);
        }

        $startDate = now();
        $endDate = now()->addDays(30);
        $userPlan = UserPlan::create([
            'user_id' => $user->id,
            'plan_id' => $request->plan_id,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'status' => 'active',
        ]);
        return $this->apiResponse($userPlan, "Linked successfully", 200);
    }



    public function switchToNextPlan(Request $request)
    {
        $user = $request->user();

        if ($user == null) {
            return $this->apiResponse(null, "there is something went Wrong", 400);
        }

        $currentPlan = UserPlan::where('user_id', $user->id)->where('status', 'active')->where('end_date', '<', now())->first();

        if ($currentPlan) {
            $currentPlan->update(['status' => 'completed']);

            if ($currentPlan->plan_id == 3) {
                return $this->apiResponse(null, "you have finished Our Plans", 200);
            }

            $newPlan = UserPlan::create([
                'user_id' => $user->id,
                'plan_id' => $request->plan_id,
                'start_date' => now(),
                'end_date' => now()->addDays(30),
                'status' => 'active',
            ]);

            return $this->apiResponse($newPlan, "Successfully trnasition to a new plan", 200);
        }
        return $this->apiResponse(null, "You have an unfinished plan", 400);
    }



    public function deleteCurrentUserPlan(Request $request)
    {
        $user = $request->user();

        $userPlan = UserPlan::where('user_id', $user->id)->where('status', 'active')->first();

        if (!$userPlan) {

            return $this->apiResponse(null, "No active plan found", 404);
        }

        $userPlan->delete();
        return $this->apiResponse(null, "Active plan deleted successfully", 200);
    }

    public function getUserCurrentPlan(Request $request)
    {
        $user = $request->user();

        if ($user == null) {
            return $this->apiResponse(null, "Something went wrong", 400);
        }

        $userPlan = UserPlan::with('plan')
            ->where('user_id', $user->id)
            ->where('status', 'active')
            ->first();

        if (!$userPlan) {
            return $this->apiResponse(null, "No active plan found", 404);
        }
        $plan = $userPlan->plan;

        return $this->apiResponse([
            "name" =>  $plan->name,
            "description" => $plan->description,
            "number_of_day_to_train" => $plan->number_of_day_to_train,
            "current_day" => $userPlan->current_day
        ], "this is your plan", 200);
    }
}
