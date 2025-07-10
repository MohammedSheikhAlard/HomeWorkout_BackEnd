<?php

namespace App\Http\Controllers;

use App\Models\PlanDay;
use Illuminate\Http\Request;
use App\Traits\apiResponseTrait;
use App\Http\Resources\PlanDayResource;
use App\Http\Requests\StorePlanDayRequest;
use App\Http\Requests\UpdatePlanDayRequest;

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
}
