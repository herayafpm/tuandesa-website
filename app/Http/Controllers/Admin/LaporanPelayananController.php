<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Charts\PelayananChart;
use App\Models\Pelayanan;
use \Carbon\Carbon;
use Illuminate\Support\Facades\DB;
class LaporanPelayananController extends Controller
{
    public function index(Request $request)
    {
        $params = $request->all();
        $tipe = true;
        $bulan = date('m');
        $tahun = date('Y');
        if(isset($params['tipe']) && isset($params['date'])){
            $date = explode("-",$params['date']);
            $tipe = (bool) $params['tipe'];
            $bulan = $date[0];
            $tahun = $date[1];
        }
        $borderColors = [
            "rgba(255, 99, 132, 1.0)",
            "rgba(22,160,133, 1.0)",
            "rgba(255, 205, 86, 1.0)",
            "rgba(51,105,232, 1.0)",
            "rgba(244,67,54, 1.0)",
            "rgba(34,198,246, 1.0)",
            "rgba(153, 102, 255, 1.0)",
            "rgba(255, 159, 64, 1.0)",
            "rgba(233,30,99, 1.0)",
            "rgba(205,220,57, 1.0)"
        ];
        $fillColors = [
            "rgba(255, 99, 132, 0.2)",
            "rgba(22,160,133, 0.2)",
            "rgba(255, 205, 86, 0.2)",
            "rgba(51,105,232, 0.2)",
            "rgba(244,67,54, 0.2)",
            "rgba(34,198,246, 0.2)",
            "rgba(153, 102, 255, 0.2)",
            "rgba(255, 159, 64, 0.2)",
            "rgba(233,30,99, 0.2)",
            "rgba(205,220,57, 0.2)"

        ];
        $labels = [];
        $totals = [];
        if($tipe){
            $pelayanans = DB::table('pelayanans')->select('id', 'created_at')
            ->where('created_at','LIKE','%'.$tahun.'%')
            ->get()
            ->groupBy(function($date) {
                //return Carbon::parse($date->created_at)->format('Y'); // grouping by years
                return Carbon::parse($date->created_at)->format('m'); // grouping by months
            })->toArray();
            $month = array_keys($pelayanans);
            for ($i=1; $i <= 12; $i++) {
                array_push($totals,0);
                array_push($labels,Carbon::createFromDate(date('Y'), $i, 1)->translatedFormat('F'));
            }
            foreach($month as $m){
                $total = sizeof($pelayanans[$m]);
                $totals[(int)$m - 1] = $total;
            }
        }else{
            $starDate = Carbon::createFromDate($tahun,$bulan,1)->startOfMonth()->format('Y-m-d H:i:s');
            $endDate = Carbon::createFromDate($tahun,$bulan,1)->lastOfMonth()->format('Y-m-d')." 23:59:59";
            $pelayanans = DB::table('pelayanans')->select('id', 'created_at')
            ->whereBetween('created_at',[$starDate,$endDate])
            ->get()
            ->groupBy(function($date) {
                //return Carbon::parse($date->created_at)->format('Y'); // grouping by years
                return Carbon::parse($date->created_at)->translatedFormat('d F Y'); // grouping by months
            })->toArray();
            $lastDay = Carbon::createFromDate($tahun,$bulan,1)->lastOfMonth()->format('d');
            for ($i=1; $i <= (int) $lastDay ; $i++) {
                array_push($labels,Carbon::createFromDate($tahun,$bulan,$i)->translatedFormat('d F Y'));
                array_push($totals,0);
            }
            $pelayananKeys = array_keys($pelayanans);
            $no = 0;
            foreach ($pelayanans as $pelayanan) {
                $keySplit = explode(' ',$pelayananKeys[$no])[0];
                $totals[(int) $keySplit - 1] = sizeof($pelayanan);
                $no++;
            }
        }
        $newBulan = Carbon::createFromDate($tahun,$bulan,1)->translatedFormat('F');
        $title = (isset($params['tipe']) && !(bool)$params['tipe'])?" Bulan $newBulan":"";
        $pelayananChart = new PelayananChart;
        $pelayananChart->title("Laporan Grafik Pelayanan".$title." Tahun $tahun");
        $pelayananChart->labels($labels)->barWidth(0.5);
        $pelayananChart->dataset('Jumlah Data', 'bar', $totals)
            ->color($borderColors)
            ->backgroundcolor($fillColors)->options(['minBarLength'=>10]);
        return view('admin.pelayanans.laporanchart', [ 'pelayananChart' => $pelayananChart ] );
    }
}
