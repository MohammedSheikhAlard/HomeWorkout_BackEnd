<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Models\UserPlanProgress;
use App\Http\Requests\StoreUserPlanProgressRequest;
use App\Http\Requests\UpdateUserPlanProgressRequest;
use App\Traits\apiResponseTrait;
use Illuminate\Http\Request;
use Carbon\Carbon;


class UserPlanProgressController extends Controller
{
    use apiResponseTrait;

    public function AddNewPlanDayProgress(Request $request)
    {
        $request->validate([
            'plan_day_id' => 'required|exists:plan_days',
        ]);

        $user = $request->user();

        if ($user == null) {
            $this->apiResponse(null, "something went wrong", 400);
        }

        $userPlanProgress = UserPlanProgress::create([
            'user_id' => $user->id,
            'plan_day_id' => $request->plan_day_id,
            'is_trained' => true,
            'date' => Carbon::now()
        ]);

        if ($userPlanProgress == null) {
            return $this->apiResponse(null, "Plan Day Saved Falid", 400);
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
