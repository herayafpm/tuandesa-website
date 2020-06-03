<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\JenisAduan;

use App\Http\Requests\JenisAduanRequest;
class JenisAduanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $jenisaduans = JenisAduan::latest()->paginate(10);
        return view('admin.jenisaduans.index',compact('jenisaduans'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.jenisaduans.form');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(JenisAduanRequest $request)
    {
        $params = $request->except('_token');
        if(JenisAduan::create($params)){
            flash('Jenis Aduan berhasil ditambahkan')->success();
        }else{
            flash('Jenis Aduan gagal ditambahkan')->error()->important();
        }
        return redirect()->route('jenisaduans.index');
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
        $jenisaduan = JenisAduan::findOrFail($id);
        return view('admin.jenisaduans.form',compact('jenisaduan'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(JenisAduanRequest $request, $id)
    {
        $params = $request->except('_token');
        $jenisaduan = JenisAduan::findOrFail($id);

        if($jenisaduan->update($params)){
            flash('Jenis Aduan berhasil diubah')->success();
        }else{
            flash('Jenis Aduan gagal diubah')->error()->important();
        }
        return redirect()->route('jenisaduans.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $jenisaduan = JenisAduan::findOrFail($id);
        if($jenisaduan->delete()){
            flash('Jenis Aduan berhasil dihapus')->success();
        }else{
            flash('Jenis Aduan gagal dihapus')->error()->important();
        }
        return redirect()->route('jenisaduans.index');
    }
}
