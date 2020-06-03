<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Berita;
use App\Models\BeritaImage;
use App\Models\JenisBerita;

use App\Http\Requests\BeritaRequest;
use App\Http\Requests\BeritaImageRequest;
use File;
use Str;
use ImageStorage;
use App\Authorizable;

class BeritaController extends Controller
{
    use Authorizable;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $beritas = Berita::orderBy($request->orderBy ?? 'updated_at',$request->order??'DESC');
        if(!empty($request->date)){
            $date = explode(' - ',urldecode($request->date));
            $starDate = $date[0].' 00:00:00';
            $endDate = $date[1].' 23:59:59';
            $beritas = $beritas->whereBetween('created_at',[$starDate,$endDate]);
        }

        if(!empty($request->search)){
            $request->search = strtolower($request->search);
            if(Str::is($request->search, "selesai")){
              $request->search = array_search('Selesai', Aduan::statuses());
            }else if(Str::is($request->search, "proses")){
              $request->search = array_search('Proses', Aduan::statuses());
            }
            $searchFields = ['id','created_at','status','user.name','jenisaduan.name'];
            $beritas->whereLike($searchFields, $request->search);
        }
        $beritas = $beritas->paginate(10);
        return view('admin.beritas.index', compact('beritas'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $jenisberitas = JenisBerita::pluck('name','id');
        return view('admin.beritas.form', compact('jenisberitas'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(BeritaRequest $request)
    {
        $params = $request->except('_token','image');
        $berita = Berita::create($params);
        if($berita){
            if($request->has('image')){
                $dataInsert = [];
                foreach ($request['image'] as $image) {
                    $name = auth()->user()->username.'_'.$berita->jenisberita->name.'_'.time();
                    ImageStorage::upload($image,$name);
                    array_push($dataInsert,[
                        'berita_id' => $berita->id,
                        'path' => 'storage/'.$name.'.webp'
                    ]);
                }
                BeritaImage::insert($dataInsert);
            }
            flash('Berita berhasil ditambahkan')->success();
        }else{
            flash('Berita gagal ditambahkan')->error()->important();
        }
        return redirect()->route('beritas.index');
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
        $berita = Berita::findOrFail($id);
        $jenisberitas = JenisBerita::pluck('name','id');
        $statuses = Berita::statuses();
        return view('admin.beritas.form', compact('jenisberitas','berita','statuses'));
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
        $berita = Berita::findOrFail($id);

        if($berita->update($params)){
            flash('Berita berhasil diubah')->success();
        }else{
            flash('Berita gagal diubah')->error()->important();
        }
        return redirect()->route('beritas.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $berita = Berita::findOrFail($id);
        $berita->berita_images->each(function($image){
            if(File::exists($image->path)){
                File::delete($image->path);
            }
        });
        if($berita->delete()){
            flash('Berita berhasil dihapus')->success();
        }else{
            flash('Berita gagal dihapus')->error()->important();
        }
        return redirect()->route('beritas.index');
    }
    public function add_image($id)
    {
        $beritaId = $id;
        return view('admin.beritas.form_image',compact('beritaId'));
    }
    public function post_image(BeritaImageRequest $request,$id)
    {
        $berita = Berita::findOrFail($id);
        $params = $request->except('_token');
        if($request->has('image')){
            $dataInsert = [];
            foreach ($request['image'] as $image) {
                $name = auth()->user()->username.'_'.$berita->jenisberita->name.'_'.time();
                ImageStorage::upload($image,$name);
                array_push($dataInsert,[
                    'berita_id' => $berita->id,
                    'path' => 'storage/'.$name.'.webp'
                ]);
            }
            if(BeritaImage::insert($dataInsert)){
                flash('Image berhasil ditambahkan')->success();
            }else{
                flash('Image gagal ditambahkan')->error()->important();
            }
            return redirect('admin/beritas/'.$berita->id.'/edit');
        }
    }
    protected function uploadImage($image,$berita)
    {
        $name = $berita->user->name.'_'.time();
        $fileName = $name.Str::random(2).'.'.$image->getClientOriginalExtension();
        $folder = 'public/uploads/images/';
        $filePath = $image->move($folder,$fileName);
        return $filePath;
    }
    public function delete_image($id)
    {
        $image = BeritaImage::findOrFail($id);
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
