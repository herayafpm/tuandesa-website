<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;
use App\Models\Aduan;
use App\Models\User;
use App\Models\Pelayanan;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Str;
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
    public function lupa(Request $request){
        $user = User::where('username', '=', $request->username)
            ->first();
        //Check if the user exists
        if (!$user) {
            return response()->json([
                'status' => false,
                'messages' => "Username tidak ditemukkan!",
                'data' => []
            ], 404);
        }
        $token = Str::random(60);
        //Create Password Reset Token
        DB::table('password_resets')->insert([
            'email' => $user->email,
            'token' => $token,
            'created_at' => \Carbon\Carbon::now()
        ]);
        $cek = $user->sendPasswordResetNotification($token);
        return response()->json([
            'status' => true,
            'messages' => "Berhasil mengirim email ke ".$user->email,
            'data' => $cek
        ], 200);
        
    }
    public function profile()
    {
        $user = Auth::user();
        $user->type = $user->getRoleNames()[0];
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
            'password' => 'required|min:6',
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
        if (Hash::check($request->password, $user->password)) {
            if(!empty($request->password2)){
                $params['password'] = bcrypt($request->password2);
            }
            $user->update($params);
            return response()->json([
                'status' => true,
                'messages' => "Berhasil mengubah data!",
                'data' => [$params,$request->password2]
            ], 201);
        }else{
            return response()->json([
                'status' => false,
                'messages' => "Password yang dimasukan tidak valid",
                'data' => null
            ], 400);
        }
        
    }
}