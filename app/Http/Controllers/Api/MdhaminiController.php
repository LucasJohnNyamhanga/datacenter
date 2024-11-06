<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\DeleteRequest;
use App\Http\Requests\StoremdhaminiRequest;
use App\Models\Mdhamini;

class MdhaminiController extends Controller
{
    public function storeMdhamini(StoremdhaminiRequest $request)
    {
        $jina = $request->jina;
        $simu = $request->simu;
        $mahusiano = $request->mahusiano;
        $anapoishi = $request->anapoishi;
        $picha = $request->picha;
        $loans_id = $request->loansId;
        $customer_id = $request->customerId;

        if (empty($jina) || empty($mahusiano) || empty($anapoishi) || empty($picha)) {
            return response()->json(['message' => 'Jaza sehemu zote zilizo wazi'], 401);
        } else {

            $mdhaminiCheck = Mdhamini::query()->where("jina", $jina)->first();

            if (!$mdhaminiCheck) {
                $mdhamini = Mdhamini::create([
                    'jina' => $jina,
                    'simu' => $simu,
                    'mahusiano' => $mahusiano,
                    'anapoishi' => $anapoishi,
                    'picha' => $picha,
                    'loan_id' => $loans_id,
                    'customer_id' => $customer_id,
                ]);

                return response()->json(['message' => 'Mdhamini Kasajiliwa'], 200);
            } else {
                return response()->json(['message' => 'Tayari jina La Mdhamini Limeshasajiliwa.'], 401);
            }
        }

    }



    public function deleteMdhamini(DeleteRequest $request)
    {
        $id = $request->id;
        $record = Mdhamini::find($id);
        $baseUrl = env('APP_URL');

        if ($record) {
            $imagePath = $record->picha;
            
            $filePathImage = trim(str_replace($baseUrl, '', $imagePath));
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
}
