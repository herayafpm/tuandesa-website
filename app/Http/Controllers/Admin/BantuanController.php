<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Bantuan;
use App\Models\BantuanImage;
use ImageStorage;
use Str;
class BantuanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $bantuans = Bantuan::orderBy($request->orderBy ?? 'updated_at',$request->order??'DESC');
        if(!empty($request->date)){
            $date = explode(' - ',urldecode($request->date));
            $starDate = $date[0].' 00:00:00';
            $endDate = $date[1].' 23:59:59';
            $bantuans = $bantuans->whereBetween('created_at',[$starDate,$endDate]);
        }

        if(!empty($request->search)){
            $request->search = strtolower($request->search);
            if(Str::is($request->search, "selesai")){
              $request->search = array_search('Selesai', Bantuan::statuses());
            }else if(Str::is($request->search, "tidak diterima")){
              $request->search = array_search('Tidak Diterima', Bantuan::statuses());
            }else if(Str::is($request->search, "diterima")){
              $request->search = array_search('Diterima', Bantuan::statuses());
            }
            $searchFields = ['id','created_at','status','user.name','jenisbantuan.name'];
            $bantuans->whereLike($searchFields, $request->search);
        }
        $bantuans = $bantuans->paginate(10);
        return view('admin.bantuans.index', compact('bantuans'));
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
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $bantuan = Bantuan::findOrFail($id);
        $bantuan->bantuan_images->each(function($image){
            BantuanImage::findOrFail($image->id);
            $imagePath = substr($image->path,8);
            if($image->delete()){
                ImageStorage::delete($imagePath);
            }
        });
        if($bantuan->delete()){
            flash('Bantuan berhasil dihapus')->success();
        }else{
            flash('Bantuan gagal dihapus')->error()->important();
        }
        return redirect()->route('bantuans.index');
    }
}