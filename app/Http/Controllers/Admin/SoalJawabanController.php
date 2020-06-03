<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SoalJawaban;
use App\Models\Jawaban;
use App\Models\JenisBantuan;

use App\Http\Requests\SoalJawabanRequest;
use App\Http\Requests\JawabanRequest;
use App\Authorizable;
class SoalJawabanController extends Controller
{
  use Authorizable;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

      $jenisbantuan = JenisBantuan::get()->first();
      $jenisbantuans = JenisBantuan::all();
      $soaljawabans = SoalJawaban::where('jenis_bantuan_id',$request->jenis_bantuan??$jenisbantuan->id)->latest()->paginate(10);
      return view('admin.soaljawabans.index',compact('soaljawabans','jenisbantuans','jenisbantuan'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
      $jenisbantuan = JenisBantuan::where('id',$request->jenis_bantuan)->get()->first();
      $jenisbantuans = JenisBantuan::pluck('name','id');
      $tipes = SoalJawaban::tipes();
      $bobot = SoalJawaban::where('jenis_bantuan_id',$request->jenis_bantuan)->pluck('bobot');
      if($bobot){
        $bob = 100;
        foreach ($bobot as $b) {
          $bob -= (int) $b;
        }
        $bobot = $bob;
      }else{
        $bobot = 100;
      }
      return view('admin.soaljawabans.form',compact('jenisbantuans','jenisbantuan','bobot','tipes'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SoalJawabanRequest $request)
    {
        $params = $request->except('_token');
        if(SoalJawaban::create($params)){
            flash('Soal berhasil ditambahkan')->success();
        }else{
            flash('Soal gagal ditambahkan')->error()->important();
        }
        return redirect()->route('soaljawabans.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $soaljawaban = SoalJawaban::findOrFail($id);
        $jenisbantuan = JenisBantuan::where('id',$soaljawaban->jenis_bantuan_id)->get()->first();
        $jenisbantuans = JenisBantuan::pluck('name','id');
        $tipes = SoalJawaban::tipes();
        $bobot = SoalJawaban::where('id','!=',$id)->where('jenis_bantuan_id',$soaljawaban->jenis_bantuan_id)->pluck('bobot');
        if($bobot){
          $bob = 100;
          foreach ($bobot as $b) {
            $bob -= (int) $b;
          }
          $bobot = $bob;
        }else{
          $bobot = 100;
        }
        return view('admin.soaljawabans.form',compact('soaljawaban','jenisbantuans','jenisbantuan','bobot','tipes'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(SoalJawabanRequest $request, $id)
    {
        $params = $request->except('_token');
        $soaljawaban = SoalJawaban::findOrFail($id);

        if($soaljawaban->update($params)){
            flash('Soal berhasil diubah')->success();
        }else{
            flash('Soal gagal diubah')->error()->important();
        }
        return redirect()->route('soaljawabans.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $soaljawaban = SoalJawaban::findOrFail($id);
        if($soaljawaban->delete()){
            flash('Soal berhasil dihapus')->success();
        }else{
            flash('Soal gagal dihapus')->error()->important();
        }
        return redirect()->route('soaljawabans.index');
    }

    public function create_jawaban(Request $request,$id)
    {
      $soaljawaban = SoalJawaban::findOrFail($id);
      $jumlahnilai = env('COUNT_NILAI');
      return view('admin.soaljawabans.form_jawaban',compact('soaljawaban','jumlahnilai'));
    }
    public function store_jawaban(JawabanRequest $request,$id)
    {
      $params = $request->except('_token');
      if(Jawaban::create($params)){
          flash('Jawaban berhasil ditambahkan')->success();
      }else{
          flash('Jawaban gagal ditambahkan')->error()->important();
      }
      return redirect("admin/soaljawabans/$id/edit");
    }

    public function edit_jawaban($soaljawabanId,$jawabanId)
    {
      $soaljawaban = SoalJawaban::findOrFail($soaljawabanId);
      $jawaban = Jawaban::findOrFail($jawabanId);
      $jumlahnilai = env('COUNT_NILAI');
      return view('admin.soaljawabans.form_jawaban',compact('soaljawaban','jumlahnilai','jawaban'));
    }
    public function update_jawaban(JawabanRequest $request,$soaljawabanId,$jawabanId)
    {
      $jawaban = Jawaban::findOrFail($jawabanId);
      $params = $request->except('_token');
      if($jawaban->update($params)){
          flash('Jawaban berhasil diupdate')->success();
      }else{
          flash('Jawaban gagal diupdate')->error()->important();
      }
      return redirect("admin/soaljawabans/$soaljawabanId/edit");
    }
    public function destroy_jawaban($soaljawabanId,$jawabanId)
    {
      $jawaban = Jawaban::findOrFail($jawabanId);
      if($jawaban->delete()){
          flash('Jawaban berhasil dihapus')->success();
      }else{
          flash('Jawaban gagal dihapus')->error()->important();
      }
      return redirect("admin/soaljawabans/$soaljawabanId/edit");
    }
}
