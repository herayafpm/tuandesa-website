<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Aduan;
use App\Models\JenisAduan;
use Validator;
use Illuminate\Support\Facades\Auth;
use ImageStorage;
use App\Models\AduanImage;
use App\Models\AduanLike;
use App\Models\AduanComment;
use Str;
class AduanController extends Controller
{
    public function index(Request $request)
    {
        $aduans = Aduan::with('user','jenisaduan','aduan_images')->latest()->offset($request->start??0)->limit($request->limit??10)->get();
        return response()->json([
            'status'=>true,
            'messages'=>"Berhasil mengambil data",
            'data'=> $aduans
        ], 200);
    }
    public function store(Request $request)
    {
        $user = Auth::user();
        $validator = Validator::make($request->all(), [
            'jenis_aduan' => 'required',
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
            'jenis_aduan_id' => $request->jenis_aduan,
            'komentar' => $request->komentar
        ];

        $aduan = Aduan::create($params);
        if($aduan){
            if($request->has('lampiran')){
                $dataInsert = [];
                foreach ($request->lampiran as $image) {
                    $image = base64_decode(explode(';',explode(',',$image)[1])[0]);
                    $name = $aduan->user->name.'_'.Str::random(10).'_'.$aduan->jenisaduan->name.'_'.time();
                    ImageStorage::upload($image,$name);
                    array_push($dataInsert,[
                        'aduan_id' => $aduan->id,
                        'path' => 'storage/'.$name.'.webp'
                    ]);
                }
                AduanImage::insert($dataInsert);
            }
            return response()->json([
                'status' => true,
                'messages' => "Aduan Berhasil diajukan",
                'data' => null
            ], 200);
        }else{
            return response()->json([
                'status' => false,
                'messages' => "Aduan Gagal diajukan",
                'data' => null
            ], 400);
        }
        
    }
    public function show($id)
    {
        $aduan = Aduan::with('user','jenisaduan','aduan_images')->where('id',$id)->get()->each(function($array){
            $array->status = $array->getStatus($array->status);
            $array->me = false;
            foreach ($array->likes as $like) {
                if($like['user_id'] == Auth::user()->id){
                    $array->me = true;
                }
            }
            $array->totalLike =$array->likes->count();
            $array->totalComment =$array->comments->count();
            return $array;
        });
        return response()->json([
            'status'=>true,
            'messages'=>"Berhasil mengambil data",
            'data'=> $aduan
        ], 200);
    }
    public function destroy($id){
        $aduan = Aduan::findOrFail($id);
        $aduan->aduan_images->each(function($image){
            $imagePath = substr($image->path,8);
            if($image->delete()){
                ImageStorage::delete($imagePath);
            }
        });
        if($aduan->delete()){
           return response()->json([
                'status'=>true,
                'messages'=>"Berhasil menghapus aduan",
                'data'=> []
            ], 200);
        }else{
            return response()->json([
                'status'=>false,
                'messages'=>"Berhasil menghapus aduan",
                'data'=> []
            ], 400);
        }
    }
    public function likeAduan($id)
    {
        $aduan = Aduan::findOrFail($id);
        $user = Auth::user()->id;
        $like = (bool) $aduan->likes->where('user_id',$user)->count();
        $params = [
            'user_id' => $user,
            'aduan_id' => $id,
        ];
        if($like){
            AduanLike::where($params)->delete();
        }else{
            AduanLike::create($params);
        }
        return response()->json([
            'status'=>true,
            'messages'=>"Berhasil ".((!$like)?"Menlike":"Mendislike")." Aduan",
            'data'=> null
        ], 200);
    }
    public function komentarAduan(Request $request, $id)
    {
        if(empty($request->komentar)){
            return response()->json([
                'status'=>false,
                'messages'=>"Komentar tidak boleh kosong!",
                'data'=> null
            ], 400);
        }
        $aduan = Aduan::findOrFail($id);
        $user = Auth::user()->id;
        $komentar = $request->komentar;
        $params = [
            'user_id' => $user,
            'aduan_id' => $id,
            'komentar' => htmlspecialchars($komentar)
        ];
        $komentarAduan =  AduanComment::create($params);
        if($komentarAduan){
            return response()->json([
                'status'=>true,
                'messages'=>"Berhasil Mengomentari Aduan",
                'data'=> null
            ], 200);
        }else{
            return response()->json([
                'status'=>false,
                'messages'=>"Gagal Mengomentari Aduan",
                'data'=> null
            ], 400);
        }
    }
    public function aduanComments(Request $request,$id)
    {
        $comments = AduanComment::with('user')->where('aduan_id',$id)->latest()->offset($request->start??0)->limit($request->limit??10)->get();
        return response()->json([
            'status'=>true,
            'messages'=>"Berhasil mengambil data",
            'data'=> $comments
        ], 200);
    }

    public function komentarDelete($id)
    {
        $komentarAduan = AduanComment::findOrFail($id);
        if($komentarAduan->delete()){
            return response()->json([
                'status'=>true,
                'messages'=>"Berhasil menghapus komentar Aduan",
                'data'=> null
            ], 200);
        }else{
            return response()->json([
                'status'=>true,
                'messages'=>"Gagal menghapus komentar Aduan",
                'data'=> null
            ], 400);
        }
    }
    public function jenis()
    {
        $jenisaduans = JenisAduan::all();
        return response()->json([
            'status'=>true,
            'messages'=>"Berhasil mengambil data",
            'data'=> $jenisaduans
        ], 200);
    }
}