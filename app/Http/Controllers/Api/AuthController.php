<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\OfficeIdRequest;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use App\Http\Controllers\Controller;
use App\Http\Requests\SignupRequest;
use App\Http\Requests\UserRequest;
use App\Models\Office;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\PersonalAccessToken;
use Validator;

class AuthController extends Controller
{
    public function login(LoginRequest $request)
    {
        $username = $request->username;
        $password = $request->password;

        if (empty($username) || empty($password)) {
            return response()->json(['message' => 'Jaza nafasi zote zilizo wazi.'], 401);
        }
        
        $user = User::query()->where("username", $username)->first();

        if ($user) {
            $passwordDatabase = $user->password;
            if ($password == $passwordDatabase) {
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
        $retypePassword = $request->retypePassword;
        $mobile = $request->mobile;


        if (empty($username) || empty($password) || empty($retypePassword) || empty($mobile)|| empty($office)) {
            return response()->json(['message' => 'Jaza sehemu zote zilizo wazi'], 401);
        } else {

            if ($password == $retypePassword) {
                $user = User::query()->where("username", $username)->first();
                
                if (!$user) {
                    $user = User::create([
                        'office' => $office,
                        'username' => $username,
                        'password' => $password,
                        'role' => 'normal',
                        'active' => false,
                        'mobile' => $mobile,
                    ]);

                    return response()->json(['message' => 'Akaunti Imetengenezwa'], 200);
                } else {
                    return response()->json(['message' => 'Tayari jina limeshasajiliwa.'], 401);
                }
            }else{
                return response()->json(['message' => 'Maneno ya siri hayafanani'], 401);
            }
        }
    }

    public function logout(Request $request)
    {
        $user = $request->user();
        $user->currentAccessToken()->delete();

        return response()->json(['message'=> 'logout'],200);
    }


      public function user(UserRequest $request)
    {
        $tokenUser = $request->token;

        if (empty($token)) {
            return response()->json(['message' => 'Token iko wazi'], 401);
        } else {

            $token = PersonalAccessToken::findToken($tokenUser);
            $user = $token->tokenable;

            if (!$user) {
                return response((compact('user')));
            } else {
                return response()->json(['message' => 'Hakuna mtumiaji aliyepatikana'], 401);
            }
        }
        
    }
}
