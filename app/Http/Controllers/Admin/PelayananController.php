<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Pelayanan;
use App\Models\PelayananImage;
use App\Models\JenisPelayanan;

use App\Http\Requests\PelayananRequest;
use App\Http\Requests\PelayananImageRequest;
use File;
use Str;
use ImageStorage;
use App\Authorizable;
class PelayananController extends Controller
{
    use Authorizable;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $pelayanans = Pelayanan::orderBy($request->orderBy ?? 'updated_at',$request->order??'DESC');
        if(!empty($request->date)){
            $date = explode(' - ',urldecode($request->date));
            $starDate = $date[0].' 00:00:00';
            $endDate = $date[1].' 23:59:59';
            $pelayanans = $pelayanans->whereBetween('created_at',[$starDate,$endDate]);
        }

        if(!empty($request->search)){
            $searchFields = ['id','created_at','status','user.name','jenispelayanan.name'];
            $pelayanans->whereLike($searchFields, $request->search);
        }
        $pelayanans = $pelayanans->paginate(10);
        return view('admin.pelayanans.index', compact('pelayanans'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $users = User::all();
        $jenispelayanans = JenisPelayanan::pluck('name','id');
        return view('admin.pelayanans.form', compact('users','jenispelayanans'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PelayananRequest $request)
    {
        $params = $request->except('_token');
        $pelayanan = Pelayanan::create($params);
        if($pelayanan){
            if($request->has('image')){
                $dataInsert = [];
                foreach ($request['image'] as $image) {
                    $name = $pelayanan->user->name.'_'.Str::random(10).'_'.$pelayanan->jenispelayanan->name.'_'.time();
                    ImageStorage::upload($image,$name);
                    array_push($dataInsert,[
                        'pelayanan_id' => $pelayanan->id,
                        'path' => 'storage/'.$name.'.webp'
                    ]);
                }
                PelayananImage::insert($dataInsert);
            }
            flash('Pelayanan berhasil ditambahkan')->success();
        }else{
            flash('Pelayanan gagal ditambahkan')->error()->important();
        }
        return redirect()->route('pelayanans.index');
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
        $pelayanan = Pelayanan::findOrFail($id);
        $users = User::all();
        $jenispelayanans = JenisPelayanan::pluck('name','id');
        $statuses = Pelayanan::statuses();
        return view('admin.pelayanans.form', compact('users','jenispelayanans','pelayanan','statuses'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $params = $request->except('_token');
        $pelayanan = Pelayanan::findOrFail($id);

        if($pelayanan->update($params)){
            flash('Pelayanan berhasil diubah')->success();
        }else{
            flash('Pelayanan gagal diubah')->error()->important();
        }
        return redirect()->route('pelayanans.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $pelayanan = Pelayanan::findOrFail($id);
        $pelayanan->pelayanan_images->each(function($image){
            $imagePath = substr($image->path,8);
            if($image->delete()){
                ImageStorage::delete($imagePath);
            }
        });
        if($pelayanan->delete()){
            flash('Pelayanan berhasil dihapus')->success();
        }else{
            flash('Pelayanan gagal dihapus')->error()->important();
        }
        return redirect()->route('pelayanans.index');
    }
    public function add_image($id)
    {
        $pelayananId = $id;
        return view('admin.pelayanans.form_image',compact('pelayananId'));
    }
    public function post_image(PelayananImageRequest $request,$id)
    {
        $pelayanan = Pelayanan::findOrFail($id);
        $params = $request->except('_token');
        if($request->has('image')){
            $dataInsert = [];
            foreach ($request['image'] as $image) {
                $name = $pelayanan->user->name.'_'.Str::random(10).'_'.$pelayanan->jenispelayanan->name.'_'.time();
                ImageStorage::upload($image,$name);
                array_push($dataInsert,[
                    'pelayanan_id' => $pelayanan->id,
                    'path' => 'storage/'.$name.'.webp'
                ]);
            }
            if(PelayananImage::insert($dataInsert)){
                flash('Image berhasil ditambahkan')->success();
            }else{
                flash('Image gagal ditambahkan')->error()->important();
            }
            return redirect('admin/pelayanans/'.$pelayanan->id.'/edit');
        }
    }
    protected function uploadImage($image,$pelayanan)
    {
        $name = $pelayanan->user->name.'_'.time();
        $fileName = $name.Str::random(2).'.'.$image->getClientOriginalExtension();
        $folder = 'public/uploads/images/';
        $filePath = $image->move($folder,$fileName);
        return $filePath;
    }
    public function delete_image($id)
    {
        $image = PelayananImage::findOrFail($id);
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