<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Http\Request;
use App\Traits\apiResponseTrait;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\StoreAdminRequest;
use App\Http\Requests\UpdateAdminRequest;

class AdminController extends Controller
{
    use apiResponseTrait;

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
