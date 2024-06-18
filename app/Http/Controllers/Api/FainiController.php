<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorefainiRequest;
use App\Models\Faini;
use Illuminate\Http\Request;

class FainiController extends Controller
{
    public function storeFaini(StorefainiRequest $request)
    {

        $kiasi = $request->kiasi;
        $userId = $request->userId;
        $loanId = $request->loanId;
        $customerId = $request->customerId;

        $loan = Faini::create([
            'kiasi' => $kiasi,
            'user_id' => $userId,
            'loan_id' => $loanId,
            'customer_id' => $customerId,
        ]);
        
            return response()->json(['message' => 'Faini Imesajiliwa'], 200);
    }
}
