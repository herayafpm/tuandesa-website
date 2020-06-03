<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Aduan;
use App\Models\AduanImage;
use App\Models\JenisAduan;

use App\Http\Requests\AduanRequest;
use App\Http\Requests\AduanImageRequest;
use File;
use Str;

use ImageStorage;
use App\Authorizable;

class AduanController extends Controller
{
    use Authorizable;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $aduans = Aduan::orderBy($request->orderBy ?? 'updated_at',$request->order??'DESC');
        if(!empty($request->date)){
            $date = explode(' - ',urldecode($request->date));
            $starDate = $date[0].' 00:00:00';
            $endDate = $date[1].' 23:59:59';
            $aduans = $aduans->whereBetween('created_at',[$starDate,$endDate]);
        }

        if(!empty($request->search)){
            $request->search = strtolower($request->search);
            if(Str::is($request->search, "selesai")){
              $request->search = array_search('Selesai', Aduan::statuses());
            }else if(Str::is($request->search, "proses")){
              $request->search = array_search('Proses', Aduan::statuses());
            }
            $searchFields = ['id','created_at','status','user.name','jenisaduan.name'];
            $aduans->whereLike($searchFields, $request->search);
        }
        $aduans = $aduans->paginate(10);
        return view('admin.aduans.index', compact('aduans'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $users = User::all();
        $jenisaduans = JenisAduan::pluck('name','id');
        return view('admin.aduans.form', compact('users','jenisaduans'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AduanRequest $request)
    {
        $params = $request->except('_token','image');
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
            flash('Aduan berhasil ditambahkan')->success();
        }else{
            flash('Aduan gagal ditambahkan')->error()->important();
        }
        return redirect()->route('aduans.index');
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
        $aduan = Aduan::findOrFail($id);
        $users = User::all();
        $jenisaduans = JenisAduan::pluck('name','id');
        $statuses = Aduan::statuses();
        return view('admin.aduans.form', compact('users','jenisaduans','aduan','statuses'));
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
        $aduan = Aduan::findOrFail($id);

        if($aduan->update($params)){
            flash('Aduan berhasil diubah')->success();
        }else{
            flash('Aduan gagal diubah')->error()->important();
        }
        return redirect()->route('aduans.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $aduan = Aduan::findOrFail($id);
        $aduan->aduan_images->each(function($image){
            if(File::exists($image->path)){
                File::delete($image->path);
            }
        });
        if($aduan->delete()){
            flash('Aduan berhasil dihapus')->success();
        }else{
            flash('Aduan gagal dihapus')->error()->important();
        }
        return redirect()->route('aduans.index');
    }
    public function add_image($id)
    {
        $aduanId = $id;
        return view('admin.aduans.form_image',compact('aduanId'));
    }
    public function post_image(AduanImageRequest $request,$id)
    {
        $aduan = Aduan::findOrFail($id);
        $params = $request->except('_token');
        if($request->has('image')){
            $dataInsert = [];
            foreach ($params['image'] as $image) {
                $name = $aduan->user->name.'_'.$aduan->jenisaduan->name.'_'.time();
                ImageStorage::upload($image,$name);
                array_push($dataInsert,[
                    'aduan_id' => $aduan->id,
                    'path' => 'storage/'.$name.'.webp'
                ]);
            }
            if(AduanImage::insert($dataInsert)){
                flash('Image berhasil ditambahkan')->success();
            }else{
                flash('Image gagal ditambahkan')->error()->important();
            }
            return redirect('admin/aduans/'.$aduan->id.'/edit');
        }
    }
    protected function uploadImage($image,$aduan)
    {
        $name = $aduan->user->name.'_'.time();
        $fileName = $name.Str::random(2).'.'.$image->getClientOriginalExtension();
        $folder = 'public/uploads/images/';
        $filePath = $image->move($folder,$fileName);
        return $filePath;
    }
    public function delete_image($id)
    {
        $image = AduanImage::findOrFail($id);
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
