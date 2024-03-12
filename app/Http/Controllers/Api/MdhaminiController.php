<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoremdhaminiRequest;
use Illuminate\Http\Request;

class MdhaminiController extends Controller
{
    public function storeCustomer(StoremdhaminiRequest $request){

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

           
                // $customer = Customer::query()->where("jina", $jina)->first();
                
                // if (!$customer) {
                //     // $customer = Customer::create([
                //     //     'jina' => $jina,
                //     //     'jinaMaarufu' => $jinaMaarufu,
                //     //     'jinsia' => $jinsia,
                //     //     'anapoishi' => $anapoishi,
                //     //     'simu' => $simu,
                //     //     'kazi' => $kazi,
                //     //     'picha' => $picha,
                //     //     'offices_id' => $offices_id,
                //     //     'users_id' => $users_id,
                //     // ]);

                //     return response()->json(['message' => 'Akaunti Imetengenezwa'], 200);
                // } else {
                //     return response()->json(['message' => 'Tayari jina limeshasajiliwa.'], 401);
                // }
           
        }

    }
}
