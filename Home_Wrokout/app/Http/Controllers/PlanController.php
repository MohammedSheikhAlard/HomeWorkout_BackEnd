<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use App\Http\Requests\StorePlanRequest;
use App\Http\Requests\UpdatePlanRequest;
use App\Http\Resources\PlanResource;
use App\Models\Admin;
use App\Traits\apiResponseTrait;
use Illuminate\Http\Request;

class PlanController extends Controller
{

    use apiResponseTrait;

    public function addNewPlan(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'description' => 'required',
            'number_of_day_to_train' => 'required',
            'level_id' => 'required'
        ]);

        $admin = $request->user();

        $plan  = Plan::create([
            'name' => $request->name,
            'description' => $request->description,
            'number_of_day_to_train' => $request->number_of_day_to_train,
            'admin_id' => $admin->id,
            'level_id' => $request->level_id,
        ]);

        if ($plan == null) {
            return $this->apiResponse($plan, "Something Went Wrong When adding New plan", 404);
        }

        return $this->apiResponse($plan, "Your New Plan Added Successfully", 200);
    }

    public function updatePlan(Request $request)
    {
        $plan = Plan::find($request->id);

        if ($plan == null) {
            return $this->apiResponse(null, "Your Plan Was not found", 404);
        }

        $plan->update($request->all());

        return $this->apiResponse($plan, "plan Updated Successfully", 200);
    }

    public function getPlan(Request $request)
    {
        $plan = Plan::find($request->id);

        if ($plan == null) {
            return $this->apiResponse(null, "Your Plan Was not found", 404);
        }

        return $this->apiResponse($plan, "This is Your Plan", 200);
    }

    public function deletePlan(Request $request)
    {
        $plan = Plan::find($request->id);

        if ($plan == null) {
            return $this->apiResponse(null, "Your Plan Was not found", 404);
        }

        $plan->delete();

        return $this->apiResponse(null, "Your Plan Deleted Successfully", 200);
    }

    public function restorePlan(Request $request)
    {
        $plan = Plan::withTrashed()->find($request->id);

        if ($plan == null) {
            return $this->apiResponse(null, "Your Plan Was not found", 404);
        }

        $plan->restore();

        return $this->apiResponse($plan, "Your Plan Restored Successfully", 200);
    }

    public function getAllPlans()
    {
        $plans = Plan::all();

        if ($plans->isEmpty()) {
            return $this->apiResponse(null, "No Plans Found", 404);
        }

        return $this->apiResponse(PlanResource::collection($plans), "This is all plans", 200);
    }

    public function getPlansByUserLevelID(Request $request)
    {
        $user = $request->user();

        if ($user == null) {
            return $this->apiResponse(null, "Something went wrong", 400);
        }

        $plans = Plan::where('level_id', '=', $user->level_id)->get();

        if ($plans == null) {
            return $this->apiResponse(null, "there is no plans for this level id", 404);
        }

        return $this->apiResponse(PlanResource::collection($plans), "thoes are plans for your level", 200);
    }
}
