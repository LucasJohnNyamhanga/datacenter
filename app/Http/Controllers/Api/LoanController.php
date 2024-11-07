<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\KasoroRequest;
use App\Http\Requests\PitishaMkopoRequest;
use App\Http\Requests\StoreloanRequest;
use App\Models\Customer;
use App\Models\Loan;
use Carbon\Carbon;

class LoanController extends Controller
{
    public function storeLoan(StoreloanRequest $request)
    {

        $siku = $request->siku;
        $kiasi = $request->kiasi;
        $customer_id = $request->customerId;
        $riba = $request->riba;
        $fomu = $request->fomu;

        $customer = Customer::with(['loan'])
        ->where('id', $customer_id)
        ->whereHas('loan', function ($query) {
            $query->where('mpya', true)->orWhere('hali', true);
        })
        ->first();

        // Check if the customer has an active loan
        if ($customer) {
            return response()->json(['message' => 'Mteja tayari anamkopo unaoendelea'], 401);
        }

        // Create a new loan for the customer
        $loan = Loan::create([
            'siku' => $siku,
            'kiasi' => $kiasi,
            'customer_id' => $customer_id,
            'hali' => false,
            'kasoro' => false,
            'maelezo' => '',
            'njeMuda' => false,
            'mpya' => true,
            'riba' => $riba,
            'form' => $fomu,
        ]);

        return response()->json(['message' => 'Mkopo Umesajiliwa'], 200);
    }

    public function wekaKasoroMkopo(KasoroRequest $request)
    {
        $id = $request->id;
        $maelezo = $request->maelezo;

        $loan = Loan::find($id);
        $loan->maelezo = $maelezo;
        $loan->kasoro = true;
        $loan->mpya = false;
    
        $loan->save();

        return response()->json(['message' => 'Kasoro imewekwa kwenye Mkopo'], 200);
    }

    public function pitishaMkopo(PitishaMkopoRequest $request)
    {
        $id = $request->id;
        $rejesho = $request->rejesho;
        $riba = $request->riba;
        $fomu = $request->fomu;

        $loan = Loan::find($id);
        $loan->maelezo = '';
        $loan->kasoro = false;
        $loan->mpya = false;
        $loan->hali = true;
        $loan->rejesho = $rejesho;
        $loan->riba = $riba;
        $loan->form = $fomu;
        $loan->mwanzoMkopo = Carbon::now();
    
        $loan->save();

        return response()->json(['message' => 'Mkopo Umepitishwa'], 200);
    }

    public function badiliTaarifaMkopo(PitishaMkopoRequest $request)
    {
        $id = $request->loanId;
        $kiasi = $request->kiasi;
        $siku = $request->siku;
        $riba = $request->riba;
        $fomu = $request->fomu;

        $loan = Loan::find($id);
        $loan->kiasi = $kiasi;
        $loan->siku = $siku;
        $loan->riba = $riba;
        $loan->form = $fomu;
        $loan->save();

        return response()->json(['message' => 'Mkopo Umebadilishwa Taarifa.'], 200);
    }
}
