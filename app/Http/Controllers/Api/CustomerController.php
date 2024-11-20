<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\GetNewCustomerRequest;
use App\Http\Requests\MtejaDetailRequest;
use App\Http\Requests\StorecustomerRequest;
use App\Http\Requests\UpdateAfisaMkopoCustomer;
use App\Http\Requests\WatejaWanaofananaRequest;
use App\Models\Customer;
use App\Models\Loan;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class CustomerController extends Controller
{
    public function storeCustomer(StorecustomerRequest $request)
    {
        $validator = Validator::make($request->all(), [
            'jina' => 'required|string|max:255',
            'jinaMaarufu' => 'required|string',
            'jinsia' => 'required|string',
            'anapoishi' => 'required|string',
            'simu' => 'required|string',
            'kazi' => 'required|string',
            'picha' => 'required|string|max:255',
            'officeId' => 'required|integer',
            'userId' => 'required|integer',
            'siku' => 'required|integer',
            'kiasi' => 'required|integer',
            'riba' => 'required|integer',
            'fomu' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Jaza nafasi zote zilizo wazi', 'errors' => $validator->errors()], 400);
        }

        DB::beginTransaction();

        try {
            $customer = Customer::query()
                ->where("jina", $request->input('jina'))
                ->orWhere('simu', '=', $request->input('simu'))
                ->first();

            if (!$customer) {
                $customer = Customer::create([
                    'jina' => $request->input('jina'),
                    'jinaMaarufu' => $request->input('jinaMaarufu'),
                    'jinsia' => $request->input('jinsia'),
                    'anapoishi' => $request->input('anapoishi'),
                    'simu' => $request->input('simu'),
                    'kazi' => $request->input('kazi'),
                    'picha' => $request->input('picha'),
                    'office_id' => $request->input('officeId'),
                    'user_id' => $request->input('userId'),
                ]);

                Loan::create([
                    'siku' => $request->input('siku'),
                    'kiasi' => $request->input('kiasi'),
                    'customer_id' => $customer->id,
                    'hali' => false,
                    'kasoro' => false,
                    'maelezo' => '',
                    'njeMuda' => false,
                    'mpya' => true,
                    'riba' => $request->input('riba'),
                    'fomu' => $request->input('fomu'),
                ]);

                DB::commit();

                return response()->json(['message' => 'Mteja amesajiliwa kikamilifu, taarifa zake zipo kwenye Maombi mapya.'], 200);
            } else {
                return response()->json(['message' => 'Mteja hawezi kusajiliwa, taarifa zake zina mteja mwingine.'], 401);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Kuna tatizo limetokea wakati wa kumsajili mteja, usajili umesitishwa.'], 500);
        }
    }


    public function getNewCustomer(GetNewCustomerRequest $request)
    {
        $officeId = $request->officeId;
        $customer = Customer::with('user','loan')->whereRelation('loan', 'mpya', '=', true)
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
                $query->where('id', $loanId)
                ->latest()
                ->take(1);
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
            'user',
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
        $customer = Customer::with('user','loan')->whereRelation('loan', 'kasoro', '=', true)
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

        $customers = Customer::with([
        'user',
        'loan' => function ($query) {
            $query->latest('created_at')->take(1); // Fetch the latest loan
        },
        'mdhamini',
        'dhamana',
        'marejesho'
        ])
        ->where('office_id', '=', $officeId) // Filter by office
        ->whereHas('loan', function ($query) {
            $query->where('hali', true); // Filter loans with 'hali' as true
        })
        ->orderBy(function ($query) {
            // Order customers by the latest loan's 'created_at'
            return $query->select('created_at')
                ->from('loans')
                ->whereColumn('loans.customer_id', 'customers.id')
                ->orderBy('created_at', 'desc')
                ->limit(1);
        }, 'desc')
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
        $customer = Customer::with(['user','loan' => function ($query) {
                $query->latest()->take(1);
            }, 'mdhamini', 'dhamana', 'marejesho'])
            ->whereRelation('loan', 'hali', '=', true)
            ->where(function($query) use ($jina, $namba) {
                $query->where('jina', 'LIKE', '%' . $jina . '%')
                ->orWhere('simu', 'LIKE', '%' . $namba . '%');
            })
        ->get();
        return response()->json(['data' => $customer,], 200);
    }

    public function getMteja(GetNewCustomerRequest $request)
    {
        //$officeId = $request->officeId;
        $jina = $request->jina;

        // Fetch customers with their relationships and filter based on conditions
        $customers = Customer::with(['user','loan' => function ($query) {
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

        $customers = Customer::with([
                'user', 
                'mdhamini',
                'dhamana',
                'marejesho',
                'loan' => function ($query) {
                    $query->latest()->take(1); // Only load the latest loan for display
                }
            ])
            ->where('office_id', $officeId)
            ->whereHas('loan', function ($query) {
                $query->where('mpya', false)
                    ->where('kasoro', false)
                    ->where('hali', false)
                    ->where('created_at', function ($subquery) {
                        $subquery->selectRaw('MAX(created_at)')
                                ->from('loans')
                                ->whereColumn('loans.customer_id', 'customers.id');
                    });
            })
            ->get();

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
        $baseUrl = env('APP_URL');
        $imagePath = $customer->picha;
        $filePathImage = trim(str_replace($baseUrl, '', $imagePath));
        $filePath = public_path($filePathImage);

        if (file_exists($filePath)) {
            unlink($filePath);
        }

        // Update customer detailsS
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
