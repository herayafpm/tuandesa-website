<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Bantuan;
use App\Models\SoalJawaban;
use App\Models\JenisBantuan;
use Validator;
use Illuminate\Support\Facades\Auth;
use ImageStorage;
use App\Models\BantuanImage;
use App\Models\BantuanJawaban;
use Str;
class BantuanController extends Controller
{
    public function index(Request $request)
    {
        $bantuans = Bantuan::with('user','jenisbantuan','bantuan_images')->latest()->offset($request->start??0)->limit($request->limit??10)->get()->each(function($array){
            $array->status = $array->getStatus($array->status);
            return $array;
        });
        return response()->json([
            'status'=>true,
            'messages'=>"Berhasil mengambil data",
            'data'=> $bantuans
        ], 200);
    }
    public function store(Request $request)
    {
        $user = Auth::user();
        $minSoal = "";
        if($request->has('jenis_bantuan')){
          $soaljawaban = SoalJawaban::where('jenis_bantuan_id',$request->jenis_bantuan)->get();
          $minSoal = "|min:".$soaljawaban->count();
        }
        $validator = Validator::make($request->all(), [
            'jenis_bantuan' => 'required',
            'komentar' => 'required',
            'jawaban' => 'required'.$minSoal,
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
            'jenis_bantuan_id' => $request->jenis_bantuan,
            'komentar' => $request->komentar
        ];

        $bantuan = Bantuan::create($params);
        if($bantuan){
            if($request->has('lampiran')){
                $dataInsert = [];
                foreach ($request->lampiran as $image) {
                    $image = base64_decode(explode(';',explode(',',$image)[1])[0]);
                    $name = $bantuan->user->name.'_'.Str::random(10).'_'.$bantuan->jenisbantuan->name.'_'.time();
                    ImageStorage::upload($image,$name);
                    array_push($dataInsert,[
                        'bantuan_id' => $bantuan->id,
                        'path' => 'storage/'.$name.'.webp'
                    ]);
                }
                BantuanImage::insert($dataInsert);
            }
            if($request->has('jawaban')){
              $dataInsert = [];
              $no = 0;
              foreach ($soaljawaban as $soal) {
                array_push($dataInsert,[
                    'bantuan_id' => $bantuan->id,
                    'soal_jawaban_id' => $soal->id,
                    'jawaban_id' => $request->jawaban[$no],
                ]);
                $no++;
              }
              BantuanJawaban::insert($dataInsert);
            }
            return response()->json([
                'status' => true,
                'messages' => "Bantuan Berhasil diajukan",
                'data' => null
            ], 200);
        }else{
            return response()->json([
                'status' => false,
                'messages' => "Bantuan Gagal diajukan",
                'data' => null
            ], 400);
        }
        
    }
    public function show($id)
    {
        $bantuan = Bantuan::with('user','jenisbantuan','bantuan_images','bantuan_jawabans')->where('id',$id)->get()->each(function($array){
            $array->status = $array->getStatus($array->status);
            $array->bantuan_jawabans->each(function($bantuan){
              $bantuan->soal_jawaban;
              $bantuan->jawaban;
            });
            return $array;
        });
        return response()->json([
            'status'=>true,
            'messages'=>"Berhasil mengambil data",
            'data'=> $bantuan
        ], 200);
    }
    public function jenis()
    {
        $jenisbantuans = JenisBantuan::all();
        return response()->json([
            'status'=>true,
            'messages'=>"Berhasil mengambil data",
            'data'=> $jenisbantuans
        ], 200);
    }
    public function soaljawaban($id)
    {
      $soaljawabans = SoalJawaban::with('jawabans')->where('jenis_bantuan_id',$id)->get();
      return response()->json([
            'status'=>true,
            'messages'=>"Berhasil mengambil data",
            'data'=> $soaljawabans
        ], 200);
    }
}