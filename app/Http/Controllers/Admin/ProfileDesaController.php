<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProfileDesa;
use App\Models\ProfileDesaImage;

use App\Http\Requests\ProfileDesaRequest;
use ImageStorage;
use App\Authorizable;

class ProfileDesaController extends Controller
{
    use Authorizable;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $profiledesas = ProfileDesa::latest()->paginate(10);
        return view('admin.profiledesas.index',compact('profiledesas'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.profiledesas.form');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProfileDesaRequest $request)
    {
        $params = $request->except('_token');
        if(ProfileDesa::create($params)){
            flash('Profile desa berhasil ditambahkan')->success();
        }else{
            flash('Profile desa gagal ditambahkan')->error()->important();
        }
        return redirect()->route('profiledesas.index');
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
        $profiledesa = ProfileDesa::findOrFail($id);
        return view('admin.profiledesas.form',compact('profiledesa'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ProfileDesaRequest $request, $id)
    {
        $params = $request->except('_token');
        $profiledesa = ProfileDesa::findOrFail($id);

        if($profiledesa->update($params)){
            flash('Profile desa berhasil diubah')->success();
        }else{
            flash('Profile desa gagal diubah')->error()->important();
        }
        return redirect()->route('profiledesas.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $profiledesa = ProfileDesa::findOrFail($id);
        if($profiledesa->delete()){
            flash('Profile desa berhasil dihapus')->success();
        }else{
            flash('Profile desa gagal dihapus')->error()->important();
        }
        return redirect()->route('profiledesas.index');
    }

    public function add_image($id)
    {
        $profiledesaId = $id;
        return view('admin.profiledesas.form_image',compact('profiledesaId'));
    }
    public function post_image(Request $request,$id)
    {
        $profiledesa = ProfileDesa::findOrFail($id);
        if($request->has('image')){
            $image = $request->file('image');
            $name = $profiledesa->judul.'_'.time();
            ImageStorage::upload($image,$name);
            $params = [
                'profile_desa_id' => $profiledesa->id,
                'path' => 'storage/'.$name.'.webp'
            ];
            if(ProfileDesaImage::create($params)){
                flash('Image berhasil ditambahkan')->success();
            }else{
                flash('Image gagal ditambahkan')->error()->important();
            }
            return redirect('admin/profiledesas/'.$profiledesa->id.'/edit');
        }
    }
    public function delete_image($id)
    {
        $image = ProfileDesaImage::findOrFail($id);
        $imagePath = substr($image->path,8);
        if($image->delete()){
            ImageStorage::delete($imagePath);
            flash('Image berhasil dihapus')->success();
        }else{
            flash('Image gagal dihapus')->error()->important();
        }
        return redirect()->back();   
    }
}
