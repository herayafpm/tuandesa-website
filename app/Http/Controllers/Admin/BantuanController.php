<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Bantuan;
use App\Models\BantuanImage;
use App\Models\JenisBantuan;
use ImageStorage;
use Str;
use App\Charts\BantuanChart;
use Illuminate\Support\Facades\DB;
use Excel;
use App\Exports\BantuanExport;
class BantuanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $bantuans = Bantuan::orderBy($request->orderBy ?? 'status',$request->order??'ASC');
        if(!empty($request->date)){
            $date = explode(' - ',urldecode($request->date));
            $starDate = $date[0].' 00:00:00';
            $endDate = $date[1].' 23:59:59';
            $bantuans = $bantuans->whereBetween('created_at',[$starDate,$endDate]);
        }

        if(!empty($request->search)){
            // $request->search = strtolower($request->search);
            // if(Str::is($request->search, "selesai")){
            //   $request->search = array_search('Selesai', Bantuan::statuses());
            // }else if(Str::is($request->search, "tidak diterima")){
            //   $request->search = array_search('Tidak Diterima', Bantuan::statuses());
            // }else if(Str::is($request->search, "diterima")){
            //   $request->search = array_search('Diterima', Bantuan::statuses());
            // }
            $searchFields = ['user.name','user.username'];
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
        $bantuan = Bantuan::findOrFail($id);
        $statuses = Bantuan::statuses();
        return view('admin.bantuans.detail',compact('bantuan','statuses'));
    }
    public function update(Request $request, $id)
    {
        $params = $request->except('_token');
        $bantuan = Bantuan::findOrFail($id);
        if($bantuan->update($params)){
            flash('Status Bantuan Sosial berhasil diubah')->success();
        }else{
            flash('Status Bantuan Sosial gagal diubah')->error()->important();
        }
        return redirect()->route('bantuans.show',$id);
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

    public function laporan(Request $request)
    {
        if($request->user()->can('view_bantuans') == false){
             return redirect()->route('admin');
        }
        $jenis_bantuan = $request->input('jenis_bantuan_id');
        $status = $request->input('status');
        $bulan = $request->input('bulan');
        $tahun = $request->input('tahun');
        $data = [];
        if(!empty($bulan) && empty($tahun)){
            $tahun = date('Y');
            for ($i=1; $i <= 31; $i++) {
                $bantuan = (empty($jenis_bantuan))?Bantuan::with('jenisbantuan'):Bantuan::where('jenis_bantuan_id',$jenis_bantuan);
                $bantuan = (empty($status))?$bantuan:$bantuan->where('status',$status);
                $data['labels'][] = $i;
                $data['dataset'][] = $bantuan->whereDate('created_at',"$tahun-$bulan-$i")->get()->count();
            }
        }else if(!empty($tahun) && empty($bulan)){
            for ($i=1; $i <= 12; $i++) { 
                $bantuan = (empty($jenis_bantuan))?Bantuan::with('jenisbantuan'):Bantuan::where('jenis_bantuan_id',$jenis_bantuan);
                $bantuan = (empty($status))?$bantuan:$bantuan->where('status',$status);
                $data['labels'][] = $this->getMonth($i);
                $data['dataset'][] = $bantuan->whereYear('created_at',$tahun)->whereMonth('created_at',$i)->get()->count();
            }
        }else if(!empty($tahun) && !empty($bulan)){
            for ($i=1; $i <= 31; $i++) {
                $bantuan = (empty($jenis_bantuan))?Bantuan::with('jenisbantuan'):Bantuan::where('jenis_bantuan_id',$jenis_bantuan);
                $bantuan = (empty($status))?$bantuan:$bantuan->where('status',$status);
                $data['labels'][] = $i;
                $data['dataset'][] = $bantuan->whereDate('created_at',"$tahun-$bulan-$i")->get()->count();
            }
        }if(empty($tahun) && empty($bulan)){
            $bulan = date('m');
            $tahun = date('Y');
            for ($i=1; $i <= 31; $i++) {
                $bantuan = (empty($jenis_bantuan))?Bantuan::with('jenisbantuan'):Bantuan::where('jenis_bantuan_id',$jenis_bantuan);
                $bantuan = (empty($status))?$bantuan:$bantuan->where('status',$status);
                $data['labels'][] = $i;
                $data['dataset'][] = $bantuan->whereDate('created_at',"$tahun-$bulan-$i")->get()->count();
            }
        }
        $statuses = Bantuan::statuses();
        $jenis_bantuans = JenisBantuan::pluck('name','id');
        $jenis_bantuan = (empty($jenis_bantuan)) ? 'Semua Jenis Bantuan': JenisBantuan::where('id',$jenis_bantuan)->pluck('name')->first();
        $status = (empty($status)) ? 'Semua Status': 'Status '.$statuses[$status];
        if(!empty($bulan)){
            $data['titleChart'] = "Grafik Bantuan ".$jenis_bantuan." ".$status." Bulan ".$this->getMonth($bulan)." Tahun ".$tahun;
        }else{
            $data['titleChart'] = "Grafik Bantuan ".$jenis_bantuan." ".$status." Tahun ".$tahun;
        }
        return view('admin.bantuans.laporan', compact('data','statuses','jenis_bantuans'));
    }
    public function laporanexcel(Request $request)
    {
        $jenis_bantuan = $request->input('jenis_bantuan_id');
        $status = $request->input('status');
        $bulan = $request->input('bulan');
        $tahun = $request->input('tahun');
        $data = [];
        if(empty($bulan) && empty($tahun)){
            $bantuan = (empty($jenis_bantuan))?Bantuan::with('jenisbantuan','user'):Bantuan::where('jenis_bantuan_id',$jenis_bantuan);
            $bantuan = (empty($status))?$bantuan:$bantuan->where('status',$status);
            $data = $bantuan->get();
        }else if(!empty($bulan) && !empty($tahun)){
            $bantuan = (empty($jenis_bantuan))?Bantuan::with('jenisbantuan','user'):Bantuan::where('jenis_bantuan_id',$jenis_bantuan);
            if(!is_null($status)){
                $bantuan = $bantuan->where('status',$status);
            }
            $data = $bantuan->whereYear('created_at',$tahun)->whereMonth('created_at',$bulan)->get();
        }
        $statuses = Bantuan::statuses();
        $bantuans = [["No", "Nama Lengkap", "Username","Jenis Bantuan","Status","Tanggal Diajukan"]];
        $no = 1;
        foreach ($data as $d) {
            $bantuan = [$no,$d->user->name,$d->user->username,$d->jenisbantuan->name,$statuses[$d->status],$d->created_at];
            array_push($bantuans,$bantuan);
            $no++;
        }
        $jenis_bantuan = (empty($jenis_bantuan)) ? 'Semua Jenis Bantuan': JenisBantuan::where('id',$jenis_bantuan)->pluck('name')->first();
        $status = (empty($status)) ? 'Semua Status': 'Status '.$statuses[$status];
        $title = "Laporan Bantuan ".$jenis_bantuan." ".$status." ".((!empty($bulan) && !empty($tahun))?"Bulan ".$this->getMonth($bulan)." Tahun ".$tahun:"");
        $export = new BantuanExport($bantuans);
    
        return Excel::download($export, "$title.csv");
    }
    private function getMonth($month)
    {
        $data = ["Januari","Februari","Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","November","Desember"];
        return $data[(int)$month - 1];
    }
}