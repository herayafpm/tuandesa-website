<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\JenisBantuan;

use App\Http\Requests\JenisBantuanRequest;
use App\Authorizable;
class JenisBantuanController extends Controller
{
  use Authorizable;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $jenisbantuans = JenisBantuan::latest()->paginate(10);
        return view('admin.jenisbantuans.index',compact('jenisbantuans'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.jenisbantuans.form');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(JenisBantuanRequest $request)
    {
        $params = $request->except('_token');
        if(JenisBantuan::create($params)){
            flash('Jenis Bantuan berhasil ditambahkan')->success();
        }else{
            flash('Jenis Bantuan gagal ditambahkan')->error()->important();
        }
        return redirect()->route('jenisbantuans.index');
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
        $jenisbantuan = JenisBantuan::findOrFail($id);
        return view('admin.jenisbantuans.form',compact('jenisbantuan'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(JenisBantuanRequest $request, $id)
    {
        $params = $request->except('_token');
        $jenisbantuan = JenisBantuan::findOrFail($id);

        if($jenisbantuan->update($params)){
            flash('Jenis Bantuan berhasil diubah')->success();
        }else{
            flash('Jenis Bantuan gagal diubah')->error()->important();
        }
        return redirect()->route('jenisbantuans.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $jenisbantuan = JenisBantuan::findOrFail($id);
        if($jenisbantuan->delete()){
            flash('Jenis Bantuan berhasil dihapus')->success();
        }else{
            flash('Jenis Bantuan gagal dihapus')->error()->important();
        }
        return redirect()->route('jenisbantuans.index');
    }
}
