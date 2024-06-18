<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorerejeshoRequest;
use App\Models\Customer;
use App\Models\Loan;
use App\Models\Mapato;
use App\Models\Rejesho;
use App\Models\User;

class RejeshoController extends Controller
{
    
    public function getBakiSalio(StorerejeshoRequest $request)
    {

        $paid = $request->input('paid');
        $customerId = $request->input('customerId');
        $loanId = $request->input('loanId');
        $userId = $request->input('userId');
        $njia = $request->input('njia');

        $rejesho = Rejesho::create([
            'kiasi' => $paid,
            'user_id' => $userId,
            'loan_id' => $loanId,
            'customer_id' => $customerId,
        ]);

        $mteja = Customer::find($customerId);

        $mapato = Mapato::create([
                    'kiasi' => $paid,
                    'njia' => $njia,
                    'aina' => 'Rejesho',
                    'maelezo' => 'Rejesho la '.$mteja->jina,
                    'office_id' => $mteja->office_id,
                    'user_id' => $userId,
                    'rejeshoId' => $rejesho->id,
                    'rejesho' => true,
                ]);

        $totalRepayments = Rejesho::where('loan_id', $loanId)
                                ->where('customer_id', $customerId)
                                ->sum('kiasi');

        $loan = Loan::find($loanId);

        if (!$loan) {
            return response()->json(['message' => 'Loan not found'], 404);
        }

        $loanAmount = $loan->kiasi;
        $riba = $loan->riba;
        $ribaAmount = ($loanAmount * ($riba / 100));
        $totalLoanWithInterest = $loanAmount + $ribaAmount;

        $baki = $totalLoanWithInterest - $totalRepayments;

        return response()->json(['message' => $baki], 200);
        
    }
    
}