<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Zakat;
use App\Models\ZakatAmil;
use Validator;
use Illuminate\Support\Facades\Auth;
class ZakatController extends Controller
{
    public function index()
    {
        $now = date('Y-m-d');
        $zakats = Zakat::where('start', '<=', $now)->where('end','>=',$now)->latest()->offset($request->start??0)->limit($request->limit??10)->get();
        return response()->json([
            'status'=>true,
            'messages'=>"Berhasil mengambil data",
            'data'=> $zakats
        ], 200);
    }

    public function show($id)
    {
        $jumlahdusun = (int) env('COUNT_DUSUN');
        $zakatamil = ZakatAmil::where('user_id',auth()->user()->id)->where('zakat_id',$id)->get()->first();
        return response()->json([
            'status'=>true,
            'messages'=>"Berhasil mengambil data",
            'data'=> [
                'dusun'=> $jumlahdusun,
                'data' => $zakatamil
            ]
        ], 200);
    }
    public function store(Request $request,$id)
    {
        $user = Auth::user();
        $zakatamil = ZakatAmil::where('zakat_id',$id)->where('user_id',$user->id)->get()->first();
        if($zakatamil){
            $dusun = 'required|numeric|unique:zakat_amils,dusun,'.$zakatamil->id.',id,zakat_id,'.$id;
        }else{
            $dusun = 'required|numeric|unique:zakat_amils,dusun,NULL,id,zakat_id,'.$id;
        }
        $validator = Validator::make($request->all(), [
            'dusun' => $dusun,
            'beras' => 'required|numeric',
            'uang' => 'required|numeric',
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
            'zakat_id' => $id,
            'user_id' => $user->id,
            'dusun' => $request->dusun,
            'beras' => $request->beras,
            'uang' => $request->uang,
        ];
        if($zakatamil){
            $zakatamil->update($params);
        }else{
            $zakatamil = ZakatAmil::create($params);
        }
        return response()->json([
            'status' => true,
            'messages' => "Zakat Amil Berhasil diupdate",
            'data' => null
        ], 200);
    }
}
