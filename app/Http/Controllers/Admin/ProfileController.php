<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Zakat;
use App\Models\ZakatAmil;
use App\Models\JenisAduan;
use App\Models\JenisPelayanan;

use App\Http\Requests\ZakatAmilRequest;
use App\Http\Requests\AduanRequest;
use App\Http\Requests\UserRequest;
use App\Http\Requests\PelayananRequest;
use App\Models\Aduan;
use App\Models\Pelayanan;
use App\Models\AduanImage;
use App\Models\PelayananImage;
use ImageStorage;
use App\Models\AduanLike;
use App\Models\AduanComment;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function index()
    {
        $now = date('Y-m-d');
        $zakats = Zakat::where('start', '<=', $now)->where('end','>=',$now)->get();
        $user = auth()->user();
        return view('admin.profile',compact('user','zakats'));
    }
    public function update(UserRequest $request)
    {
        $user = auth()->user();
        $params = [
            'email' => $request->email,
            'ttl' => $request->ttl,
            'no_hp' => $request->no_hp,
        ];
        $cek = Hash::check($request->password, $user->password);
        if($cek){
            $user->update($params);
            flash('Berhasil diupdate')->success();
        }else{
            flash('Password yang anda masukkan tidak sesuai')->error()->important();
        }
        return redirect()->route('profile.index');
    }
    public function zakat($id)
    {
        $jumlahdusun = (int) env('COUNT_DUSUN');
        $zakat = Zakat::findOrFail($id);
        $zakatamil = ZakatAmil::where('user_id',auth()->user()->id)->where('zakat_id',$id)->get()->first();
        return view('admin.profiles.form_zakat',compact('zakat','zakatamil','jumlahdusun'));
    }
    public function zakat_store(ZakatAmilRequest $request, $id)
    {
        $params = [
            'zakat_id' => $id,
            'user_id' => auth()->user()->id,
            'dusun' => $request->dusun,
            'beras' => $request->beras,
            'uang' => $request->uang,
        ];
        if(!empty($request->id)){
            $zakatamil = ZakatAmil::findOrFail($request->id);
            $store = $zakatamil->update($params);
        }else {
            $store = ZakatAmil::create($params);
        }
        flash('Data Zakat berhasil dikirim')->success();
        return redirect()->route('profile.index');
    }
    public function aduan_create()
    {
        $jenisaduans = JenisAduan::pluck('name','id');
        return view('admin.profiles.form_aduan',compact('jenisaduans'));
    }
    public function aduan_store(AduanRequest $request)
    {

        $params = [
            'user_id' => auth()->user()->id,
            'jenis_aduan_id' => $request->jenis_aduan_id,
            'komentar' => $request->komentar
        ];
        $aduan = Aduan::create($params);
        if($aduan){
            if($request->has('image')){
                $dataInsert = [];
                foreach ($request['image'] as $image) {
                    $name = $aduan->user->name.'_'.$aduan->jenisaduan->name.'_'.time();
                    ImageStorage::upload($image,$name);
                    array_push($dataInsert,[
                        'aduan_id' => $aduan->id,
                        'path' => 'storage/'.$name.'.webp'
                    ]);
                }
                AduanImage::insert($dataInsert);
            }
            flash('Aduan berhasil diajukan')->success();
        }else{
            flash('Aduan gagal diajukan')->error()->important();
        }
        return redirect()->route('profile.index');
    }
    public function likeAduan($id)
    {
        $aduan = Aduan::findOrFail($id);
        $user = auth()->user()->id;
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
        return redirect()->route('profile.index');
    }
    public function komentarAduan(Request $request, $id)
    {
        if(empty($request->komentar)){
            flash('Komentar tidak boleh kosong')->error()->important();
            return redirect()->back();
        }
        $aduan = Aduan::findOrFail($id);
        $user = auth()->user()->id;
        $komentar = $request->komentar;
        $params = [
            'user_id' => $user,
            'aduan_id' => $id,
            'komentar' => htmlspecialchars($komentar)
        ];
        $komentarAduan =  AduanComment::create($params);
        if($komentarAduan){
            flash('Berhasil berkomentar')->success();
        }else{
            flash('Gagal berkomentar')->error()->important();
        }
        return redirect()->route('profile.index');
    }
    public function komentarAduan_delete($id)
    {
        $komentarAduan = AduanComment::findOrFail($id);
        if($komentarAduan->delete()){
            flash('komentar Aduan berhasil dihapus')->success();
        }else{
            flash('komentar Aduan gagal dihapus')->error()->important();
        }
        return redirect()->back();
    }
    public function pelayanan_create()
    {
        $jenispelayanans = JenisPelayanan::pluck('name','id');
        return view('admin.profiles.form_pelayanan',compact('jenispelayanans'));
    }
    public function pelayanan_store(PelayananRequest $request)
    {

        $params = [
            'user_id' => auth()->user()->id,
            'jenis_pelayanan_id' => $request->jenis_pelayanan_id,
            'komentar' => $request->komentar
        ];
        $pelayanan = Pelayanan::create($params);
        if($pelayanan){
            if($request->has('image')){
                $dataInsert = [];
                foreach ($request['image'] as $image) {
                    $name = $pelayanan->user->name.'_'.$pelayanan->jenispelayanan->name.'_'.time();
                    ImageStorage::upload($image,$name);
                    array_push($dataInsert,[
                        'pelayanan_id' => $pelayanan->id,
                        'path' => 'storage/'.$name.'.webp'
                    ]);
                }
                PelayananImage::insert($dataInsert);
            }
            flash('Pelayanan berhasil diajukan')->success();
        }else{
            flash('Pelayanan gagal diajukan')->error()->important();
        }
        return redirect()->route('profile.index');
    }


}
