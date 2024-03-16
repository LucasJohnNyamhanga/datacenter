<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\GetNewCustomerRequest;
use App\Http\Requests\MtejaDetailRequest;
use App\Http\Requests\StorecustomerRequest;
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
                $customer = Customer::query()->where("jina", $jina)->first();
                
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
                    return response()->json(['message' => 'Tayari jina limeshasajiliwa.'], 401);
                }
        }
    }

    public function getNewCustomer(GetNewCustomerRequest $request)
    {
        $customer = Customer::with('loan')->whereRelation('loan', 'mpya', '=', true)->paginate(10);
        return response()->json($customer, 200);
    }

     public function getNewCustomerDetails(MtejaDetailRequest $request)
    {
        $id = $request->id;
        $customer = Customer::with(['loan','mdhamini','dhamana'])->where('id','=',$id)->whereRelation('loan', 'mpya', '=', true)->first();

       
       
            return response()->json(['message' => $customer], 200);
       
    }

    
}
