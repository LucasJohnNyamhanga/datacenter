<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\getOfficeWithBalance;
use App\Http\Requests\StoreofficeRequest;
use App\Models\Balance;
use App\Models\Office;
use Illuminate\Support\Facades\Validator;

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

    public function editOfisi(StoreofficeRequest $request)
    {
    
        $validator = Validator::make($request->all(), [
            'jina' => 'required|string|max:255',
            'kitengoId' => 'required|integer',
            'asilimiaFomu' => 'required|integer',
            'asilimiaRiba' => 'required|integer',
            'id' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Jaza nafasi zote zilizowazi', 'errors' => $validator->errors()], 400);
        }

        $jina = $request->input('jina');
        $kitengoId = $request->input('kitengoId');
        $asilimiaFomu = $request->input('asilimiaFomu');
        $asilimiaRiba = $request->input('asilimiaRiba');
        $id = $request->input('id');

        // Retrieve the package by its ID
        $office = Office::find($id);

        if (!$office) {
            return response()->json(['message' => 'Ofisi hii imeshaondolewa.'], 404);
        }

        // Check for existing package with the same title (excluding the current package being edited)
        $existingOfisi = Office::where('jina', $jina)->where('id', '!=', $id)->first();
        if ($existingOfisi) {
            return response()->json(['message' => 'Kuna ofisi nyingine inatumia jina hili.'], 409);
        }

        // Update the existing package
        $office->update([
            'jina' => $jina,
            'asilimiaMkopo' => $asilimiaRiba,
            'asilimiaFomu' => $asilimiaFomu,
            'department_id' => $kitengoId,
        ]);

        return response()->json(['message' => 'Ofisi imebadilishiwa taarifa.'], 200);
    }
}
