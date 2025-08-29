<?php

namespace App\Http\Controllers;

use App\Models\PlanDay;
use Illuminate\Http\Request;
use App\Traits\apiResponseTrait;
use App\Http\Resources\PlanDayResource;
use App\Http\Requests\StorePlanDayRequest;
use App\Http\Requests\UpdatePlanDayRequest;
use App\Models\UserPlan;
use App\Models\Plan;

class PlanDayController extends Controller
{

    use apiResponseTrait;

    public function addNewPlanDay(Request $request)
    {
        $request->validate([
            'day_number' => 'required|integer',
            'total_calories' => 'required|integer',
            'is_rest_day' => 'required|boolean',
            'plan_id' => 'required|exists:plans,id',
        ]);

        $planDay = PlanDay::create([
            'day_number' => $request->day_number,
            'total_calories' => $request->total_calories,
            'is_rest_day' => $request->is_rest_day,
            'plan_id' => $request->plan_id,
        ]);

        if ($planDay == null) {
            return $this->apiResponse(null, 'Failed to create plan day', 500);
        }

        return $this->apiResponse($planDay, 'Plan day created successfully', 201);
    }

    public function updatePlanDay(Request $request)
    {
        $planDay = PlanDay::find($request->id);

        if ($planDay == null) {
            return $this->apiResponse(null, "Plan day not found", 404);
        }

        $planDay->update($request->all());

        return $this->apiResponse($planDay, "Plan day updated successfully", 200);
    }

    public function getPlanDay(Request $request)
    {
        $planDay = PlanDay::find($request->id);

        if ($planDay == null) {
            return $this->apiResponse(null, "Plan day not found", 404);
        }

        return $this->apiResponse($planDay, "This is your plan for today", 200);
    }

    public function getAllPlanDays(Request $request)
    {
        $planDays = PlanDay::where('plan_id', $request->plan_id)->get();

        if ($planDays == null) {
            return $this->apiResponse(null, "No plan days found", 404);
        }

        return $this->apiResponse(PlanDayResource::collection($planDays), "All plan days retrieved successfully", 200);
    }

    public function getAllUserPlanDays(Request $request)
    {
        $user = $request->user();

        if ($user == null) {
            $this->apiResponse(null, "There is something went wrong", 200);
        }

        $userPlan = UserPlan::where('user_id', '=', $user->id)
            ->where('status', '=', 'active')->first();

        if ($userPlan == null) {
            return $this->apiResponse(null, "there is no active Plan", 200);
        }

        $planDays = PlanDay::where('plan_id', $userPlan->plan_id)->get();


        if ($planDays == null) {
            return $this->apiResponse(null, "No plan days found", 404);
        }

        return $this->apiResponse(PlanDayResource::collection($planDays), "All plan days retrieved successfully", 200);
    }

    /////////////////////////////////////

    public function webPlanDaysPage(Request $request, Plan $plan)
    {
        if (!$request->session()->get('admin_logged_in')) {
            return redirect()->route('admin.login');
        }
        $days = PlanDay::where('plan_id', $plan->id)->orderBy('day_number')->get();
        return view('admin.plan_days', [
            'plan' => $plan,
            'days' => $days,
        ]);
    }

    public function webPlanDayStore(Request $request, Plan $plan)
    {
        if (!$request->session()->get('admin_logged_in')) {
            return redirect()->route('admin.login');
        }

        $request->validate([
            'day_number' => 'required|integer',
            'is_rest_day' => 'required|boolean',
        ]);

        PlanDay::create([
            'day_number' => $request->day_number,
            'total_calories' => 0,
            'is_rest_day' => $request->is_rest_day,
            'plan_id' => $plan->id,
        ]);

        return redirect()->route('admin.plans.days', $plan);
    }

    public function webPlanDayUpdate(Request $request, PlanDay $planDay)
    {
        if (!$request->session()->get('admin_logged_in')) {
            return redirect()->route('admin.login');
        }

        $request->validate([
            'day_number' => 'required|integer',
            'is_rest_day' => 'required|boolean',
        ]);

        $planDay->update([
            'day_number' => $request->day_number,
            'is_rest_day' => $request->is_rest_day,
        ]);

        return redirect()->route('admin.plans.days', $planDay->plan);
    }

    public function webPlanDayDelete(Request $request, PlanDay $planDay)
    {
        if (!$request->session()->get('admin_logged_in')) {
            return redirect()->route('admin.login');
        }

        $plan = $planDay->plan;
        $planDay->delete();

        return redirect()->route('admin.plans.days', $plan);
    }

    public function webToggleRestDay(Request $request, PlanDay $planDay)
    {
        if (!$request->session()->get('admin_logged_in')) {
            return redirect()->route('admin.login');
        }

        $planDay->is_rest_day = !$planDay->is_rest_day;
        $planDay->save();

        return redirect()->route('admin.plans.days', $planDay->plan);
    }
}
