<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\MatumiziRequest;
use App\Http\Requests\StorematumiziRequest;
use App\Models\Mapato;
use App\Models\Matumizi;
use Carbon\Carbon;

class MatumiziController extends Controller
{
     public function storeMatumizi(StorematumiziRequest $request)
    {
        $kiasi = $request->kiasi;
        $njia = $request->njia;
        $aina = $request->aina;
        $maelezo = $request->maelezo;
        $officeId = $request->officeId;
        $userId = $request->userId;
        $isSystem = $request->isSystem;

        if (empty($kiasi) || empty($njia) || empty($aina) || empty($maelezo)) {
            return response()->json(['message' => 'Jaza sehemu zote zilizo wazi'], 401);
        } else {

                Matumizi::create([
                    'kiasi' => $kiasi,
                    'njia' => $njia,
                    'aina' => $aina,
                    'maelezo' => $maelezo,
                    'office_id' => $officeId,
                    'user_id' => $userId,
                    'isSystem'=> $isSystem,
                ]);
                return response()->json(['message' => 'Matumizi Yamesajiliwa'], 200);
        
        }

    }


    public function getMatumizi(MatumiziRequest $request){

        $officeId = $request->officeId;

        $matumizi = Matumizi::whereDate('created_at', Carbon::today())
        ->where('office_id','=',$officeId)
        ->get();
        return response()->json(['data' => $matumizi], 200);
    }

    public function getMatumiziWithDate(MatumiziRequest $request){
        $officeId = $request->officeId;
        $tarehe = Carbon::parse($request->tarehe);

        $matumizi = Matumizi::whereDay('created_at', $tarehe)
        ->where('office_id','=',$officeId)
        ->get();
        return response()->json(['data' => $matumizi], 200);
    }

    public function getMatumiziWithTwoDate(MatumiziRequest $request)
    {
        $officeId = $request->officeId;
        $dateStart = Carbon::parse($request->dateStart);
        $dateEnd = Carbon::parse($request->dateEnd);

        $matumizi = Matumizi::whereBetween('created_at', [$dateStart, $dateEnd])
            ->where('office_id', '=', $officeId)
            ->get();

        return response()->json(['data' => $matumizi], 200);
    }


    public function sahihishaMatumizi(MatumiziRequest $request)
    {
        $kiasi = $request->kiasi;
        $njia = $request->njia;
        $aina = $request->aina;
        $maelezo = $request->maelezo;
        $id = $request->id;

        $matumizi = Matumizi::find($id);
        $matumizi->kiasiCopy = $kiasi;
        $matumizi->njiaCopy = $njia;
        $matumizi->ainaCopy = $aina;
        $matumizi->maelezoCopy = $maelezo;
        $matumizi->badili = true;
        $matumizi->futa = false;
    
        $matumizi->save();

        return response()->json(['message' => 'Matumizi  Yamebadilishwa'], 200);
    }

    public function futaMatumizi(MatumiziRequest $request)
    {
        
        $id = $request->id;

        $matumizi = Matumizi::find($id);
        $matumizi->badili = false;
        $matumizi->futa = true;
    
        $matumizi->save();

        return response()->json(['message' => 'Matumizi  Yamebadilishwa'], 200);
    }

    public function getMarekebisho(MatumiziRequest $request)
    {
        $officeId = $request->officeId;

        $matumizi = Matumizi::where('office_id','=',$officeId)
        ->where('futa','=',true)
        ->orWhere('badili','=',true)
        ->get();

        $mapato = Mapato::where('office_id','=',$officeId)
        ->where('futa','=',true)
        ->orwhere('badili','=',true)
        ->get();

        return response()->json(['matumizi' => $matumizi,'mapato' => $mapato], 200);
    }

    public function getMenejaMarekebisho(MatumiziRequest $request)
    {
        $officeId = $request->officeId;

        $matumizi = Matumizi::where('office_id', '=', $officeId)
            ->whereDate('created_at', Carbon::today())
            ->where(function ($query) {
                $query->where('futa', '=', true)
                    ->orWhere('badili', '=', true);
            })
            ->get();

        $mapato = Mapato::where('office_id', '=', $officeId)
            ->whereDate('created_at', Carbon::today())
            ->where(function ($query) {
            $query->where('futa', '=', true)
                ->orWhere('badili', '=', true);
            })
        ->get();

        return response()->json(['matumizi' => $matumizi, 'mapato' => $mapato], 200);
    }

    public function futaTumiziMojaKwaMoja(MatumiziRequest $request)
    {
        
        $id = $request->id;

        $matumizi = Matumizi::find($id);
        $matumizi->delete();

        return response()->json(['message' => 'Tumizi  Limefutwa'], 200);
    }

     public function hapanaMabadilikoMatumizi(MatumiziRequest $request)
    {
        
        $id = $request->id;

        $matumizi = Matumizi::find($id);
        $matumizi->kiasiCopy = 0;
        $matumizi->njiaCopy = '';
        $matumizi->ainaCopy = '';
        $matumizi->maelezoCopy = '';
        $matumizi->badili = false;
        $matumizi->futa = false;
    
        $matumizi->save();

        return response()->json(['message' => 'Tumizi  Limehairishwa kufanyiwa mabadiliko.'], 200);
    }

    public function rekebishaMabadilikoMatumizi(MatumiziRequest $request)
    {
        
        $id = $request->id;

        $matumizi = Matumizi::find($id);

        $kiasiCopy = $matumizi->kiasiCopy;
        $njiaCopy = $matumizi->njiaCopy;
        $ainaCopy = $matumizi->ainaCopy;
        $maelezoCopy = $matumizi->maelezoCopy;

        $matumizi->kiasi = $kiasiCopy;
        $matumizi->njia = $njiaCopy;
        $matumizi->aina = $ainaCopy;
        $matumizi->maelezo = $maelezoCopy;

        $matumizi->kiasiCopy = 0;
        $matumizi->njiaCopy = '';
        $matumizi->ainaCopy = '';
        $matumizi->maelezoCopy = '';
        $matumizi->badili = false;
        $matumizi->futa = false;
    
        $matumizi->save();

        return response()->json(['message' => 'Tumizi  Limehairishwa kufanyiwa mabadiliko.'], 200);
    }
}