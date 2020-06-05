<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProfileDesa;
use App\Models\ProfileDesaImage;
class ProfileDesaController extends Controller
{
    public function index(Request $request)
    {
        $profiledesas = ProfileDesa::with('profile_desa_images')->get();
        return response()->json([
            'status'=>true,
            'messages'=>"Berhasil mengambil data",
            'data'=> $profiledesas
        ], 200);
    }
    
}