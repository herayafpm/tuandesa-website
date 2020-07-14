<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Bantuan;
use App\Models\BantuanImage;
use ImageStorage;
use Str;
use App\Charts\BantuanChart;
use Illuminate\Support\Facades\DB;
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
        $bulan = $request->input('bulan');
        $tahun = $request->input('tahun');
        $data = [];
        if(!empty($bulan) && empty($tahun)){
            $tahun = date('Y');
            for ($i=1; $i <= 31; $i++) {
                $data['labels'][] = $i;
                $data['dataset'][] = Bantuan::whereDate('created_at',"$tahun-$bulan-$i")->get()->count();
            }
        }else if(!empty($tahun) && empty($bulan)){
            for ($i=1; $i <= 12; $i++) { 
                $data['labels'][] = $this->getMonth($i);
                $data['dataset'][] = Bantuan::whereYear('created_at',$tahun)->whereMonth('created_at',$i)->get()->count();
            }
        }else if(!empty($tahun) && !empty($bulan)){
            for ($i=1; $i <= 31; $i++) {
                $data['labels'][] = $i;
                $data['dataset'][] = Bantuan::whereDate('created_at',"$tahun-$bulan-$i")->get()->count();
            }
        }if(empty($tahun) && empty($bulan)){
            $bulan = date('m');
            $tahun = date('Y');
            for ($i=1; $i <= 31; $i++) {
                $data['labels'][] = $i;
                $data['dataset'][] = Bantuan::whereDate('created_at',"$tahun-$bulan-$i")->get()->count();
            }
        }
        // var_dump($request->input());
        // $user = $request->user();
        // $bantuanChart = new BantuanChart;
        // $bantuanChart->labels($data['labels']);
        if(!empty($bulan)){
            $data['titleChart'] = "Grafik Bantuan Bulan ".$this->getMonth($bulan)." Tahun ".$tahun;
            // $bantuanChart->dataset('Grafik Bantuan Bulan '.$this->getMonth($bulan).' Tahun '.$tahun, 'bar', $data['dataset'])->color("rgb(255, 99, 132)")->backgroundcolor("rgb(255, 99, 132)");
        }else{
            $data['titleChart'] = "Grafik Bantuan Tahun ".$tahun;
            // $bantuanChart->dataset('Grafik Bantuan Tahun '.$tahun, 'bar', $data['dataset'])->color("rgb(255, 99, 132)")->backgroundcolor("rgb(255, 99, 132)");
        }
        // $bantuanChart->options([
        //         'backgroundColor' => 'green',
        //         'displayLegend' => true,
        //         'tooltip' => [
        //             'show' => true
        //         ]
        //     ]);

        return view('admin.bantuans.laporan', compact('data'));
    }
    private function getMonth($month)
    {
        $data = ["Januari","Februari","Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","November","Desember"];
        return $data[(int)$month - 1];
    }
}