<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use App\Http\Controllers\Controller;
use App\Http\Requests\SignupRequest;
use Illuminate\Support\Facades\Hash;
use Validator;

class AuthController extends Controller
{
    public function login(LoginRequest $request)
    {
        $user = $request->username;
        $password = $request->password;

        if (empty($user) || empty($password)) {
            return response()->json(['message' => 'Jaza nafasi zote zilizo wazi.'], 401);
        }
        
        $user = User::query()->where("username", $user)->first();

        if ($user) {
            $credentials["password"] = bcrypt($password);
            if ($user && Hash::check($request->input('password'), $user->password)) {
                // Password matches, do something (e.g., log in the user)
                $token = $user->createToken('main')->plainTextToken;
                return response((compact('user', 'token')));
            } else {
                // Invalid username or password
                return response()->json(['message' => 'Umekosea jina au password'], 401);
            }
        }else{
            return response()->json(['message' => 'Tumia jina au password halisi'], 401);
        }

    }
    public function signup(SignupRequest $request)
    {
      
        $office = $request->office;
            $username = $request->username;
            $password = $request->password;
            $role = $request->role;
           $active = $request->active;
        $mobile = $request->mobile;

        if (empty($username) || empty($password) || empty($role) || empty($mobile)|| empty($office)) {
            return response()->json(['message' => 'Jaza sehemu zote zilizo wazi'], 401);
        } else {
            $user = User::query()->where("username", $username)->first();
            if (!$user) {
                $user = User::create([
                    'office' => $request->office,
                    'username' => $request->username,
                    'password' => $request->password,
                    'role' => $request->role,
                    'active' => $request->active,
                    'mobile' => $request->mobile,
                ]);

                return response()->json(['message' => 'Akaunti Imetengenezwa'], 200);
            } else {
                return response()->json(['message' => 'Tayari jina limeshasajiliwa.'], 401);
            }
        }
    }
    public function logout(Request $request)
    {
        $user = $request->user();
        $user->currentAccessToken()->delete();

        return response()->json(['message'=> 'logout'],200);
    }
}
