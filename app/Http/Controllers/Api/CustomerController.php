<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\GetNewCustomerRequest;
use App\Http\Requests\MtejaDetailRequest;
use App\Http\Requests\StorecustomerRequest;
use App\Http\Requests\UpdateAfisaMkopoCustomer;
use App\Http\Requests\WatejaWanaofananaRequest;
use App\Models\Customer;

class CustomerController extends Controller
{
    //
    public function storeCustomer(StorecustomerRequest $request){

        $jina = $request->jina;
        $jinaMaarufu = $request->jinaMaarufu;
        $jinsia = $request->jinsia;
        $anapoishi = $request->anapoishi;
        $simu = $request->simu;
        $kazi = $request->kazi;
        $picha = $request->picha;
        $offices_id = $request->officeId;
        $users_id = $request->userId;

        if (empty($jina) || empty($jinaMaarufu) || empty($jinsia) || empty($anapoishi)|| empty($simu)|| empty($kazi)|| empty($picha)|| empty($offices_id)|| empty($users_id)) {
            return response()->json(['message' => 'Jaza sehemu zote zilizo wazi'], 401);
        } else {
                $customer = Customer::query()->where("jina", $jina)
                ->orWhere('simu', '=', $simu)
                ->first();
                if (!$customer) {
                    $customer = Customer::create([
                        'jina' => $jina,
                        'jinaMaarufu' => $jinaMaarufu,
                        'jinsia' => $jinsia,
                        'anapoishi' => $anapoishi,
                        'simu' => $simu,
                        'kazi' => $kazi,
                        'picha' => $picha,
                        'office_id' => $offices_id,
                        'user_id' => $users_id,
                    ]);

                    $id = $customer->id;
                    return response()->json(['message' => $id], 200);
                } else {
                    return response()->json(['message' => 'Tayari Mteja Ameshasajiliwa.'], 401);
                }
        }
    }

    public function getNewCustomer(GetNewCustomerRequest $request)
    {
        $officeId = $request->officeId;
        $customer = Customer::with('loan')->whereRelation('loan', 'mpya', '=', true)
        ->where('office_id','=',$officeId)
        ->whereRelation('loan', 'mpya', '=', true)
        ->get();
        return response()->json(['data' => $customer], 200);
    }

    public function getCustomerDetails(MtejaDetailRequest $request)
    {
        $id = $request->id;
        $loanId = $request->loanId;

        $customer = Customer::with([
            'loan' => function ($query) use ($loanId) {
        $query->where('id', $loanId)->latest()->take(1);
            },
            'mdhamini',
            'dhamana',
            'marejesho' => function ($query) use ($loanId) {
                $query->where('loan_id', $loanId);
            }
        ])
        ->where('id', $id)
        ->first();


        foreach ($customer->loan as $loan) {
                $totalRepayments = $loan->marejesho->sum('kiasi');
                $deni = ($loan->kiasi * ($loan->riba / 100)) + $loan->kiasi;
                if ($totalRepayments >= $deni) {
                    $loan->hali = false;
                    $loan->njeMuda = false;
                    $loan->save();
                    }
                }

        $customer = Customer::with([
            'loan' => function ($query) use ($loanId) {
        $query->where('id', $loanId)->latest()->take(1);
            },
            'mdhamini',
            'dhamana',
            'marejesho' => function ($query) use ($loanId) {
                $query->where('loan_id', $loanId);
            }
        ])
        ->where('id', $id)
        ->first();

        if($customer){
            return response()->json(['data' => $customer], 200);
        }
            return response()->json(['data' => 'Mteja Hayupo Kwenye List Ya Wateja Wenye Mkopo.'], 404);
    }

    public function getKasoroCustomer(GetNewCustomerRequest $request)
    {
        $officeId = $request->officeId;
        $customer = Customer::with('loan')->whereRelation('loan', 'kasoro', '=', true)
        ->where('office_id','=',$officeId)
        ->whereRelation('loan', 'kasoro', '=', true)
        ->get();
        return response()->json(['data' => $customer], 200);
    }

    public function getWateja(GetNewCustomerRequest $request)
    {
        $officeId = $request->officeId;
        $customers = Customer::with(['loan' => function ($query) {
                $query->latest()->take(1);
            }, 'mdhamini', 'dhamana', 'marejesho'])
            ->where('office_id', '=', $officeId)
            ->whereHas('loan', function ($query) {
                    $query->where('hali', true);
                })
            ->get();

            foreach ($customers as $customer) {
            foreach ($customer->loan as $loan) {
                $totalRepayments = $loan->marejesho->sum('kiasi');
                $deni = ($loan->kiasi * ($loan->riba / 100)) + $loan->kiasi;
                if ($totalRepayments >= $deni) {
                    $loan->hali = false;
                    $loan->njeMuda = false;
                    $loan->save();
                    }
                }
            }

        $customers = Customer::with(['loan' => function ($query) {
                $query->latest()->take(1);
            }, 'mdhamini', 'dhamana', 'marejesho'])
            ->where('office_id', '=', $officeId)
            ->whereHas('loan', function ($query) {
                    $query->where('hali', true);
                })
            ->get();

        return response()->json(['data' => $customers], 200);
    }
    
    public function badiliAfisaMkopo(UpdateAfisaMkopoCustomer $request)
    {
        $customerId = $request->customerId;
        $afisaId = $request->afisaId;

        $customer = Customer::find($customerId);
        $customer->user_id = $afisaId;
    
        $customer->save();

        return response()->json(['message' => 'Afisa Mkopo Amebadilishwa'], 200);
    }

    public function tafutaWatejaWanaofanana(WatejaWanaofananaRequest $request)
    {
        $jina = $request->jina;
        $namba = $request->namba;
        $customer = Customer::with(['loan' => function ($query) {
                $query->latest()->take(1);
            }, 'mdhamini', 'dhamana', 'marejesho'])
            ->whereRelation('loan', 'hali', '=', true)
            ->where(function($query) use ($jina, $namba) {
                $query->where('jina', 'LIKE', '%' . $jina . '%')
                ->orWhere('simu', 'LIKE', '%' . $namba . '%');
            })
        ->get();
        return response()->json(['data' => $customer], 200);
    }

    public function getMteja(GetNewCustomerRequest $request)
    {
        //$officeId = $request->officeId;
        $jina = $request->jina;

        // Fetch customers with their relationships and filter based on conditions
        $customers = Customer::with(['loan' => function ($query) {
                $query->latest()->take(1);
            }, 'mdhamini', 'dhamana', 'marejesho'])
            ->whereRelation('loan', function ($query) {
                $query
                ->where('mpya', false)
                    ->where('kasoro', false);
            })
            ->where(function($query) use ($jina) {
                $query->where('jina', 'LIKE', '%' . $jina . '%')
                    ->orWhere('simu', 'LIKE', '%' . $jina . '%');
            })
            ->orWhere(function($query) use ($jina) {
                $query->whereHas('mdhamini', function($query) use ($jina) {
                    $query->where('jina', 'LIKE', '%' . $jina . '%')
                        ->orWhere('simu', 'LIKE', '%' . $jina . '%');
                });
            })
            ->get();

        return response()->json(['data' => $customers], 200);
    }


    public function getWatejaWaliomaliza(GetNewCustomerRequest $request)
    {
        $officeId = $request->officeId;
        $customers = Customer::with(['loan' => function ($query) {
                $query->latest()->take(1);
            }, 'mdhamini', 'dhamana', 'marejesho'])
        ->where('office_id', $officeId)
        ->whereDoesntHave('loan', function ($query) {
            $query->where('mpya', true)->orWhere('hali', true);
        })
        ->get();

        // Return the filtered customers in the response
        return response()->json(['data' => $customers], 200);
    }

    public function getWatejaNjeMuda(GetNewCustomerRequest $request)
    {
        $officeId = $request->officeId;

        $customers = Customer::with(['loan' => function ($query) {
                $query->latest()->take(1);
            }, 'mdhamini', 'dhamana', 'marejesho'])
        ->where('office_id', '=', $officeId)
        ->whereHas('loan', function ($query) {
            $query->where('hali', true)
                  ->whereRaw('mwanzoMkopo < DATE_SUB(NOW(), INTERVAL siku DAY)');
        })
        ->get();

        return response()->json(['data' => $customers], 200);
    }


    public function rekebishaCustomer(StorecustomerRequest $request){

        // Extract request data
        $id = $request->id;
        $jina = $request->jina;
        $jinaMaarufu = $request->jinaMaarufu;
        $jinsia = $request->jinsia;
        $anapoishi = $request->anapoishi;
        $simu = $request->simu;
        $kazi = $request->kazi;
        $picha = $request->picha;
        $offices_id = $request->officeId;
        $users_id = $request->userId;

        // Check for empty fields
        if (empty($jina) || empty($jinaMaarufu) || empty($jinsia) || empty($anapoishi) || empty($simu) || empty($kazi) || empty($picha) || empty($offices_id) || empty($users_id)) {
            return response()->json(['message' => 'Jaza sehemu zote zilizo wazi'], 401);
        }

        // Find the customer
        $customer = Customer::find($id);

        // Check if the customer exists
        if (!$customer) {
            return response()->json(['message' => 'Mteja hakupatikana'], 404);
        }

        // Check if $simu matches any other customer apart from this customer
        $existingSimu = Customer::where('simu', $simu)->where('id', '!=', $id)->first();
        if ($existingSimu) {
            return response()->json(['message' => 'Namba ya Simu inatumika na mteja mwingine'], 409);
        }

        // Check if $jina matches any other customer apart from this customer
        $existingJina = Customer::where('jina', $jina)->where('id', '!=', $id)->first();
        if ($existingJina) {
            return response()->json(['message' => 'Jina linatumika na mteja mwingine'], 409);
        }

        // Handle image deletion
        $imagePath = $customer->picha;
        $filePathImage = trim(str_replace('https://database.co.tz/', '', $imagePath));
        $filePath = public_path($filePathImage);

        if (file_exists($filePath)) {
            unlink($filePath);
        }

        // Update customer details
        $customer->user_id = $users_id;
        $customer->jina = $jina;
        $customer->jinaMaarufu = $jinaMaarufu;
        $customer->jinsia = $jinsia;
        $customer->anapoishi = $anapoishi;
        $customer->simu = $simu;
        $customer->kazi = $kazi;
        $customer->picha = $picha;
        $customer->office_id = $offices_id;
        $customer->save();

        // Return success response
        return response()->json(['message' => 'Mteja amebadilishiwa taarifa kikamilifu'], 200);
    }
}
