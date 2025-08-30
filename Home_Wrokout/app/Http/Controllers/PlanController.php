<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use App\Http\Requests\StorePlanRequest;
use App\Http\Requests\UpdatePlanRequest;
use App\Http\Resources\PlanResource;
use App\Models\Admin;
use App\Traits\apiResponseTrait;
use Illuminate\Http\Request;
use App\Models\Level;

class PlanController extends Controller
{

    use apiResponseTrait;

    public function addNewPlan(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'description' => 'required',
            'price' => 'nullable|numeric|min:0',
            'number_of_day_to_train' => 'required',
            'level_id' => 'required'
        ]);

        $admin = $request->user();

        $plan  = Plan::create([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
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
        $request->validate([
            'id' => 'required|exists:plans,id',
            'name' => 'required',
            'description' => 'required',
            'price' => 'nullable|numeric|min:0',
            'number_of_day_to_train' => 'required',
            'level_id' => 'required'
        ]);

        $plan = Plan::find($request->id);

        if ($plan == null) {
            return $this->apiResponse(null, "Your Plan Was not found", 404);
        }

        $plan->update([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'number_of_day_to_train' => $request->number_of_day_to_train,
            'level_id' => $request->level_id,
        ]);

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


    public function getPaidPlans(Request $request)
    {
        $user = $request->user();

        if ($user == null) {
            return $this->apiResponse(null, "Something went wrong", 400);
        }

        $plans = Plan::where('price', '>', 0)->get();

        if ($plans->isEmpty()) {
            return $this->apiResponse(null, "there is no paid plans in the system", 200);
        }

        return $this->apiResponse(new PlanResource($plans), "this is the paid plans", 200);
    }

    /////////////////////////////////////////

    public function webPlansPage(Request $request)
    {
        if (!$request->session()->get('admin_logged_in')) {
            return redirect()->route('admin.login');
        }
        $plans = Plan::with('level')->orderBy('level_id')->orderBy('id')->get();
        $levels = Level::orderBy('id')->get();
        return view('admin.plans', compact('plans', 'levels'));
    }

    public function webPlanStore(Request $request)
    {
        if (!$request->session()->get('admin_logged_in')) {
            return redirect()->route('admin.login');
        }
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'nullable|numeric|min:0',
            'number_of_day_to_train' => 'required|integer|min:1',
            'level_id' => 'required|exists:levels,id',
        ]);
        Plan::create([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'number_of_day_to_train' => $request->number_of_day_to_train,
            'admin_id' => $request->session()->get('admin_id'),
            'level_id' => $request->level_id,
        ]);
        return redirect()->route('admin.plans');
    }

    public function webPlanUpdate(Request $request, Plan $plan)
    {
        if (!$request->session()->get('admin_logged_in')) {
            return redirect()->route('admin.login');
        }
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'nullable|numeric|min:0',
            'number_of_day_to_train' => 'required|integer|min:1',
            'level_id' => 'required|exists:levels,id',
        ]);

        $plan->update([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'number_of_day_to_train' => $request->number_of_day_to_train,
            'level_id' => $request->level_id,
        ]);

        return redirect()->route('admin.plans');
    }

    public function webPlanDelete(Request $request, Plan $plan)
    {
        if (!$request->session()->get('admin_logged_in')) {
            return redirect()->route('admin.login');
        }
        // Soft delete (thanks to SoftDeletes trait on Plan model)
        $plan->delete();
        return redirect()->route('admin.plans');
    }

    public function webPlansTrashedPage(Request $request)
    {
        if (!$request->session()->get('admin_logged_in')) {
            return redirect()->route('admin.login');
        }
        $plans = Plan::onlyTrashed()->with('level')->orderBy('deleted_at', 'desc')->get();
        $levels = Level::orderBy('id')->get();
        return view('admin.plans-trashed', compact('plans', 'levels'));
    }

    public function webPlanRestore(Request $request, $id)
    {
        if (!$request->session()->get('admin_logged_in')) {
            return redirect()->route('admin.login');
        }
        $plan = Plan::onlyTrashed()->findOrFail($id);
        $plan->restore();
        return redirect()->route('admin.plans.trashed')->with('success', 'Plan restored successfully!');
    }

    public function webPlanForceDelete(Request $request, $id)
    {
        if (!$request->session()->get('admin_logged_in')) {
            return redirect()->route('admin.login');
        }

        $plan = Plan::onlyTrashed()->findOrFail($id);

        // فحص إذا كان هناك أيام خطط مرتبطة بالخطة
        if ($plan->planDays()->count() > 0) {
            return redirect()->route('admin.plans.trashed')
                ->with('error', 'Cannot permanently delete this plan because there are plan days linked to it. Please delete the linked plan days first.')
                ->with('show_cancel_button', true);
        }

        // فحص إذا كان هناك مستخدمين مرتبطين بالخطة
        if ($plan->userPlans()->count() > 0) {
            return redirect()->route('admin.plans.trashed')
                ->with('error', 'Cannot permanently delete this plan because there are users linked to it. Please delete the user associations first.')
                ->with('show_cancel_button', true);
        }

        $plan->forceDelete();
        return redirect()->route('admin.plans.trashed')->with('success', 'Plan permanently deleted successfully!');
    }
}
