<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\JenisBerita;

use App\Http\Requests\JenisBeritaRequest;
use File;
use App\Models\BeritaImage;

class JenisBeritaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $jenisberitas = JenisBerita::orderBy($request->orderBy ?? 'updated_at',$request->order??'DESC');
        if(!empty($request->date)){
            $date = explode(' - ',urldecode($request->date));
            $starDate = $date[0].' 00:00:00';
            $endDate = $date[1].' 23:59:59';
            $jenisberitas = $jenisberitas->whereBetween('created_at',[$starDate,$endDate]);
        }

        if(!empty($request->search)){
            $searchFields = ['name','created_at'];
            $jenisberitas->whereLike($searchFields, $request->search);
        }
        $jenisberitas = $jenisberitas->paginate(10);
        return view('admin.jenisberitas.index',compact('jenisberitas'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.jenisberitas.form');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(JenisBeritaRequest $request)
    {
        $params = $request->except('_token');
        if(JenisBerita::create($params)){
            flash('Jenis Berita berhasil ditambahkan')->success();
        }else{
            flash('Jenis Berita gagal ditambahkan')->error()->important();
        }
        return redirect()->route('jenisberitas.index');
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
        $jenisberita = JenisBerita::findOrFail($id);
        return view('admin.jenisberitas.form',compact('jenisberita'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(JenisBeritaRequest $request, $id)
    {
        $params = $request->except('_token');
        $Jenisberita = JenisBerita::findOrFail($id);

        if($Jenisberita->update($params)){
            flash('Jenis Berita berhasil diubah')->success();
        }else{
            flash('Jenis Berita gagal diubah')->error()->important();
        }
        return redirect()->route('jenisberitas.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $jenisberita = JenisBerita::findOrFail($id);
        if($jenisberita->beritas->first()){
            flash('Jenis Berita masih digunakan oleh data Berita')->error()->important();
        }else{
             if($jenisberita->delete()){
                 $jenisberita->beritas->each(function($berita){
                     $berita->berita_images->each(function($image){
                        if(File::exists($image->path)){
                            File::delete($image->path);
                        }
                     });
                 });
                flash('Jenis Berita berhasil dihapus')->success();
            }else{
                flash('Jenis Berita gagal dihapus')->error()->important();
            }
        }
        return redirect()->route('jenisberitas.index');
    }
}
