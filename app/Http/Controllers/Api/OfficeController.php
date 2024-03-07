<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreofficeRequest;
use App\Models\Office;

class OfficeController extends Controller
{
    public function storeOffice(StoreofficeRequest $request){

        $jina = $request->jina;
        $asilimiaMkopo = $request->asilimiaMkopo;
        $asilimiaFomu = $request->asilimiaFomu;


        if (empty($jina) || empty($asilimiaMkopo) || empty($asilimiaFomu)) {
            return response()->json(['message' => 'Jaza sehemu zote zilizo wazi'], 401);
        } else {
                $office = Office::query()->where("jina", $jina)->first();
                
                if (!$office) {
                    $office = Office::create([
                        'jina' => $jina,
                        'asilimiaMkopo' => $asilimiaMkopo,
                        'asilimiaFomu' => $asilimiaFomu,
                    ]);

                    return response()->json(['message' => 'Ofisi Imetengenezwa'], 200);
                } else {
                    return response()->json(['message' => 'Tayari jina limeshasajiliwa.'], 401);
                }
        }

    }
}
