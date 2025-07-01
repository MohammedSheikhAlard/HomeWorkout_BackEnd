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
}
