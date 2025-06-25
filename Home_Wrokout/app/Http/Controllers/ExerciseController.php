<?php

namespace App\Http\Controllers;

use App\Models\Exercise;
use App\Http\Requests\StoreExerciseRequest;
use App\Http\Requests\UpdateExerciseRequest;
use App\Http\Resources\ExerciseResource;
use App\Traits\apiResponseTrait;
use Illuminate\Http\Request;

class ExerciseController extends Controller
{
    use apiResponseTrait;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreExerciseRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Exercise $exercise)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateExerciseRequest $request, Exercise $exercise)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Exercise $exercise)
    {
        //
    }


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

        return $this->apiResponse($exercise, "exercies added sussessfully", 200);
    }

    public function getAllExercises()
    {
        $exercises  = Exercise::all();

        if ($exercises == null) {
            return $this->apiResponse(null, "something went wrong", 400);
        }

        return $this->apiResponse(new ExerciseResource($exercises), "This is all exercieses", 200);
    }

    public function updateExercise(Request $request)
    {
        $fildes = $request->validate([
            'name' => 'required',
            'description' => 'required',
            'image_path' => 'required',
            'category_id' => 'required',
        ]);

        $admin = $request->user();

        $exercise = Exercise::find($request->id);

        $exercise->update([
            'name' => $request->name,
            'description' => $request->description,
            'image_path' => $request->image_path,
            'category_id' => $request->category_id,
            'admin_id' => $admin->id,
        ]);

        return $this->apiResponse($exercise, "Exercise Updated Successfully", 200);
    }

    public function deleteExercise(Request $request)
    {
        $exercise = Exercise::find($request->id);

        if ($exercise == null) {
            return $this->apiResponse(null, "We couldn't find your exercies", 400);
        }
        $exercise->delete();

        return "Exercise Deleted Successfully";
    }
}
