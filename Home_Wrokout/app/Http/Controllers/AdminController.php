<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Exercise;
use App\Models\category;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Traits\apiResponseTrait;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\StoreAdminRequest;
use App\Http\Requests\UpdateAdminRequest;


class AdminController extends Controller
{
    use apiResponseTrait;


    // ================= WEB (لوحة التحكم) =================
    public function loginPage()
    {
        return view('admin.login');
    }

    public function webLogin(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        $admin = Admin::where('name', $request->input('username'))->first();
        if (!$admin || !Hash::check($request->input('password'), $admin->password)) {
            return back()->with('error', 'بيانات الدخول غير صحيحة');
        }

        $request->session()->put('admin_logged_in', true);
        $request->session()->put('admin_id', $admin->id);
        return redirect()->route('admin.dashboard');
    }

    public function webDashboard(Request $request)
    {
        if (!$request->session()->get('admin_logged_in')) {
            return redirect()->route('admin.login');
        }
        return view('admin.dashboard');
    }

    public function webLogout(Request $request)
    {
        $request->session()->forget('admin_logged_in');
        $request->session()->forget('admin_id');
        return redirect()->route('admin.login');
    }

    public function exercisesPage(Request $request)
    {
        if (!$request->session()->get('admin_logged_in')) {
            return redirect()->route('admin.login');
        }
        $exercises = Exercise::with(['category'])->orderBy('id')->get();
        $categories = category::orderBy('name')->get();
        return view('admin.exercises', compact('exercises', 'categories'));
    }

    public function exerciseStore(Request $request)
    {
        if (!$request->session()->get('admin_logged_in')) {
            return redirect()->route('admin.login');
        }
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'image_path' => 'nullable|image',
        ]);

        $exercise = Exercise::create([
            'name' => $request->name,
            'description' => $request->description,
            'image_path' => '',
            'category_id' => $request->category_id,
            'admin_id' => $request->session()->get('admin_id'),
        ]);
        if ($request->hasFile('image_path')) {
            $imageName = $request->file('image_path')->getClientOriginalName();
            $exercise->image_path = $request->file('image_path')->storeAs('exercises', $imageName, 'mohammed');
            $exercise->save();
        }
        return redirect()->route('admin.exercises');
    }

    public function exerciseUpdate(Request $request, Exercise $exercise)
    {
        if (!$request->session()->get('admin_logged_in')) {
            return redirect()->route('admin.login');
        }
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'image_path' => 'nullable|image',
        ]);
        $exercise->name = $request->name;
        $exercise->description = $request->description;
        $exercise->category_id = $request->category_id;
        if ($request->hasFile('image_path')) {
            $imageName = $request->file('image_path')->getClientOriginalName();
            $exercise->image_path = $request->file('image_path')->storeAs('exercises', $imageName, 'mohammed');
        }
        $exercise->save();
        return redirect()->route('admin.exercises');
    }

    public function exerciseDelete(Request $request, Exercise $exercise)
    {
        if (!$request->session()->get('admin_logged_in')) {
            return redirect()->route('admin.login');
        }
        $exercise->delete();
        return redirect()->route('admin.exercises');
    }

    public function exercisesTrashedPage(Request $request)
    {
        if (!$request->session()->get('admin_logged_in')) {
            return redirect()->route('admin.login');
        }
        $exercises = Exercise::onlyTrashed()->with(['category', 'levels'])->orderBy('deleted_at', 'desc')->get();
        $categories = category::orderBy('name')->get();
        return view('admin.exercises-trashed', compact('exercises', 'categories'));
    }

    public function exerciseRestore(Request $request, $id)
    {
        if (!$request->session()->get('admin_logged_in')) {
            return redirect()->route('admin.login');
        }
        $exercise = Exercise::onlyTrashed()->findOrFail($id);
        $exercise->restore();
        return redirect()->route('admin.exercises.trashed')->with('success', 'Exercise restored successfully!');
    }

    public function exerciseForceDelete(Request $request, $id)
    {
        if (!$request->session()->get('admin_logged_in')) {
            return redirect()->route('admin.login');
        }

        $exercise = Exercise::onlyTrashed()->findOrFail($id);

        // فحص إذا كان هناك مستويات تمارين مرتبطة بالتمرين
        if ($exercise->exerciseLevels()->count() > 0) {
            return redirect()->route('admin.exercises.trashed')
                ->with('error', 'Cannot permanently delete this exercise because there are exercise levels linked to it. Please delete the linked exercise levels first.')
                ->with('show_cancel_button', true);
        }

        $exercise->forceDelete();
        return redirect()->route('admin.exercises.trashed')->with('success', 'Exercise permanently deleted successfully!');
    }

    public function categoriesPage(Request $request)
    {
        if (!$request->session()->get('admin_logged_in')) {
            return redirect()->route('admin.login');
        }
        $categories = category::orderByDesc('id')->get();
        return view('admin.categories', compact('categories'));
    }

    public function categoryStore(Request $request)
    {
        if (!$request->session()->get('admin_logged_in')) {
            return redirect()->route('admin.login');
        }
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'image_path' => 'nullable|image',
        ]);

        $adminId = $request->session()->get('admin_id');
        $category = category::create([
            'name' => $request->name,
            'description' => $request->description,
            'image_path' => '',
            'admin_id' => $adminId,
        ]);

        // if ($request->hasFile('image_path')) {
        //     $imageName = $request->file('image_path')->getClientOriginalName();
        //     $category->image_path = $request->file('image_path')->storeAs('categories', $imageName, 'mohammed');
        //     $category->save();
        // }

        $imageName = $request->file('image_path')->getClientOriginalName();

        if ($request->hasFile('image_path')) {
            $category->image_path = $request->file('image_path')->storeAs('categories', $imageName, 'mohammed');
        }

        $category->save();

        return redirect()->route('admin.categories');
    }

    public function categoryUpdate(Request $request, category $category)
    {
        if (!$request->session()->get('admin_logged_in')) {
            return redirect()->route('admin.login');
        }
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'image_path' => 'nullable|image',
        ]);

        $updateData = [
            'name' => $request->name,
            'description' => $request->description,
        ];

        // Handle image update
        if ($request->hasFile('image_path')) {
            // Delete old image
            if ($category->image_path) {
                Storage::disk('mohammed')->delete($category->image_path);
            }

            // Store new image
            $imageName = $request->file('image_path')->getClientOriginalName();
            $updateData['image_path'] = $request->file('image_path')->storeAs('categories', $imageName, 'mohammed');
        }

        // Update all fields at once
        $category->update($updateData);

        return redirect()->route('admin.categories');
    }

    public function categoryDelete(Request $request, category $category)
    {
        if (!$request->session()->get('admin_logged_in')) {
            return redirect()->route('admin.login');
        }
        $category->delete();
        return redirect()->route('admin.categories');
    }

    public function categoriesTrashedPage(Request $request)
    {
        if (!$request->session()->get('admin_logged_in')) {
            return redirect()->route('admin.login');
        }
        $categories = category::onlyTrashed()->orderBy('deleted_at', 'desc')->get();
        return view('admin.categories-trashed', compact('categories'));
    }

    public function categoryRestore(Request $request, $id)
    {
        if (!$request->session()->get('admin_logged_in')) {
            return redirect()->route('admin.login');
        }
        $category = category::onlyTrashed()->findOrFail($id);
        $category->restore();
        return redirect()->route('admin.categories.trashed')->with('success', 'Category restored successfully!');
    }

    public function categoryForceDelete(Request $request, $id)
    {
        if (!$request->session()->get('admin_logged_in')) {
            return redirect()->route('admin.login');
        }

        $category = category::onlyTrashed()->findOrFail($id);

        // فحص إذا كان هناك تمارين مرتبطة بالفئة (بما في ذلك المحذوفة)
        $exercisesCount = Exercise::withTrashed()->where('category_id', $category->id)->count();

        if ($exercisesCount > 0) {
            return redirect()->route('admin.categories.trashed')
                ->with('error', 'Cannot permanently delete this category because there are exercises linked to it. Please delete the linked exercises first.')
                ->with('show_cancel_button', true);
        }

        $category->forceDelete();
        return redirect()->route('admin.categories.trashed')->with('success', 'Category permanently deleted successfully!');
    }



    public function usersPage(Request $request)
    {
        if (!$request->session()->get('admin_logged_in')) {
            return redirect()->route('admin.login');
        }

        $users = \App\Models\User::with('level')->orderBy('created_at', 'desc')->get();
        $levels = \App\Models\Level::orderBy('name')->get();
        return view('admin.users', compact('users', 'levels'));
    }

    public function userDelete(Request $request, \App\Models\User $user)
    {
        if (!$request->session()->get('admin_logged_in')) {
            return redirect()->route('admin.login');
        }

        $user->delete();
        return redirect()->route('admin.users');
    }

    public function userWalletCreate(Request $request, \App\Models\User $user)
    {
        if (!$request->session()->get('admin_logged_in')) {
            return redirect()->route('admin.login');
        }

        $request->validate([
            'walletAction' => 'required|in:set',
            'walletAmount' => 'required|numeric|min:0',
        ]);

        $balance = floatval($request->walletAmount);

        // إنشاء محفظة جديدة
        $wallet = \App\Models\Wallet::create([
            'user_id' => $user->id,
            'balance' => $balance,
        ]);

        return redirect()->route('admin.users')->with('success', 'Wallet created successfully with balance: $' . number_format($balance, 2));
    }

    public function userWalletUpdate(Request $request, \App\Models\User $user)
    {
        if (!$request->session()->get('admin_logged_in')) {
            return redirect()->route('admin.login');
        }

        $request->validate([
            'walletAction' => 'required|in:add,subtract,set',
            'walletAmount' => 'required|numeric|min:0',
        ]);

        $wallet = $user->wallet;
        if (!$wallet) {
            return redirect()->route('admin.users')->with('error', 'User does not have a wallet');
        }

        $amount = floatval($request->walletAmount);
        $action = $request->walletAction;

        switch ($action) {
            case 'add':
                $wallet->balance += $amount;
                break;
            case 'subtract':
                if ($wallet->balance < $amount) {
                    return redirect()->route('admin.users')->with('error', 'Insufficient balance');
                }
                $wallet->balance -= $amount;
                break;
            case 'set':
                $wallet->balance = $amount;
                break;
        }

        $wallet->save();

        return redirect()->route('admin.users')->with('success', 'Wallet updated successfully. New balance: $' . number_format($wallet->balance, 2));
    }

    ////////////////////////////////////////////////////////////////
    public function register(Request $request)
    {
        $fileds = $request->validate([
            'name' => 'required | unique:admins',
            'password' => 'required | min:6',
        ]);

        $admin = Admin::create($fileds);

        if ($admin == null) {
            return "Admin Register Faild";
        }

        $token = $admin->createtoken($request->name);

        return $this->apiResponse($admin, "admin added sussessfully", 200, $token->plainTextToken);
    }

    public function login(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'password' => 'required',
        ]);

        $admin = Admin::where('name', $request->name)->first();


        if (!$admin || $admin->password != $request->password) {
            return $this->apiResponse(null, 'The provided credentials are incorrect', 404);
        }

        $token = $admin->createtoken($admin->name);

        return $this->apiResponse([
            'admin' => $admin
        ], 'wellcome to the dashboard', 200, $token->plainTextToken);
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return [
            'message' => 'You are logged out'
        ];
    }
}
