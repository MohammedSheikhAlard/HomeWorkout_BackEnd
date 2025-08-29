<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\PlanDay;
use App\Models\UserPlan;
use Illuminate\Http\Request;
use App\Models\ExerciseLevel;
use App\Models\PlanDayExercise;
use App\Traits\apiResponseTrait;
use PhpParser\Node\Stmt\Return_;
use Illuminate\Support\Facades\DB;
use Illuminate\Auth\Events\Validated;
use App\Http\Requests\StorePlanDayExerciseRequest;
use App\Http\Requests\UpdatePlanDayExerciseRequest;
use Carbon\Carbon;

class PlanDayExerciseController extends Controller
{

    use apiResponseTrait;

    public function addNewPlanDayExercise(Request $request)
    {
        $request->validate([
            'plan_day_id' => 'required',
            'exercies_level_id' => 'required|unique:plan_day_exercises',
            'exercies_order' => 'required|unique:plan_day_exercises'
        ]);

        $planDay = PlanDay::find($request->plan_day_id);

        if ($planDay->is_rest_day) {
            return $this->apiResponse(null, "You can't add a exercieses for a rest day", 200);
        }

        $planDayExercise = PlanDayExercise::create([
            'plan_day_id' => $request->plan_day_id,
            'exercies_level_id' => $request->exercies_level_id,
            'exercies_order' => $request->exercies_order
        ]);

        if ($planDayExercise == null) {
            return $this->apiResponse(null, "Your plan Day Exercise added falied", 500);
        }

        $exercies = ExerciseLevel::find($request->exercies_level_id);
        $planDay = PlanDay::find($request->plan_day_id);

        $planDay->total_calories += $exercies->calories;

        $planDay->save();

        return $this->apiResponse($planDayExercise, "New Exercise Added For That Day Plan", 200);
    }

    public function updatePlanDayExercise(Request $request)
    {
        $planDayExercise = PlanDayExercise::find($request->id);

        if ($planDayExercise->exercies_level_id != $request->exercies_level_id) {
            $request->validate([
                'id' => 'required',
                'exercies_level_id' => 'required',
                'exercies_order' => 'required|unique:plan_day_exercises'
            ]);
        }

        if ($planDayExercise == null) {
            return $this->apiResponse(null, "Plan Day Exercise not Found", 404);
        }


        if ($request->exercies_level_id != $planDayExercise->exercies_level_id) {
            $planDay = PlanDay::find($planDayExercise->plan_day_id);

            $exercise = ExerciseLevel::find($request->exercies_level_id);
            $preExercise = ExerciseLevel::find($planDayExercise->exercies_level_id);

            $planDay->total_calories -= $preExercise->calories;
            $planDay->total_calories += $exercise->calories;

            $planDay->save();
        }



        $planDayExercise->update($request->all());

        return $this->apiResponse($planDayExercise, "Plan Day Exercise Updated Successfully", 200);
    }

    public function getPlanDayExercise(Request $request)
    {
        $planDayExercise = PlanDayExercise::find($request->id);

        if ($planDayExercise == null) {
            return $this->apiResponse(null, "Plan Day Exercise not found", 404);
        }

        return $this->apiResponse($planDayExercise, "This is Your Plan Day Exercise", 200);
    }

    public function getAllPlanDayExercises(Request $request)
    {
        $planDayExercises = DB::table('plan_day_exercises')
            ->join('plan_days', 'plan_days.id', '=', 'plan_day_exercises.plan_day_id', 'inner')

            ->join('exercise_levels', 'exercise_levels.id', '=', 'plan_day_exercises.exercies_level_id', 'inner')

            ->join('exercises', 'exercises.id', '=', 'exercise_levels.exercise_id', 'inner')

            ->select('plan_day_exercises.id', 'exercises.name', 'exercises.description', 'exercises.image_path', 'exercise_levels.calories', 'exercise_levels.number_of_rips', 'plan_day_exercises.exercies_order')

            ->where('plan_day_exercises.plan_day_id', '=', $request->plan_day_id)->get();

        if ($planDayExercises == null) {
            return  $this->apiResponse(null, "something went wrong", 400);
        }

        return $this->apiResponse($planDayExercises, "this is your Exercises for today", 200);
    }

    public function getAllUserPlanDayExercises(Request $request)
    {
        $request->validate([
            'plan_day_id' => 'required|integer|exists:plan_days,id'
        ]);

        $user = $request->user();

        if ($user == null) {
            $this->apiResponse(null, "something went wrong", 400);
        }

        $planDay = PlanDay::find($request->plan_day_id);

        if ($planDay->is_rest_day) {
            return $this->apiResponse(null, "today is a rest day", 200);
        }

        $userPlan = UserPlan::with('plan')
            ->where('user_id', $user->id)
            ->where('status', 'active')
            ->first();

        if ($userPlan == null) {
            return $this->apiResponse(null, "there is no active plan", 400);
        }

        $today = Carbon::now();
        $daytoPlay = Carbon::parse($userPlan->start_date)->addDays($planDay->day_number - 1);

        if ($today->lessThan($daytoPlay)) {
            return $this->apiResponse(null, "it's not your day to play", 200);
        }

        $planDayExercises = DB::table('plan_day_exercises')
            ->join('plan_days', 'plan_days.id', '=', 'plan_day_exercises.plan_day_id', 'inner')

            ->join('exercise_levels', 'exercise_levels.id', '=', 'plan_day_exercises.exercies_level_id', 'inner')

            ->join('exercises', 'exercises.id', '=', 'exercise_levels.exercise_id', 'inner')

            ->select('plan_day_exercises.id', 'exercises.name', 'exercises.description', 'exercises.image_path', 'exercise_levels.calories', 'exercise_levels.number_of_rips', 'plan_day_exercises.exercies_order')

            ->where('plan_day_exercises.plan_day_id', '=', $request->plan_day_id)->get();

        if ($planDayExercises == null) {
            return  $this->apiResponse(null, "something went wrong", 400);
        }

        return $this->apiResponse($planDayExercises, "this is your Exercises for today", 200);
    }

    ////////////////////////////////////////////////

    public function webDayExercisesPage(Request $request, PlanDay $planDay)
    {
        if (!$request->session()->get('admin_logged_in')) {
            return redirect()->route('admin.login');
        }

        $exercises = DB::table('plan_day_exercises')
            ->join('exercise_levels', 'exercise_levels.id', '=', 'plan_day_exercises.exercies_level_id', 'inner')
            ->join('exercises', 'exercises.id', '=', 'exercise_levels.exercise_id', 'inner')
            ->select(
                'plan_day_exercises.id',
                'plan_day_exercises.exercies_level_id',
                'exercises.name',
                'exercises.description',
                'exercises.image_path',
                'exercise_levels.calories',
                'exercise_levels.number_of_rips',
                'plan_day_exercises.exercies_order'
            )
            ->where('plan_day_exercises.plan_day_id', '=', $planDay->id)
            ->orderBy('plan_day_exercises.exercies_order')
            ->get();

        $availableExerciseLevels = DB::table('exercise_levels')
            ->join('exercises', 'exercises.id', '=', 'exercise_levels.exercise_id')
            ->select('exercise_levels.id', 'exercises.name', 'exercise_levels.calories')
            ->where('exercise_levels.level_id', '=', $planDay->plan->level_id)
            ->orderBy('exercises.name')
            ->get();

        return view('admin.plan_day_exercises', [
            'planDay' => $planDay,
            'exercises' => $exercises,
            'availableExerciseLevels' => $availableExerciseLevels,
        ]);
    }

    public function webAddExerciseToDay(Request $request, PlanDay $planDay)
    {
        if (!$request->session()->get('admin_logged_in')) {
            return redirect()->route('admin.login');
        }

        if ($planDay->is_rest_day) {
            return redirect()->route('admin.plans.day.exercises', $planDay)->with('error', "You can't add exercises for a rest day");
        }

        $request->validate([
            'exercies_level_id' => 'required|exists:exercise_levels,id',
            'exercies_order' => 'required|integer|min:1',
        ]);

        $existing = PlanDayExercise::where('plan_day_id', $planDay->id)
            ->where('exercies_level_id', $request->exercies_level_id)
            ->first();

        if ($existing) {
            return redirect()->route('admin.plans.day.exercises', $planDay)->with('error', 'This exercise is already added to this day');
        }

        PlanDayExercise::create([
            'plan_day_id' => $planDay->id,
            'exercies_level_id' => $request->exercies_level_id,
            'exercies_order' => $request->exercies_order,
        ]);

        return redirect()->route('admin.plans.day.exercises', $planDay);
    }

    public function webUpdateExerciseInDay(Request $request, PlanDayExercise $planDayExercise)
    {
        if (!$request->session()->get('admin_logged_in')) {
            return redirect()->route('admin.login');
        }

        $request->validate([
            'exercies_level_id' => 'required|exists:exercise_levels,id',
            'exercies_order' => 'required|integer|min:1',
        ]);

        $planDay = PlanDay::find($planDayExercise->plan_day_id);

        $planDayExercise->update([
            'exercies_level_id' => $request->exercies_level_id,
            'exercies_order' => $request->exercies_order,
        ]);

        return redirect()->route('admin.plans.day.exercises', $planDay);
    }

    public function webDeleteExerciseFromDay(Request $request, PlanDayExercise $planDayExercise)
    {
        if (!$request->session()->get('admin_logged_in')) {
            return redirect()->route('admin.login');
        }

        $planDay = PlanDay::find($planDayExercise->plan_day_id);

        $planDayExercise->delete();

        return redirect()->route('admin.plans.day.exercises', $planDay);
    }
}
