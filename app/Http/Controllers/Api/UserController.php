<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;
use App\Models\Aduan;
use App\Models\Pelayanan;
class UserController extends Controller
{
    public function login(){
        if(Auth::attempt(['username' => request('username'), 'password' => request('password')])){
            $user = Auth::user();
            $token =  $user->createToken('nApp')->accessToken;
            return response()->json([
                'status' => true,
                'messages' => 'Berhasil Masuk',
                'data'=>$token
            ], 200);
        }
        else{
            return response()->json([
                'status' => false,
                'messages' => "Username atau Password salah!"
            ], 401);
        }
    }
    public function profile()
    {
        $user = Auth::user();
        return response()->json([
            'status' => true,
            'messages' => "Berhasil mengambil data!",
            'data' => $user
        ], 200);
    }
    public function aduans(Request $request)
    {
        $user = Auth::user();
        $aduans = Aduan::with('user','jenisaduan','aduan_images')->where('user_id',$user->id)->latest()->offset($request->start)->limit($request->limit)->get();
        return response()->json([
            'status'=>true,
            'messages'=>"Berhasil mengambil data",
            'data'=> $aduans
        ], 200);
    }
    public function pelayanans(Request $request)
    {
        $user = Auth::user();
        $pelayanans = Pelayanan::with('user','jenispelayanan','pelayanan_images')->where('user_id',$user->id)->latest()->offset($request->start)->limit($request->limit)->get();
        return response()->json([
            'status'=>true,
            'messages'=>"Berhasil mengambil data",
            'data'=> $pelayanans
        ], 200);
    }
    public function update(Request $request)
    {
        $user = Auth::user();
        $validator = Validator::make($request->all(), [
            'ttl' => 'required',
            'no_hp' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10',
            'email' => 'required|email|unique:users,email,'.$user->id,
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
            'ttl' => $request->ttl,
            'no_hp' => $request->no_hp,
            'email' => $request->email
        ];
        $user->update($params);
        return response()->json([
            'status' => true,
            'messages' => "Berhasil mengubah data!",
            'data' => $user
        ], 201);
    }
}
