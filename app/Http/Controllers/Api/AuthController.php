<?php

namespace App\Http\Controllers\Api;


use App\Http\Requests\BadiliTawiRequest;
use App\Http\Requests\ChangeUserStatusRequest;
use App\Models\Department;
use App\Models\Office;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use App\Http\Controllers\Controller;
use App\Http\Requests\SignupRequest;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(LoginRequest $request)
    {
        $username = $request->username;
        $password = $request->password;

        if (empty($username) || empty($password)) {
            return response()->json(['message' => 'Jaza nafasi zote zilizo wazi.'], 401);
        }

       
        
        $user = User::where("username", $username)->where("isActive", true)->first();

        if ($user) {
            $passwordDatabase = $user->password;
            if ($password == $passwordDatabase) {
                //get departments and offices
                $token = $user->createToken('main')->plainTextToken;
                $office = Office::all();        
                $department = Department::all();
                $data = [
                    'status' => 200,
                    'department' => $department,
                    'token' => $token,
                    'office'=>$office,
                    'user'=>$user,
                ];
                return response()->json($data, 200);
        
            } else {
                return response()->json(['message' => 'Umekosea jina au password'], 401);
            }
        }else{
            return response()->json(['message' => 'Tumia jina au password halisi'], 401);
        }
    

    }
    public function signup(SignupRequest $request)
    {

        $username = $request->username;
        $jinaKamili = $request->jinaKamili;
        $picha = $request->picha;
        $jinaMdhamini = $request->jinaMdhamini;
        $simuMdhamini = $request->simuMdhamini;
        $password = $request->password;
        $retypePassword = $request->retypePassword;
        $mobile = $request->mobile;
        $departmentId = $request->departmentId;
        $officeId = $request->officeId;


        if (empty($username) || empty($password) || empty($retypePassword) || empty($mobile)|| empty($jinaKamili)|| empty($picha)|| empty($jinaMdhamini)|| empty($simuMdhamini)) {
            return response()->json(['message' => 'Jaza sehemu zote zilizo wazi'], 401);
        } else {

            if ($password == $retypePassword) {
                $user = User::query()->where("username", $username)->first();


                if (!$user) {
                    $user = User::create([
                        'mobile' => $mobile,
                        'jinaKamili' => $jinaKamili,
                        'picha' => $picha,
                        'jinaMdhamini' => $jinaMdhamini,
                        'simuMdhamini' => $simuMdhamini,
                        'username' => $username,
                        'password' => $password,
                        'department_id' => $departmentId,
                        'office_id' => $officeId,
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

    public function getUsers()
        {
            $users = User::all();
            if ($users) {
                return response((compact('users')));
            } else {
                return response()->json(['message' => 'Hakuna mtumiaji aliyepatikana'], 401);
            }
    }

    public function getUsersWithOfisiId(BadiliTawiRequest $request)
        {
            $tawiId = $request->ofisiId;
            $users = User::query()->where("office_id", $tawiId)->get();
            if ($users) {
                return response((compact('users')));
            } else {
                return response()->json(['message' => 'Hakuna mtumiaji aliyepatikana'], 401);
            }
    }

    public function badiliTawi(BadiliTawiRequest $request)
        {
            $afisaId = $request->afisaId;
            $tawiId = $request->tawiId;

            $afisa = User::find($afisaId);
            $afisa->office_id = $tawiId;
    
            $afisa->save();

            return response()->json(['message' => 'Afisa Amebadilishwa Ofisi'], 200);
    }

    public function activateUser(ChangeUserStatusRequest $request)
        {
            $isActive = $request->isActive;
            $id = $request->id;

            $afisa = User::find($id);
            $afisa->isActive = $isActive;
    
            $afisa->save();

            return response()->json(['message' => 'Afisa Amebadilishwa Ofisi'], 200);
    }

     public function activateAdmin(ChangeUserStatusRequest $request)
        {
            $id = $request->id;

            $afisa = User::find($id);
            $status = $afisa->isAdmin;
            $afisa->isAdmin = !$status;
    
            $afisa->save();

            return response()->json(['message' => 'Afisa Amebadilishwa Uadimin'], 200);
    }

    public function isAdmin(ChangeUserStatusRequest $request)
        {
            $id = $request->id;

            $afisa = User::find($id);

            if (!$afisa) {
                return response()->json(['message' => 'User not found'], 404);
            }

            $isAdmin = $afisa->isAdmin;


            return response()->json(['message' => $isAdmin], 200);
    }

    public function changePassword(ChangeUserStatusRequest $request)
        {

            $oldPassword = $request->oldPassword;
            $newPassword = $request->newPassword;
            $id = $request->id;

            $afisa = User::find($id);

        if ($afisa) {
            $passwordDatabase = $afisa->password;

            if ($oldPassword == $passwordDatabase) {
                $afisa->password = $newPassword;
                $afisa->save();
                return response()->json(['message' => 'Mabadiliko ya neno la siri yamekanilika.'], 200);
            }
            return response()->json(['message' => 'Tafadhali weka password iliyosahihi kuweza kuibadilisha'], 401);
        }

        return response()->json(['message' => 'Afisa Ameshindwa kupatikana'], 404);
    }

    public function editUser(ChangeUserStatusRequest $request)
    {
        $validator = Validator::make($request->all(), [
            'mobile' => 'required|string',
            'jinaKamili' => 'required|string|max:255',
            'jinaMdhamini' => 'required|string|max:255',
            'simuMdhamini' => 'required|string',
            'picha' => 'required|string|max:255',
            'officeId' => 'required|numeric',
            'departmentId' => 'required|numeric',
            'username' => 'required|string',
            'id' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Hakikisha sehemu zote zimejazwa', 'errors' => $validator->errors()], 400);
        }

        $mobile = $request->input('mobile');
        $jinaKamili = $request->input('jinaKamili');
        $jinaMdhamini = $request->input('jinaMdhamini');
        $simuMdhamini = $request->input('simuMdhamini');
        $picha = $request->input('picha');
        $officeId = $request->input('officeId');
        $departmentId = $request->input('departmentId');
        $username = $request->input('username');
        $id = $request->input('id');

        // Retrieve the package by its ID
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'Mtumishi hajapatikana.'], 404);
        }

        // Check for existing package with the same title (excluding the current package being edited)
        $existingUser = User::where('jinaKamili', $jinaKamili)
        ->where('id','!=' ,$id)
        ->first();
        if ($existingUser) {
            return response()->json(['message' => 'Mtumishi mwingine anatumia jina hilo, imeshindikana kubadili.'], 409);
        }

        // Update the existing package
        $user->update([
            'mobile' => $mobile,
            'jinaKamili' => $jinaKamili,
            'username' => $username,
            'picha' => $picha,
            'jinaMdhamini' => $jinaMdhamini,
            'simuMdhamini' => $simuMdhamini,
            'department_id' => $departmentId,
            'office_id' => $officeId,
        ]);

        return response()->json(['message' => 'Mtumishi amebadilishiwa taarifa.'], 200);
    }

    public function getUserById(BadiliTawiRequest $request)
        {
            $validator = Validator::make($request->all(), [
            'id' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Mtumishi hana namba ya kitambulisho (ID)', 'errors' => $validator->errors()], 400);
        }

        $id = $request->input('id');

        $user = User::with(['department','office'])->where('id',$id)
        ->first();
        
        User::find($id);

        if ($user) {
            return response()->json(['user' => $user], 200);
        } else {
            return response()->json(['message' => 'Hakuna mtumishi aliyepatikana'], 401);
        }
    }
}