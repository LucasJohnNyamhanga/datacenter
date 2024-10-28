<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UploadController extends Controller
{
    function upload(Request $request)
    {
        $image = $request->file("file");
        if ($request->hasFile("file")) {
            //$imagePath = base_path().'/../public_html/uploads/imani/images/';
            $imagePath = public_path('uploads/microcredit/images/');
            $new_name = rand() . $image->getClientOriginalName();
            $image->move($imagePath, $new_name);
            //return response()->json(['message' => 'http://10.0.2.2:8000/uploads/microcredit/images/' . $new_name], 200);
            return response()->json(['message' => 'https://shulemtandao.com/uploads/microcredit/images/' . $new_name], 200);
        } else {
            return response()->json('null');
        }
    }
}
