<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorefainiRequest;
use App\Models\Customer;
use App\Models\Faini;
use App\Models\Mapato;
use Illuminate\Http\Request;

class FainiController extends Controller
{
    public function storeFaini(StorefainiRequest $request)
    {

        $kiasi = $request->kiasi;
        $userId = $request->userId;
        $loanId = $request->loanId;
        $customerId = $request->customerId;
        $njia = $request->njia;

        $faini = Faini::create([
            'kiasi' => $kiasi,
            'user_id' => $userId,
            'loan_id' => $loanId,
            'customer_id' => $customerId,
        ]);

        $mteja = Customer::find($customerId);

        $mapato = Mapato::create([
                    'kiasi' => $kiasi,
                    'njia' => $njia,
                    'aina' => 'Faini',
                    'maelezo' => 'Faini ya '.$mteja->jina,
                    'office_id' => $mteja->office_id,
                    'user_id' => $userId,
                    'rejeshoId' => $faini->id,
                    'rejesho' => true,
                ]);
        
            return response()->json(['message' => 'Faini Imesajiliwa'], 200);
    }
}
