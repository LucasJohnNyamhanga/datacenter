<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use App\Http\Controllers\Controller;
use App\Http\Requests\SignupRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(LoginRequest $request)
    {
        $credentials = $request->validated();
        $credentials["password"] = bcrypt($credentials["password"]);
        $user = User::query()->where("username", $credentials["username"])->first();

        if ($user && Hash::check($request->input('password'), $user->password)) {
            // Password matches, do something (e.g., log in the user)
            $token = $user->createToken('main')->plainTextToken;
            return response((compact('user', 'token')));
        } else {
            // Invalid username or password
            return response()->json(['message' => 'Umekosea Jina au Neno la siri'], 401);
        }

    }
    public function signup(SignupRequest $request)
    {
        $data = $request->validated();

        $user = User::create([
            'office' => $data['office'],
            'username' => $data['username'],
            'password' => bcrypt($data['password']),
            'mobile' => bcrypt($data['mobile']),
        ]);

        $token = $user->createToken('main')->plainTextToken;

        return response((compact('user', 'token')));
    }
    public function logout(Request $request)
    {
        $user = $request->user();
        $user->currentAccessToken()->delete();

        return response()->json(['message'=> ''],204);
    }
}
