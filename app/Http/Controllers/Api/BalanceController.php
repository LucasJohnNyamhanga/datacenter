<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\HesabuKuuRequest;
use App\Http\Requests\StoreBalanceRequest;
use App\Models\Balance;
use App\Models\Office;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class BalanceController extends Controller
{
    public function storeHesabuKuu(StoreBalanceRequest $request)
    {

        function isSameDayIgnoringTime($dateTime1, $dateTime2) {
            $date1 = Carbon::parse($dateTime1)->format('Y-m-d');
            $date2 = Carbon::parse($dateTime2)->format('Y-m-d');
            return $date1 === $date2;
        }

        $kiasiSystem = $request->kiasiSystem;
        $kiasiUser = $request->kiasiUser;
        $aina = $request->aina;
        $maelezo = $request->maelezo;
        $office_id = $request->officeId;
        $user_id = $request->userId;
        $tareheHesabuIliyopita = Carbon::parse($request->tareheHesabuIliyopita);
        $hesabuIliyopita = $request->hesabuIliyopita;
        $tareheHesabu = Carbon::parse($request->tareheHesabu);

        if ( empty($kiasiUser) || empty($maelezo)) {
            return response()->json(['message' => 'Jaza sehemu zote zilizo wazi'], 401);
        } 

            $balance = Balance::latest()
            ->where('aina', $aina)
            ->whereRelation('office', 'office_id', $office_id)
            ->first();

            
        if ($balance && !isSameDayIgnoringTime($balance->tareheHesabu, $tareheHesabu)) {
                $newBalance = Balance::create([
                'kiasiSystem' => $kiasiSystem,
                'kiasiUser' => $kiasiUser,
                'aina' => $aina, 
                'maelezo' => $maelezo,
                'office_id' => $office_id,
                'user_id' => $user_id,
                'tareheHesabu' => $tareheHesabu,
                'tareheHesabuIliyopita' => $tareheHesabuIliyopita,
                'hesabuIliyopita' => $hesabuIliyopita,
            ]);
        return response()->json(['message' => 'hesabu imsesajiliwa.'], 200);
        } 
        
        if (!$balance) {
            $newBalance = Balance::create([
            'kiasiSystem' => $kiasiSystem,
            'kiasiUser' => $kiasiUser,
            'aina' => $aina, 
            'maelezo' => $maelezo,
            'office_id' => $office_id,
            'user_id' => $user_id,
            'tareheHesabu' => $tareheHesabu,
            'tareheHesabuIliyopita' => Carbon::create(2024,5,1),
            'hesabuIliyopita' => $hesabuIliyopita,
            ]);
            return response()->json(['message' => 'Hesabu mpya imeundwa.'], 200);
        }
    }

    public function getMahesabu(HesabuKuuRequest $request)
    {
        $ofisiId = $request->ofisiId;
        $latestBalance = [];
        $categories = ['PESA', 'MPESA', 'AIRTELMONEY', 'TIGOPESA'];
        $mudaHesabu = '';
        $mudaHesabuIliyopita = '';

    // Retrieve latest balances for each category
        foreach ($categories as $category) {
            $balance = Balance::latest()
                ->where('aina', $category)
                ->whereRelation('office', 'office_id', $ofisiId)
                ->first();

            if ($balance) {
                $latestBalance[] = $balance;
                $mudaHesabu = $balance->tareheHesabu;
                $mudaHesabuIliyopita = $balance->tareheHesabuIliyopita; 
            }
        }

        // Data ndani ya hesabu
        $officeNdaniHesabu = Office::with([
            'mapato' => function ($query) use ($mudaHesabuIliyopita, $mudaHesabu) {
                // Filter incomes based on the latest timestamp
                $query->whereBetween('created_at', [$mudaHesabuIliyopita, $mudaHesabu]);
            },
            'matumizi' => function ($query) use ($mudaHesabuIliyopita, $mudaHesabu) {
                // Filter expenses based on the latest timestamp
                $query->whereBetween('created_at', [$mudaHesabuIliyopita, $mudaHesabu]);
            },'user'
        ])->where('id',$ofisiId)->get(); 

        // Data baada ya hesabu
        $officeNjeHesabu = Office::with([
            'mapato' => function ($query) use ($mudaHesabu) {
                // Filter incomes based on the latest timestamp
                $query->whereBetween('created_at', [$mudaHesabu, Carbon::now()]);
            },
            'matumizi' => function ($query) use ($mudaHesabu) {
                // Filter expenses based on the latest timestamp
                $query->whereBetween('created_at', [$mudaHesabu, Carbon::now()]);
            },'user'
        ])->where('id',$ofisiId)->get(); 

        $time = Carbon::now();
        return response()->json(['hesabu' => $latestBalance, 'muda' => $time, 'ndaniHesabu' => $officeNdaniHesabu,  'njeHesabu' => $officeNjeHesabu], 200);
    }

    public function getMahesabuWithDate(HesabuKuuRequest $request)
    {
        $ofisiId = $request->ofisiId;
        $tarehe = Carbon::parse($request->tarehe);
        $latestBalance = [];
        $categories = ['PESA', 'MPESA', 'AIRTELMONEY', 'TIGOPESA'];
        $mudaHesabu = '';
        $mudaHesabuIliyopita = '';

    // Retrieve latest balances for each category
        foreach ($categories as $category) {
            $balance = Balance::latest()
                ->where('aina', $category)
                ->whereDay('tareheHesabu', $tarehe)
                ->whereRelation('office', 'office_id', $ofisiId)
                ->first();

            if ($balance) {
                $latestBalance[] = $balance;
                $mudaHesabu = $balance->tareheHesabu;
                $mudaHesabuIliyopita = $balance->tareheHesabuIliyopita; 
            }
        }

        // Data ndani ya hesabu
        $officeNdaniHesabu = Office::with([
            'mapato' => function ($query) use ($mudaHesabuIliyopita, $mudaHesabu) {
                // Filter incomes based on the latest timestamp
                $query->whereBetween('created_at', [$mudaHesabuIliyopita, $mudaHesabu]);
            },
            'matumizi' => function ($query) use ($mudaHesabuIliyopita, $mudaHesabu) {
                // Filter expenses based on the latest timestamp
                $query->whereBetween('created_at', [$mudaHesabuIliyopita, $mudaHesabu]);
            },'user'
        ])->where('id',$ofisiId)->get(); 

        // Data baada ya hesabu
        $officeNjeHesabu = Office::with([
            'mapato' => function ($query) use ($mudaHesabu) {
                // Filter incomes based on the latest timestamp
                $query->whereBetween('created_at', [$mudaHesabu, Carbon::now()]);
            },
            'matumizi' => function ($query) use ($mudaHesabu) {
                // Filter expenses based on the latest timestamp
                $query->whereBetween('created_at', [$mudaHesabu, Carbon::now()]);
            },'user'
        ])->where('id',$ofisiId)->get(); 

        $time = Carbon::now();
        return response()->json(['hesabu' => $latestBalance, 'muda' => $time, 'ndaniHesabu' => $officeNdaniHesabu,  'njeHesabu' => $officeNjeHesabu], 200);
    }

    public function deleteHesabu(HesabuKuuRequest $request)
    {
        $id = $request->id; // Get the input
        $customer = Balance::find($id);
        if ($customer) {
            $customer->delete();
        }

        return response()->json(['message' => 'Zimefutwa.'], 200);
    }

}
