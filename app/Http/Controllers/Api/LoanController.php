<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreloanRequest;
use App\Models\Loan;

class LoanController extends Controller
{
     public function storeLoan(StoreloanRequest $request){

        $aina = $request->aina;
        $kiasi = $request->kiasi;
        $customers_id = $request->customers_id;
        $hali = $request->hali;
        $kasoro = $request->kasoro;
        $maelezo = $request->maelezo;
        $njeMuda = $request->njeMuda;


        if (empty($aina) || empty($kiasi) || empty($customers_id) || empty($hali)|| empty($kasoro)|| empty($maelezo)|| empty($njeMuda)) {
            return response()->json(['message' => 'Jaza sehemu zote zilizo wazi'], 401);
        } else {

                $loan = Loan::create([
                        'aina' => $aina,
                        'kiasi' => $kiasi,
                        'customers_id' => $customers_id,
                        'hali' => $hali,
                        'kasoro' => $kasoro,
                        'maelezo' => $maelezo,
                        'njeMuda' => $njeMuda,
                    ]);
                
                if (!$loan) {
                    return response()->json(['message' => 'Mkopo Umesajiliwa'], 200);
                } else {
                    return response()->json(['message' => 'Kuna tatizo lililo nje ya uwezo.'], 401);
                }
        }

    }
}
