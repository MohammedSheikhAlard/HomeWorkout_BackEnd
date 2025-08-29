<?php

namespace App\Http\Controllers;


use App\Models\category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\StorecategoryRequest;
use App\Http\Requests\UpdatecategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Admin;
use App\Traits\apiResponseTrait;
use Illuminate\Support\Facades\Storage;

use function Pest\Laravel\delete;

class CategoryController extends Controller
{
    use apiResponseTrait;

    ////////////////////////////////////////////////////////
    public function webCategoriesPage(Request $request)
    {
        if (!$request->session()->get('admin_logged_in')) {
            return redirect()->route('admin.login');
        }

        $categories = category::all();
        return view('admin.categories', compact('categories'));
    }

    public function webCategoryStore(Request $request)
    {
        if (!$request->session()->get('admin_logged_in')) {
            return redirect()->route('admin.login');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'image_path' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $category = new category();
        $category->name = $request->name;
        $category->description = $request->description;
        $category->admin_id = $request->session()->get('admin_id');

        if ($request->hasFile('image_path')) {
            $imageName = time() . '_' . $request->file('image_path')->getClientOriginalName();
            $imagePath = $request->file('image_path')->storeAs('categories', $imageName, 'public');
            $category->image_path = $imagePath;
        }

        $category->save();

        return redirect()->route('admin.categories')->with('success', 'Category created successfully!');
    }

    public function webCategoryUpdate(Request $request, category $category)
    {
        if (!$request->session()->get('admin_logged_in')) {
            return redirect()->route('admin.login');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'image_path' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $category->name = $request->name;
        $category->description = $request->description;

        if ($request->hasFile('image_path')) {
            if ($category->image_path && Storage::disk('public')->exists($category->image_path)) {
                Storage::disk('public')->delete($category->image_path);
            }

            $imageName = time() . '_' . $request->file('image_path')->getClientOriginalName();
            $imagePath = $request->file('image_path')->storeAs('categories', $imageName, 'public');
            $category->image_path = $imagePath;
        }

        $category->save();

        return redirect()->route('admin.categories')->with('success', 'Category updated successfully!');
    }

    public function webCategoryDelete(Request $request, category $category)
    {
        if (!$request->session()->get('admin_logged_in')) {
            return redirect()->route('admin.login');
        }

        if ($category->image_path && Storage::disk('public')->exists($category->image_path)) {
            Storage::disk('public')->delete($category->image_path);
        }

        $category->delete();

        return redirect()->route('admin.categories')->with('success', 'Category deleted successfully!');
    }
    //////////////////////////////////////////////////
    public function addNewCategory(Request $request)
    {
        $fileds = $request->validate([
            'name' => 'required',
            'description' => 'required',
            'image_path' => 'required'
        ]);

        $admin = $request->user();

        $category = category::create([
            'name' => $request->name,
            'description' => $request->description,
            'image_path' => $request->image_path,
            'admin_id' => $admin->id,
        ]);


        if (!$category) {
            return $this->apiResponse(null, "The provided credentials are incorrect", 404);
        }

        $imageName = $request->file('image_path')->getClientOriginalName();

        if ($request->hasFile('image_path')) {
            $category->image_path = $request->file('image_path')->storeAs('categories', $imageName, 'mohammed');
        }

        $category->save();


        return $this->apiResponse($category, "category added sussessfully", 200);
    }

    public function updateCategory(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:categories,id',
        ]);

        $category = category::find($request->id);


        $category->update([
            'name' => $request->has('name') ? $request->name : $category->name,
            'description' => $request->has('description') ? $request->description : $category->description,
        ]);

        if ($request->has('image_path') == true) {


            $imageName = $request->file('image_path')->getClientOriginalName();


            $category->image_path = $request->file('image_path')->storeAs('categories', $imageName, 'mohammed');

            $category->save();
        }

        return $this->apiResponse($category, "Category Updated Successfully", 200);
    }

    public function getAllCategory(Request $request)
    {
        $admin = $request->user();

        $categories = category::where('admin_id', '=', $admin->id)->get();

        return $this->apiResponse(new CategoryResource($categories), "this is all category you have", 200);
    }

    public function deleteCategory(Request $request)
    {

        $category = category::find($request->id);

        if ($category == null) {
            return $this->apiResponse(null, "Category deleted failed", 200);
        }

        $category->delete();

        return "category deleted successfully";
    }

    public function getAllUserCategory()
    {
        $categories = category::get()->all();

        if ($categories ==  null) {
            return $this->apiResponse(null, "something went wrong", 400);
        }

        return $this->apiResponse(new CategoryResource($categories), "this is all categories", 200);
    }
}
