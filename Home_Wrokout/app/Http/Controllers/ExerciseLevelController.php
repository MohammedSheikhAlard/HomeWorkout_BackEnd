<?php

namespace App\Http\Controllers;

use App\Http\Resources\ExerciseLevelResource;
use App\Models\ExerciseLevel;
use App\Models\Exercise;
use App\Models\Level;
use Illuminate\Http\Request;
use App\Traits\apiResponseTrait;

class ExerciseLevelController extends Controller
{

    use apiResponseTrait;


    public function AddExerciseToLevel(Request $request)
    {

        $fields = $request->validate([
            'level_id' => 'required',
            'exercise_id' => 'required',
            'calories' => 'required',
            'number_of_rips' => 'required',
        ]);

        $admin = $request->user();


        $exercise = Exercise::find($request->exercise_id);
        $level = Level::find($request->level_id);

        if (!$exercise || !$level) {
            return $this->apiResponse(null, 'Exercise or level not found', 404);
        }

        $exercise_level = ExerciseLevel::create([
            'exercise_id' => $exercise->id,
            'level_id' => $level->id,
            'calories' => $request->calories,
            'number_of_rips' => $request->number_of_rips,
            'admin_id' => $admin->id,
        ]);

        $exercise_level->save();

        return $this->apiResponse($exercise_level, 'Exercise added to level successfully', 201);
    }

    public function getAllExerciseLevels()
    {
        $exerciseLevels = ExerciseLevel::with('exercise')->get();

        if (!$exerciseLevels) {
            return $this->apiResponse(null, 'No exercise levels found', 404);
        }

        return $this->apiResponse(ExerciseLevelResource::collection($exerciseLevels), 'All exercise levels', 200);
    }


    public function deleteExerciseLevel(Request $request)
    {
        $exerciseLevel = ExerciseLevel::find($request->id);

        if (!$exerciseLevel) {
            return $this->apiResponse(null, 'Exercise level not found', 404);
        }

        $exerciseLevel->delete();

        return $this->apiResponse(null, 'Exercise level deleted successfully', 200);
    }

    public function updateExerciseLevel(Request $request)
    {
        $exerciseLevel = ExerciseLevel::find($request->id);

        if (!$exerciseLevel) {
            return $this->apiResponse(null, 'Exercise level not found', 404);
        }

        $exerciseLevel->update($request->all());

        return $this->apiResponse($exerciseLevel, 'Exercise level updated successfully', 200);
    }

    public function getAllExerciseLevelsByLevelId(Request $request)
    {
        $exerciseLevels = ExerciseLevel::with('exercise')->get();

        $exerciseLevels = $exerciseLevels->where('level_id', "=", $request->level_id);

        if ($exerciseLevels->isEmpty()) {
            return $this->apiResponse(null, 'No exercise levels found', 404);
        }

        return $this->apiResponse(ExerciseLevelResource::collection($exerciseLevels), 'All exercise levels by level id', 200);
    }

    public function getExerciseLevelsByExerciesLevelId(Request $request)
    {

        $exerciseLevel = ExerciseLevel::with('exercise')->get();

        $exerciseLevel = $exerciseLevel->where('id', "=", $request->id);

        if ($exerciseLevel->isEmpty()) {
            return $this->apiResponse(null, 'No exercise level found', 404);
        }

        return $this->apiResponse(ExerciseLevelResource::collection($exerciseLevel), 'exercise levels by Exercise level id', 200);
    }

    public function getExerciseLevelsByExerciesLevelIdandCategoryId(Request $request)
    {

        $exerciseLevel = ExerciseLevel::with('exercise')->get();

        $exerciseLevel = $exerciseLevel->where('level_id', '=', $request->level_id)
            ->where('exercise.category_id', '=', $request->category_id);

        if ($exerciseLevel->isEmpty()) {
            return $this->apiResponse(null, 'No exercise level with this categorie found', 404);
        }

        return $this->apiResponse(ExerciseLevelResource::collection($exerciseLevel), 'exercise levels by Exercise level id and categorie id', 200);
    }

    public function getDailyChallenge(Request $request)
    {
        $user = $request->user();

        if ($user == null) {
            return $this->apiResponse(null, "something went wrong", 404);
        }

        $exerciseLevel = ExerciseLevel::with('exercise')->where('level_id', '=', $user->level_id)->inRandomOrder()->limit(4)->get();

        if ($exerciseLevel->isEmpty()) {
            return $this->apiResponse(null, 'No exercise level with this Level ID found', 404);
        }

        return $this->apiResponse(ExerciseLevelResource::collection($exerciseLevel), 'this is your daily challenge', 200);
    }

    /////////////////////////////////

    // WEB page: display exercise levels management
    public function webExerciseLevelsPage(Request $request)
    {
        if (!$request->session()->get('admin_logged_in')) {
            return redirect()->route('admin.login');
        }

        $exerciseLevels = ExerciseLevel::with(['exercise', 'level'])->orderBy('id')->get();
        $exercises = Exercise::orderBy('name')->get();
        $levels = Level::orderBy('id')->get();

        return view('admin.exercise_levels', compact('exerciseLevels', 'exercises', 'levels'));
    }

    // WEB method: store new exercise level
    public function webExerciseLevelStore(Request $request)
    {
        if (!$request->session()->get('admin_logged_in')) {
            return redirect()->route('admin.login');
        }

        $request->validate([
            'exercise_id' => 'required|exists:exercises,id',
            'level_id' => 'required|exists:levels,id',
            'calories' => 'required|integer|min:1',
            'number_of_rips' => 'nullable|integer|min:1',
            'timer' => 'nullable|integer|min:1',
        ]);

        ExerciseLevel::create([
            'exercise_id' => $request->exercise_id,
            'level_id' => $request->level_id,
            'calories' => $request->calories,
            'number_of_rips' => $request->number_of_rips,
            'timer' => $request->timer ?? 30,
        ]);

        return redirect()->route('admin.exercise-levels');
    }

    // WEB method: update exercise level
    public function webExerciseLevelUpdate(Request $request, ExerciseLevel $exerciseLevel)
    {
        if (!$request->session()->get('admin_logged_in')) {
            return redirect()->route('admin.login');
        }

        $request->validate([
            'exercise_id' => 'required|exists:exercises,id',
            'level_id' => 'required|exists:levels,id',
            'calories' => 'required|integer|min:1',
            'number_of_rips' => 'nullable|integer|min:1',
            'timer' => 'nullable|integer|min:1',
        ]);

        $exerciseLevel->update([
            'exercise_id' => $request->exercise_id,
            'level_id' => $request->level_id,
            'calories' => $request->calories,
            'number_of_rips' => $request->number_of_rips,
            'timer' => $request->timer ?? 30,
        ]);

        return redirect()->route('admin.exercise-levels');
    }

    // WEB method: delete exercise level
    public function webExerciseLevelDelete(Request $request, ExerciseLevel $exerciseLevel)
    {
        if (!$request->session()->get('admin_logged_in')) {
            return redirect()->route('admin.login');
        }

        $exerciseLevel->delete();
        return redirect()->route('admin.exercise-levels');
    }

    // WEB method: display trashed exercise levels
    public function webExerciseLevelsTrashedPage(Request $request)
    {
        if (!$request->session()->get('admin_logged_in')) {
            return redirect()->route('admin.login');
        }

        $exerciseLevels = ExerciseLevel::onlyTrashed()->with(['exercise', 'level'])->orderBy('deleted_at', 'desc')->get();
        $exercises = Exercise::orderBy('name')->get();
        $levels = Level::orderBy('id')->get();

        return view('admin.exercise_levels-trashed', compact('exerciseLevels', 'exercises', 'levels'));
    }

    // WEB method: restore exercise level
    public function webExerciseLevelRestore(Request $request, $id)
    {
        if (!$request->session()->get('admin_logged_in')) {
            return redirect()->route('admin.login');
        }

        $exerciseLevel = ExerciseLevel::onlyTrashed()->findOrFail($id);
        $exerciseLevel->restore();
        return redirect()->route('admin.exercise-levels.trashed')->with('success', 'Exercise level restored successfully!');
    }

    // WEB method: force delete exercise level
    public function webExerciseLevelForceDelete(Request $request, $id)
    {
        if (!$request->session()->get('admin_logged_in')) {
            return redirect()->route('admin.login');
        }

        $exerciseLevel = ExerciseLevel::onlyTrashed()->findOrFail($id);

        // فحص إذا كان هناك مستخدمين مرتبطين بهذا المستوى
        // يمكن إضافة فحص إضافي هنا إذا كان هناك علاقات أخرى

        $exerciseLevel->forceDelete();
        return redirect()->route('admin.exercise-levels.trashed')->with('success', 'Exercise level permanently deleted successfully!');
    }
}
