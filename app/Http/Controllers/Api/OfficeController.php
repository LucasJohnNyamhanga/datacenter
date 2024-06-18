<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\getOfficeWithBalance;
use App\Http\Requests\StoreofficeRequest;
use App\Models\Balance;
use App\Models\Office;

class OfficeController extends Controller
{

    public function getOffice()
    {
    $office = Office::all();
            
        if($office -> count() > 0){
            $data = [
                        'status' => 200,
                        'data' => $office
                    ];
            return response()->json($data, 200);
        }else{
            $data = [
            'status' => 404,
            'data' => 'Hakuna ofisi iliyopatikana!.'
        ];
            return response()->json($data, 404);
        }
        
    }
    public function storeOffice(StoreofficeRequest $request){

        $jina = $request->jina;
        $asilimiaMkopo = $request->asilimiaRiba;
        $asilimiaFomu = $request->asilimiaFomu;
        $kitengoId = $request->kitengoId;


        if (empty($jina) || empty($asilimiaMkopo) || empty($asilimiaFomu)) {
            return response()->json(['message' => 'Jaza sehemu zote zilizo wazi'], 401);
        } else {
                $office = Office::query()->where("jina", $jina)->first();
                
                if (!$office) {
                    $office = Office::create([
                        'jina' => $jina,
                        'asilimiaMkopo' => $asilimiaMkopo,
                        'asilimiaFomu' => $asilimiaFomu,
                        'department_id' => $kitengoId,
                    ]);

                    return response()->json(['message' => 'Ofisi Imetengenezwa'], 200);
                } else {
                    return response()->json(['message' => 'Tayari jina limeshasajiliwa.'], 401);
                }
        }

    }

    public function getOfficeWithBalance(getOfficeWithBalance $request)
    {
        $categories = ['PESA', 'MPESA', 'AIRTELMONEY', 'TIGOPESA'];
        $department_id = $request->departmentId;

        // Retrieve the offices for the given department
        $offices = Office::where('department_id', $department_id)->get();

        // Loop through each office to get balances for each category
        foreach ($offices as $office) {
            $balances = [];
            foreach ($categories as $category) {
                $balance = $office->balance()->where('aina', $category)->latest()->first();
                if ($balance) {
                    $balances[] = $balance;
                }
            }
            $office->balances = $balances;
        }

        return response()->json(['data' => $offices], 200);
        
    }
}
