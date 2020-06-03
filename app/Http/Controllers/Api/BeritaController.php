<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Berita;
use App\Models\JenisBerita;
use Validator;
use Illuminate\Support\Facades\Auth;
use ImageStorage;
use App\Models\BeritaImage;
class BeritaController extends Controller
{
    public function index(Request $request)
    {
        $beritas = Berita::with('user','jenisberita','berita_images')->latest()->offset($request->start??0)->limit($request->limit??10)->get()->each(function($array){
            $array->status = $array->getStatus($array->status);
            return $array;
        });
        return response()->json([
            'status'=>true,
            'messages'=>"Berhasil mengambil data",
            'data'=> $beritas
        ], 200);
    }
    public function store(Request $request)
    {
        $user = Auth::user();
        $validator = Validator::make($request->all(), [
            'jenis_berita' => 'required',
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
            'jenis_berita_id' => $request->jenis_berita,
            'komentar' => $request->komentar
        ];

        $berita = Berita::create($params);
        if($berita){
            if($request->has('lampiran')){
                $dataInsert = [];
                foreach ($request->lampiran as $image) {
                    $image = base64_decode(explode(';',explode(',',$image)[1])[0]);
                    $name = $berita->user->name.'_'.$berita->jenisberita->name.'_'.time();
                    ImageStorage::upload($image,$name);
                    array_push($dataInsert,[
                        'berita_id' => $berita->id,
                        'path' => 'storage/'.$name.'.webp'
                    ]);
                }
                BeritaImage::insert($dataInsert);
            }
            return response()->json([
                'status' => true,
                'messages' => "Berita Berhasil diajukan",
                'data' => null
            ], 200);
        }else{
            return response()->json([
                'status' => false,
                'messages' => "Berita Gagal diajukan",
                'data' => null
            ], 400);
        }
        
    }
    public function show($id)
    {
        $berita = Berita::with('user','jenisberita','berita_images')->where('id',$id)->get()->each(function($array){
            $array->status = $array->getStatus($array->status);
            return $array;
        });
        return response()->json([
            'status'=>true,
            'messages'=>"Berhasil mengambil data",
            'data'=> $berita
        ], 200);
    }
    public function jenis()
    {
        $jenisberitas = JenisBerita::all();
        return response()->json([
            'status'=>true,
            'messages'=>"Berhasil mengambil data",
            'data'=> $jenisberitas
        ], 200);
    }
}
