<?php

namespace App\Http\Controllers;

use App\Models\Exercise;
use Illuminate\Http\Request;
use App\Traits\apiResponseTrait;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\ExerciseResource;
use App\Http\Requests\StoreExerciseRequest;
use App\Http\Requests\UpdateExerciseRequest;

class ExerciseController extends Controller
{
    use apiResponseTrait;

    public function addNewExercise(Request $request)
    {
        $fildes = $request->validate([
            'name' => 'required',
            'description' => 'required',
            'image_path' => 'required',
            'category_id' => 'required',
        ]);

        $admin = $request->user();

        $exercise = Exercise::create([
            'name' => $request->name,
            'description' => $request->description,
            'image_path' => $request->image_path,
            'category_id' => $request->category_id,
            'admin_id' => $admin->id,
        ]);

        $imageName = $request->file('image_path')->getClientOriginalName();

        if ($request->hasFile('image_path')) {
            $exercise->image_path = $request->file('image_path')->storeAs('exercises', $imageName, 'mohammed');
        }

        $exercise->save();

        return $this->apiResponse($exercise, "exercise added sussessfully", 200);
    }

    public function getAllExercises()
    {
        $exercises  = Exercise::all();


        if ($exercises == null) {
            return $this->apiResponse(null, "something went wrong", 400);
        }

        return $this->apiResponse(ExerciseResource::collection($exercises), "This is all exercisees", 200);
    }


    public function updateExercise(Request $request)
    {
        $request->validate([
            'id' => 'required',
        ]);

        $admin = $request->user();

        $exercise = Exercise::find($request->id);

        $exercise->update([
            'name' => $request->has('name') ? $request->name : $exercise->name,
            'description' => $request->has('description') ? $request->description : $exercise->description,
            'image_path' => $exercise->image_path, // keep old until replaced
            'category_id' => $request->has('category_id') ? $request->category_id : $exercise->category_id,
            'admin_id' => $admin->id,
        ]);

        if ($request->hasFile('image_path')) {
            // delete the old image if it exists
            if ($exercise->image_path && Storage::disk('mohammed')->exists($exercise->image_path)) {
                Storage::disk('mohammed')->delete($exercise->image_path);
            }

            // store new image
            $imageName = $request->file('image_path')->getClientOriginalName();
            $exercise->image_path = $request->file('image_path')->storeAs('exercises', $imageName, 'mohammed');
        }

        $exercise->save();

        return $this->apiResponse($exercise, "Exercise Updated Successfully", 200);
    }


    public function deleteExercise(Request $request)
    {
        $exercise = Exercise::find($request->id);

        if ($exercise == null) {
            return $this->apiResponse(null, "We couldn't find your exercise", 400);
        }
        $exercise->delete();

        return "Exercise Deleted Successfully";
    }
}
