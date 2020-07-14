<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BantuanPemeringkatan;
use App\Models\BantuanPemeringkatanDetail;
use App\Http\Requests\BantuanPemeringkatanRequest;
use ImageStorage;
use Str;
use App\Models\JenisBantuan;
use App\Models\SoalJawaban;
use App\Models\Bantuan;
use App\Models\BantuanJawaban;
use App\Models\Jawaban;
use SAW;
class BantuanPemeringkatanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $bantuanpemeringkatans = BantuanPemeringkatan::latest()->paginate(10);
        return view('admin.bantuanpemeringkatans.index',compact('bantuanpemeringkatans'));
    }
    public function create()
    {
        $data = [
            'jenis_bantuan' => JenisBantuan::pluck('name','id')
        ];
        return view('admin.bantuanpemeringkatans.form',$data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(BantuanPemeringkatanRequest $request)
    {
        $judul = $request->judul;
        $date = explode(' - ',$request->date);
        $start = $date[0].' 00:00:00';
        $end = $date[1].' 23:59:59';
        $params = [
            'judul' => $judul,
            'jenis_bantuan_id' => $request->jenis_bantuan_id,
            'start' => $start,
            'end' => $end
        ];
        if(BantuanPemeringkatan::create($params)){
            flash('Bantuan Pemeringkatan berhasil ditambahkan')->success();
        }else{
            flash('Bantuan Pemeringkatan gagal ditambahkan')->error()->important();
        }
        return redirect()->route('bantuanpemeringkatans.index');
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $bantuanpemeringkatan = BantuanPemeringkatan::findOrFail($id);
        $pemeringkatans = BantuanPemeringkatanDetail::with('bantuan')->where('bantuan_pemeringkatan_id',$id)->paginate(10);
        return view('admin.bantuanpemeringkatans.show',compact('bantuanpemeringkatan','pemeringkatans'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // $bantuan = Bantuan::findOrFail($id);
        // $bantuan->bantuan_images->each(function($image){
        //     BantuanImage::findOrFail($image->id);
        //     $imagePath = substr($image->path,8);
        //     if($image->delete()){
        //         ImageStorage::delete($imagePath);
        //     }
        // });
        // if($bantuan->delete()){
        //     flash('Bantuan berhasil dihapus')->success();
        // }else{
        //     flash('Bantuan gagal dihapus')->error()->important();
        // }
        // return redirect()->route('bantuans.index');
    }
    public function edit($id)
    {
        $bantuanpemeringkatan = BantuanPemeringkatan::findOrFail($id);
        $jenis_bantuan = JenisBantuan::pluck('name','id');
        return view('admin.bantuanpemeringkatans.form',compact('bantuanpemeringkatan','jenis_bantuan'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(BantuanPemeringkatanRequest $request, $id)
    {
        $bantuanpemeringkatan = BantuanPemeringkatan::findOrFail($id);
        $judul = $request->judul;
        $date = explode(' - ',$request->date);
        $start = $date[0].' 00:00:00';
        $end = $date[1].' 23:59:59';
        $params = [
            'judul' => $judul,
            'jenis_bantuan_id' => $request->jenis_bantuan_id,
            'start' => $start,
            'end' => $end
        ];
        if($bantuanpemeringkatan->update($params)){
            flash('Bantuan Pemeringkatan berhasil diubah')->success();
        }else{
            flash('Bantuan Pemeringkatan gagal diubah')->error()->important();
        }
        return redirect()->route('bantuanpemeringkatans.index');
    }
    public function peringkat($id)
    {
        $bantuanpemeringkatan = BantuanPemeringkatan::findOrFail($id);
        $soaljawabans = SoalJawaban::where('jenis_bantuan_id',$bantuanpemeringkatan->jenis_bantuan_id)->orderBy('id','desc')->get();
        $bantuans = Bantuan::where('status','==',0)->where(function ($query) use ($bantuanpemeringkatan) {
            $query->where('created_at', '<=', $bantuanpemeringkatan->end);
            $query->where('created_at', '>=', $bantuanpemeringkatan->start);
        })->get()->toArray();
        $no = 0;
        foreach ($bantuans as $bantuan) {
            $jawabans_id = BantuanJawaban::where('bantuan_id',$bantuan['id'])->pluck('jawaban_id')->toArray();
            $nilais = Jawaban::whereIn('id',$jawabans_id)->pluck('nilai')->toArray();
            // $jawabans = Jawaban::whereIn('id',$jawabans_id)->pluck('jawaban')->toArray();
            // $bantuans[$no]['jawabans'] = $jawabans;
            // $bantuan['hasil'] = $nilais;
            $bantuans[$no]['hasil'] = $nilais;
            $no++;
        }
        $rekomendasi = SAW::getRecomendation($bantuans,$soaljawabans);
        var_dump($rekomendasi);
        $no = 1;
        foreach ($rekomendasi as $rek) {
            $rek['peringkat'] = $no;
            $rek['bantuan_pemeringkatan_id'] = $id;
            BantuanPemeringkatanDetail::insert($rek);
            $no++;
        }
        return redirect()->route('bantuanpemeringkatans.show', $id);
    }
    public function peringkat_reset($id)
    {
        $bantuanpemeringkatans = BantuanPemeringkatanDetail::where('bantuan_pemeringkatan_id',$id)->pluck('bantuan_id')->toarray();
        $bantuans = Bantuan::whereIn('id',$bantuanpemeringkatans);
        $bantuans->update(['status'=>0]);
        if(BantuanPemeringkatanDetail::where('bantuan_pemeringkatan_id',$id)->delete()){
            flash('Berhasil direset')->success();
        }else{
            flash('gagal direset')->error()->important();
        }
        return redirect()->route('bantuanpemeringkatans.show', $id);
    }
}