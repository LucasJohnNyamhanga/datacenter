<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\BadiliKitengoAfisaRequest;
use App\Http\Requests\PataDepartmentByIdRequest;
use App\Http\Requests\StoreDepartmentRequest;
use App\Models\Department;
use App\Models\User;
use Carbon\Carbon;

class DepartmentController extends Controller
{

    public function getDepartment()
    {
        $department = Department::all();

        if ($department->count() > 0) {
            $data = [
                'status' => 200,
                'data' => $department
            ];
            return response()->json($data, 200);
        } else {
            $data = [
                'status' => 404,
                'data' => 'Hakuna department iliyopatikana!.'
            ];
            return response()->json($data, 404);
        }

    }
    public function storeDepartment(StoreDepartmentRequest $request)
    {

        $name = $request->jina;


        if (empty($name)) {
            return response()->json(['message' => 'Jaza sehemu zote zilizo wazi'], 401);
        } else {
            $office = Department::query()->where("name", $name)->first();

            if (!$office) {
                $office = Department::create([
                    'name' => $name,
                ]);

                return response()->json(['message' => 'Depatiment Imetengenezwa'], 200);
            } else {
                return response()->json(['message' => 'Tayari jina limeshasajiliwa.'], 401);
            }
        }

    }

    public function getDepartmentByUserId(PataDepartmentByIdRequest $request)
    { 
        {
        $user_id = $request->afisaId;
        $department = Department::with([
            'office' => function ($query) {
                $query->with(['mapato'=> function ($query) {
                                        $query->whereDate('created_at', Carbon::today());
                                    },'matumizi'=> function ($query) {
                                        $query->whereDate('created_at', Carbon::today());
                                    },
                    'user' => function ($query) {
                        $query->with([
                            'customer' => function ($query) {
                                $query->with([
                                    'loan' => function ($query) {
                                        $query->with('marejesho')->latest()->take(1);
                                    }
                                ])->whereRelation('loan', 'hali', '=', true);
                            }
                        ]);
                    },
                    'customer' => function ($query) {
                                $query->with([
                                    'loan' => function ($query) {
                                        $query->with('marejesho');
                                    }
                                ])->whereRelation('loan', 'hali', '=', true);
                            }
                ]);
            }
        ])
        ->where("manager_id", $user_id)
        ->get();
                $data = [
                    'status' => 200,
                    'data' => $department
                ];
                return response()->json($data, 200);

        }
    }


    public function getDepartmentByAfisaId(PataDepartmentByIdRequest $request)
    { 
        {
        $user_id = $request->afisaId;
        $department = Department::with([
            'office' => function ($query) {
                $query->with(['mapato'=> function ($query) {
                                        $query->whereDate('created_at', Carbon::today());
                                    },'matumizi'=> function ($query) {
                                        $query->whereDate('created_at', Carbon::today());
                                    },
                    'user' => function ($query) {
                        $query->with([
                            'customer' => function ($query) {
                                $query->with([
                                    'loan' => function ($query) {
                                        $query->with('marejesho');
                                    }
                                ])->whereRelation('loan', 'hali', '=', true);
                            }
                        ]);
                    },
                    'customer' => function ($query) {
                                $query->with([
                                    'loan' => function ($query) {
                                        $query->with('marejesho');
                                    }
                                ])->whereRelation('loan', 'hali', '=', true);
                            }
                ]);
            }
        ])
        ->whereRelation('users', 'id', '=', $user_id)
        ->get();
                $data = [
                    'status' => 200,
                    'data' => $department
                ];
                return response()->json($data, 200);

        }
    }

    public function getDepartmentById(PataDepartmentByIdRequest $request)
    { 
        {
        $id = $request->id;
        $department = Department::with([
            'office' => function ($query) {
                $query->with(['mapato'=> function ($query) {
                                        $query->whereDate('created_at', Carbon::today());
                                    },'matumizi'=> function ($query) {
                                        $query->whereDate('created_at', Carbon::today());
                                    },
                    'user' => function ($query) {
                        $query->with([
                            'customer' => function ($query) {
                                $query->with([
                                    'loan' => function ($query) {
                                        $query->with('marejesho');
                                    }
                                ])->whereRelation('loan', 'hali', '=', true);
                            }
                        ]);
                    },
                    'customer' => function ($query) {
                                $query->with([
                                    'loan' => function ($query) {
                                        $query->with('marejesho');
                                    }
                                ])->whereRelation('loan', 'hali', '=', true);
                            }
                ]);
            }
        ])
        ->where("id", $id)
        ->get();
           
                $data = [
                    'status' => 200,
                    'data' => $department
                ];
                return response()->json($data, 200);
           

        }
    }

    public function getDepartments()
    { 
        {
        
        $department = Department::with([
            'office' => function ($query) {
                $query->with(['mapato'=> function ($query) {
                                        $query->whereDate('created_at', Carbon::today());
                                    },'matumizi'=> function ($query) {
                                        $query->whereDate('created_at', Carbon::today());
                                    },
                    'user' => function ($query) {
                        $query->with([
                            'customer' => function ($query) {
                                $query->with([
                                    'loan' => function ($query) {
                                        $query->with('marejesho');
                                    }
                                ])->whereRelation('loan', 'hali', '=', true);
                            }
                        ]);
                    },
                    'customer' => function ($query) {
                                $query->with([
                                    'loan' => function ($query) {
                                        $query->with('marejesho');
                                    }
                                ])->whereRelation('loan', 'hali', '=', true);
                            }
                ]);
            }
        ])
        ->get();
                $data = [
                    'status' => 200,
                    'data' => $department
                ];
                return response()->json($data, 200);

        }
    }

    public function badiliMeneja(BadiliKitengoAfisaRequest $request)
        {
            $afisaId = $request->userId;
            $kitengoId = $request->kitengoId;

            $kitengo = Department::find($kitengoId);
            $managerId = $kitengo->manager_id;

        if ($managerId) {
            $user = User::find($managerId);
            $user->isManager = false;
            $user->save();
        }


            $kitengo->manager_id = $afisaId;
            $kitengo->save();

            $user = User::find($afisaId);
            $user->isManager = true;
            $user->save();

            return response()->json(['message' => 'Afisa Amewekwa Kitengoni'], 200);
    }

    public function badiliAdmin(BadiliKitengoAfisaRequest $request)
        {
            $afisaId = $request->userId;

            $user = User::find($afisaId);
            $status = $user->isAdmin;
            $user->isAdmin = !$status;
            $user->save();

            return response()->json(['message' => 'Admin Amebadilishwa'], 200);
    }

    public function sahishaKitengo(BadiliKitengoAfisaRequest $request)
        {
            $jina = $request->jina;
            $kitengoId = $request->kitengoId;

            $kitengo = Department::find($kitengoId);
            $kitengo->name = $jina;
    
            $kitengo->save();

            return response()->json(['message' => 'Afisa Amewekwa Kitengoni'], 200);
    }


    public function getDepartmentsWithOfices(BadiliKitengoAfisaRequest $request)
    {
        $department = Department::with(['office'])->get();

        $data = [
            'status' => 200,
            'data' => $department
        ];
        return response()->json($data, 200);

        
    }
}