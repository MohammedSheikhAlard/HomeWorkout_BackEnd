<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Traits\apiResponseTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    use apiResponseTrait;


    public function register(Request $request)
    {
        $fileds = $request->validate([
            'name' => 'required|unique:users',
            'password' => 'required| min:6',
            'level_id' => 'required',
        ]);

        $user = User::create($fileds);

        $token = $user->createtoken($request->name);

        return $this->apiResponse([
            'token' => $token->plainTextToken,
            'user' => $user
        ], 'user created sussessfully', 200);
    }

    public function login(Request $request)
    {
        $fileds = $request->validate([
            'name' => 'required',
            'password' => 'required',
        ]);

        $user = User::where('name', '=', $request->name)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return $this->apiResponse(null, 'The provided credentials are incorrect', 404);
        }

        $token = $user->createtoken($user->name);

        return $this->apiResponse([
            'user' => $user
        ], 'wellcome to the app', 200, $token->plainTextToken);
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return [
            'message' => 'You are logged out'
        ];
    }
}
