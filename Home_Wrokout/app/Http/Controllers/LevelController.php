<?php

namespace App\Http\Controllers;

use App\Models\Level;
use Illuminate\Http\Request;
use App\Traits\apiResponseTrait;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\LevelResource;
use App\Http\Requests\StoreLevelRequest;
use App\Http\Requests\UpdateLevelRequest;
use Illuminate\Database\Eloquent\Collection;

class LevelController extends Controller
{

    use apiResponseTrait;

    public function getAllLevels(Request $request)
    {

        $levels = Level::all();

        if ($levels == null) {
            return $this->apiResponse(null, "there is no levels in your apps", 400);
        }

        return $this->apiResponse(LevelResource::collection($levels), "this is the levels we have", 200);
    }

    public function getLevelsByCategoryID(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
        ]);

        $levels = DB::table('levels')
            ->join('exercise_levels', 'exercise_levels.level_id', '=', 'levels.id', 'inner')

            ->join('exercises', 'exercises.id', '=', 'exercise_levels.exercise_id', 'inner')

            ->join('categories', 'categories.id', '=', 'exercises.category_id', 'inner')

            ->select('levels.*')->distinct()

            ->where('categories.id', '=', $request->category_id)->get();

        if ($levels == null) {
            return $this->apiResponse(null, "there is no Levels for this Category", 200);
        }

        return $this->apiResponse(LevelResource::collection($levels), "this is the levels for category", 200);
    }
}
