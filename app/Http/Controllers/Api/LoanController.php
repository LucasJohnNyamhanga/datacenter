<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreloanRequest;
use App\Models\Loan;

class LoanController extends Controller
{
    public function storeLoan(StoreloanRequest $request)
    {

        $siku = $request->siku;
        $kiasi = $request->kiasi;
        $customer_id = $request->customerId;

        $loan = Loan::create([
            'siku' => $siku,
            'kiasi' => $kiasi,
            'customer_id' => $customer_id,
            'hali' => false,
            'kasoro' => false,
            'maelezo' => 'Hakuna Tatizo',
            'njeMuda' => false,
            'mpya' => true,
        ]);

        
            return response()->json(['message' => 'Mkopo Umesajiliwa'], 200);
       
    }
}
