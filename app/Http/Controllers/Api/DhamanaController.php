<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\TaifishaDhamana;
use App\Models\Dhamana;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\StoreDhamanaRequest;
use App\Http\Requests\DeleteDhamanaRequest;

class DhamanaController extends Controller
{
     public function storeDhamana(StoreDhamanaRequest $request)
    {
        $jina = $request->jina;
        $thamani = $request->thamani;
        $maelezo = $request->maelezo;
        $picha = $request->picha;
        $mkopoId = $request->loansId;
        $mtejaId = $request->customerId;
        $officeId = $request->officeId;

        if (empty($jina) || empty($thamani) || empty($maelezo) || empty($picha)) {
            return response()->json(['message' => 'Jaza sehemu zote zilizo wazi'], 401);
        } else {

                $mdhamini = Dhamana::create([
                    'jina' => $jina,
                    'thamani' => $thamani,
                    'maelezo' => $maelezo,
                    'picha' => $picha,
                    'loan_id' => $mkopoId,
                    'customer_id' => $mtejaId,
                    'office_id' => $officeId,
                ]);

                return response()->json(['message' => 'Dhamana Imesajiliwa'], 200);
            
        }

    }

    public function deleteDhamana(DeleteDhamanaRequest $request)
    {
        $id = $request->id;
        $record = Dhamana::find($id);

        if ($record) {
            $imagePath = $record->picha;
            $filePathImage = trim(str_replace('https://database.co.tz/', '', $imagePath));
            $filePath = public_path($filePathImage);

            if (file_exists($filePath)) {
                unlink($filePath);
                } 
            $record->delete();
            return response()->json(['message' => 'Dhamana Imefutwa'], 200);
        } else {
            return response()->json(['message' => 'Dhamana haipo.'], 404);
        }
    }

     public function taifishaDhamana(TaifishaDhamana $request)
    {
        $id = $request->id;
        $dhamana = Dhamana::find($id);

        if ($dhamana) {
            $dhamana->imetaifishwa = true;

            $dhamana->save();
            return response()->json(['message' => 'Dhamana imetaifishwa.'], 200);

        }else{return response()->json(['message' => 'Dhamana haijapatikana.'], 401);}

        
    }
}
