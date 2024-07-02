<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\MapatoRequest;
use App\Http\Requests\StoremapatoRequest;
use App\Models\Faini;
use App\Models\Mapato;
use App\Models\Rejesho;
use Carbon\Carbon;

class MapatoController extends Controller
{
    public function storeMapato(StoremapatoRequest $request)
    {
        $kiasi = $request->kiasi;
        $njia = $request->njia;
        $aina = $request->aina;
        $maelezo = $request->maelezo;
        $officeId = $request->officeId;
        $userId = $request->userId;
        $isRejesho = $request->isRejesho;
        $rejeshoId = $request->rejeshoId;

        if (empty($kiasi) || empty($njia) || empty($aina) || empty($maelezo)) {
            return response()->json(['message' => 'Jaza sehemu zote zilizo wazi'], 401);
        } else {

                $mapato = Mapato::create([
                    'kiasi' => $kiasi,
                    'njia' => $njia,
                    'aina' => $aina,
                    'maelezo' => $maelezo,
                    'office_id' => $officeId,
                    'user_id' => $userId,
                    'rejeshoId' => $rejeshoId,
                    'rejesho' => $isRejesho,
                ]);
                return response()->json(['message' => 'Mdhamini Kasajiliwa'], 200);
        
        }

    }



    public function getMapato(MapatoRequest $request){
        $officeId = $request->officeId;

        $mapato = Mapato::whereDate('created_at', Carbon::today())
        ->where('office_id','=',$officeId)
        ->get();
        return response()->json(['data' => $mapato], 200);
    }

    public function getMapatoWithDate(MapatoRequest $request){
        $officeId = $request->officeId;
        $filter = $request->filter;
        $tarehe = Carbon::parse($request->tarehe);

        if ($filter == 'Yote') {
            $mapato = Mapato::whereDay('created_at', $tarehe)
                ->where('office_id', '=', $officeId)
                ->get();
        }else{
            $mapato = Mapato::whereDay('created_at', $tarehe)
                ->where('office_id', '=', $officeId)
                ->where('aina', '=', $filter)
                ->get();
        }
        return response()->json(['data' => $mapato], 200);
    }


    public function sahihishaMapato(MapatoRequest $request)
    {
        $kiasi = $request->kiasi;
        $njia = $request->njia;
        $aina = $request->aina;
        $maelezo = $request->maelezo;
        $id = $request->id;

        $mapato = Mapato::find($id);
        $mapato->kiasiCopy = $kiasi;
        $mapato->njiaCopy = $njia;
        $mapato->ainaCopy = $aina;
        $mapato->maelezoCopy = $maelezo;
        $mapato->badili = true;
        $mapato->futa = false;
    
        $mapato->save();

        return response()->json(['message' => 'Mapato  Yamebadilishwa'], 200);
    }

    public function futaMapato(MapatoRequest $request)
    {
        
        $id = $request->id;

        $mapato = Mapato::find($id);
        $mapato->badili = false;
        $mapato->futa = true;
    
        $mapato->save();

        return response()->json(['message' => 'Matumizi  Yamebadilishwa'], 200);
    }

    public function futaPatoMojaKwaMoja(MapatoRequest $request)
    {
        
        $id = $request->id;

        $mapato = Mapato::find($id);

        if($mapato->aina == 'Rejesho'){
            $rejesho = Rejesho::find($mapato->rejeshoId);
            $rejesho->delete();
        }

        if($mapato->aina == 'Faini'){
            $faini = Faini::find($mapato->rejeshoId);
            $faini->delete();
        }

        $mapato->delete();

        return response()->json(['message' => 'Tumizi  Limefutwa'], 200);
    }

    public function hapanaMabadilikoMapato(MapatoRequest $request)
    {
        
        $id = $request->id;

        $mapato = Mapato::find($id);
        $mapato->kiasiCopy = 0;
        $mapato->njiaCopy = '';
        $mapato->ainaCopy = '';
        $mapato->maelezoCopy = '';
        $mapato->badili = false;
        $mapato->futa = false;
    
        $mapato->save();

        return response()->json(['message' => 'Tumizi  Limehairishwa kufanyiwa mabadiliko.'], 200);
    }

    public function rekebishaMabadilikoMapato(MapatoRequest $request)
    {
        
        $id = $request->id;

        $mapato = Mapato::find($id);

        $kiasiCopy = $mapato->kiasiCopy;
        $njiaCopy = $mapato->njiaCopy;
        $ainaCopy = $mapato->ainaCopy;
        $maelezoCopy = $mapato->maelezoCopy;

        //rekebisha faini
        //rekebisha rejesho
        if($mapato->aina == 'Rejesho'){
            $rejesho = Rejesho::find($mapato->rejeshoId);
            $rejesho->kiasi = $kiasiCopy;
            $rejesho->save();
        }

        if($mapato->aina == 'Faini'){
            $faini = Faini::find($mapato->rejeshoId);
            $faini->kiasi = $kiasiCopy;
            $faini->save();
        }

        $mapato->kiasi = $kiasiCopy;
        $mapato->njia = $njiaCopy;
        $mapato->aina = $ainaCopy;
        $mapato->maelezo = $maelezoCopy;

        $mapato->kiasiCopy = 0;
        $mapato->njiaCopy = '';
        $mapato->ainaCopy = '';
        $mapato->maelezoCopy = '';
        $mapato->badili = false;
        $mapato->futa = false;
    
        $mapato->save();

        return response()->json(['message' => 'Tumizi  Limehairishwa kufanyiwa mabadiliko.'], 200);
    }

    public function getMapatoWithTwoDate(MapatoRequest $request)
    {
        $officeId = $request->officeId;
        $filter = $request->filter;
        $dateStart = Carbon::parse($request->dateStart)->startOfDay();
        $dateEnd = Carbon::parse($request->dateEnd)->endOfDay();

        if($filter == 'Yote'){
            $mapato = Mapato::whereBetween('created_at', [$dateStart, $dateEnd])
                        ->where('office_id', '=', $officeId)
                        ->get();
        }else{
            $mapato = Mapato::whereBetween('created_at', [$dateStart, $dateEnd])
            ->where('office_id', '=', $officeId)
            ->where('aina', '=', $filter)
            ->get();
        }

        

        return response()->json(['data' => $mapato], 200);
    }
}
