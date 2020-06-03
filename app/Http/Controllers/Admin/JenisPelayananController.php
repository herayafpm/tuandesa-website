<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\JenisPelayanan;

use App\Http\Requests\JenisPelayananRequest;
use File;
use App\Models\PelayananImage;

class JenisPelayananController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $jenispelayanans = JenisPelayanan::orderBy($request->orderBy ?? 'updated_at',$request->order??'DESC');
        if(!empty($request->date)){
            $date = explode(' - ',urldecode($request->date));
            $starDate = $date[0].' 00:00:00';
            $endDate = $date[1].' 23:59:59';
            $jenispelayanans = $jenispelayanans->whereBetween('created_at',[$starDate,$endDate]);
        }

        if(!empty($request->search)){
            $searchFields = ['name','created_at'];
            $jenispelayanans->where(function($jenispelayanans) use($request, $searchFields){
                $searchWildcard = '%' . $request->search . '%';
                    foreach($searchFields as $field){
                    $jenispelayanans->orWhere($field, 'LIKE', $searchWildcard);
                }
            });
        }
        $jenispelayanans = $jenispelayanans->paginate(10);
        return view('admin.jenispelayanans.index',compact('jenispelayanans'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.jenispelayanans.form');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(JenisPelayananRequest $request)
    {
        $params = $request->except('_token');
        if(JenisPelayanan::create($params)){
            flash('Jenis Pelayanan berhasil ditambahkan')->success();
        }else{
            flash('Jenis Pelayanan gagal ditambahkan')->error()->important();
        }
        return redirect()->route('jenispelayanans.index');
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
        $jenispelayanan = JenisPelayanan::findOrFail($id);
        return view('admin.jenispelayanans.form',compact('jenispelayanan'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(JenisPelayananRequest $request, $id)
    {
        $params = $request->except('_token');
        $jenispelayanan = JenisPelayanan::findOrFail($id);

        if($jenispelayanan->update($params)){
            flash('Jenis Pelayanan berhasil diubah')->success();
        }else{
            flash('Jenis Pelayanan gagal diubah')->error()->important();
        }
        return redirect()->route('jenispelayanans.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $jenispelayanan = JenisPelayanan::findOrFail($id);
        if($jenispelayanan->pelayanans->first()){
            flash('Jenis Pelayanan masih digunakan oleh data pelayanan')->error()->important();
        }else{
             if($jenispelayanan->delete()){
                 $jenispelayanan->pelayanans->each(function($pelayanan){
                     $pelayanan->pelayanan_images->each(function($image){
                        if(File::exists($image->path)){
                            File::delete($image->path);
                        }
                     });
                 });
                flash('Jenis Pelayanan berhasil dihapus')->success();
            }else{
                flash('Jenis Pelayanan gagal dihapus')->error()->important();
            }
        }
        return redirect()->route('jenispelayanans.index');
    }
}
