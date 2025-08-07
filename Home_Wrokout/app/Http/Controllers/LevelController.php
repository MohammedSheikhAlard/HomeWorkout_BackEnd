<?php

namespace App\Http\Controllers;

use App\Models\Level;
use App\Http\Requests\StoreLevelRequest;
use App\Http\Requests\UpdateLevelRequest;
use App\Http\Resources\LevelResource;
use App\Traits\apiResponseTrait;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

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
}
