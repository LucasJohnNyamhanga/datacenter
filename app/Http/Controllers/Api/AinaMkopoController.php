<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AinaMkopoRequest;
use App\Models\Aina;
use App\Models\Office;
use Illuminate\Support\Facades\Validator;

class AinaMkopoController extends Controller
{
    public function storeAinaMkopo(AinaMkopoRequest $request)
    {

        $validator = Validator::make($request->all(), [
            'jina' => 'required|string|max:255',
            'officeId' => 'required|integer',
            'riba' => 'required|integer',
            'siku' => 'required|integer',
            'fomu' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Jaza nafasi zote zilizo wazi', 'errors' => $validator->errors()], 400);
        }

        $jina = $request->input('jina');
        $officeId = $request->input('officeId');
        $riba = $request->input('riba');
        $siku = $request->input('siku');
        $fomu = $request->input('fomu');

        $office = Office::find($officeId);

        // Check if the customer exists
        if (!$office) {
            return response()->json(['message' => 'Ofisi unayoiwekea aina ya mikopo haipo'], 404);
        }

        $office = Aina::create([
            'jina' => $jina,
            'riba' => $riba,
            'siku' => $siku,
            'fomu' => $fomu,
            'office_id' => $officeId,
        ]);

        return response()->json(['message' => 'Aina ya mkopo umetengenezwa'], 200);

    }

    public function getAinaMikopo(AinaMkopoRequest $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Fill in all empty fields', 'errors' => $validator->errors()], 400);
        }

        $id = $request->input('id');

        $ainaMikiopo = Aina::with('office')->where('office_id', $id)->get();


        return response()->json(['ainaMikopo' => $ainaMikiopo], 200);
    }

    public function editAinaMkopo(AinaMkopoRequest $request){

        $validator = Validator::make($request->all(), [
            'jina' => 'required|string|max:255',
            'officeId' => 'required|integer',
            'riba' => 'required|integer',
            'siku' => 'required|integer',
            'id' => 'required|integer',
            'fomu' => 'required|integer',
        ]);
        

        if ($validator->fails()) {
            return response()->json(['message' => 'Jaza nafasi zote zilizo wazi', 'errors' => $validator->errors()], 400);
        }

        $jina = $request->input('jina');
        $officeId = $request->input('officeId');
        $riba = $request->input('riba');
        $siku = $request->input('siku');
        $id = $request->input('id');
        $fomu = $request->input('fomu');

        $aina = Aina::find($id);

        // Update customer details
        $aina->update([
            'jina' => $jina,
            'riba' => $riba,
            'siku' => $siku,
            'fomu' => $fomu,
            'office_id' => $officeId,
        ]);

        // Return success response
        return response()->json(['message' => 'Aina ya mkopo imebadilishiwa taarifa kikamilifu'], 200);
    }

    public function futaAinaMkopo(AinaMkopoRequest $request){

        $validator = Validator::make($request->all(), [
            'id' => 'required|integer',
        ]);
        

        if ($validator->fails()) {
            return response()->json(['message' => 'Jaza nafasi zote zilizo wazi', 'errors' => $validator->errors()], 400);
        }

        $id = $request->input('id');

        $aina = Aina::find($id);

        $aina->delete();

        // Return success response
        return response()->json(['message' => 'Aina ya mkopo umefutwa kikamilifu'], 200);
    }
}
