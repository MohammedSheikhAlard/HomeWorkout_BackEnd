<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Traits\apiResponseTrait;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Validated;

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
            'user' => new UserResource($user)
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

        return $this->apiResponse(new UserResource($user), 'wellcome to the app', 200, $token->plainTextToken);
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return [
            'message' => 'You are logged out'
        ];
    }
}
    /* public function editUserData(Request $request) 
    {
      
        $user = $request->user();

        if($user == null)
        {
            return $this->apiResponse(null,"something went wrong",400);
        }

        if($request->has('name'))
        {
            $request->validate([
                'name'=>'unique:users'
            ]);

            $user->update([
                'name'=>$request->name
            ]);
        }


        $user->update([
          'password' => $request->has('password')? $request->password : $user->password
          'tall',
          'weight',
          'gender',
          'BMI',
          'target_calories',
          'date_of_birth',
          'reminder',
          'level_id',
        ])
    } */
