<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pelayanan;
use App\Models\JenisPelayanan;
use Validator;
use Illuminate\Support\Facades\Auth;
use ImageStorage;
use App\Models\PelayananImage;
class PelayananController extends Controller
{
    public function index(Request $request)
    {
        $pelayanans = Pelayanan::with('user','jenispelayanan','pelayanan_images')->latest()->offset($request->start??0)->limit($request->limit??10)->get()->each(function($array){
            $array->status = $array->getStatus($array->status);
            return $array;
        });
        return response()->json([
            'status'=>true,
            'messages'=>"Berhasil mengambil data",
            'data'=> $pelayanans
        ], 200);
    }
    public function store(Request $request)
    {
        $user = Auth::user();
        $validator = Validator::make($request->all(), [
            'jenis_pelayanan' => 'required',
            'komentar' => 'required',
            'lampiran' => 'required',
            'lampiran.*'=> 'is_lampiran'
        ]);

        if ($validator->fails()) {
            $messages = '';
            $errors = $validator->messages()->get('*');
            foreach ($errors as $error) {
                foreach ($error as $err) {
                    $messages .= "\n".$err;
                }
            }
            return response()->json([
                'status' => false,
                'messages' => $messages,
                'data' => null
            ], 400);     
        }
        $params = [
            'user_id' => $user->id,
            'jenis_pelayanan_id' => $request->jenis_pelayanan,
            'komentar' => $request->komentar
        ];

        $pelayanan = Pelayanan::create($params);
        if($pelayanan){
            if($request->has('lampiran')){
                $dataInsert = [];
                foreach ($request->lampiran as $image) {
                    $image = base64_decode(explode(';',explode(',',$image)[1])[0]);
                    $name = $pelayanan->user->name.'_'.$pelayanan->jenispelayanan->name.'_'.time();
                    ImageStorage::upload($image,$name);
                    array_push($dataInsert,[
                        'pelayanan_id' => $pelayanan->id,
                        'path' => 'storage/'.$name.'.webp'
                    ]);
                }
                PelayananImage::insert($dataInsert);
            }
            return response()->json([
                'status' => true,
                'messages' => "Pelayanan Berhasil diajukan",
                'data' => null
            ], 200);
        }else{
            return response()->json([
                'status' => false,
                'messages' => "Pelayanan Gagal diajukan",
                'data' => null
            ], 400);
        }
        
    }
    public function show($id)
    {
        $pelayanan = Pelayanan::with('user','jenispelayanan','pelayanan_images')->where('id',$id)->get()->each(function($array){
            $array->status = $array->getStatus($array->status);
            return $array;
        });
        return response()->json([
            'status'=>true,
            'messages'=>"Berhasil mengambil data",
            'data'=> $pelayanan
        ], 200);
    }
    public function jenis()
    {
        $jenispelayanans = JenisPelayanan::all();
        return response()->json([
            'status'=>true,
            'messages'=>"Berhasil mengambil data",
            'data'=> $jenispelayanans
        ], 200);
    }
}
