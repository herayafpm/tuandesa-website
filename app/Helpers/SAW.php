<?php
namespace App\Helpers;
class SAW{
  public static function getRecomendation($data,$kriteria)
  {
    $getMinMax = SAW::getMinMax($data,$kriteria);
    $normalisasi = SAW::normalisasi($data,$getMinMax);
    $hitungBobotPeringkat = SAW::hitungPeringkat($data,$normalisasi,$kriteria);
    return $hitungBobotPeringkat;
  }
  private static function getMinMax($data,$kriteria)
  {
    $nilaiKriteria = [];
    foreach ($data as $d) {
     $no=0;
     foreach ($d['hasil'] as $nilai) {
       $nilaiKriteria[$no][] = $nilai;
       $no++;
     }
    }
    $hasil = [];
    $no2=0;
    foreach ($kriteria as $k) {
      if($k['tipe'] == 0){
        $hasil[$no2] = [
          'tipe'=> $k['tipe'],
          'min'=>min($nilaiKriteria[$no2])
        ];
      }else if($k['tipe'] == 1){
        $hasil[$no2] = [
          'tipe'=> $k['tipe'],
          'max'=>max($nilaiKriteria[$no2])
        ];
      }
      $no2++;
    }
    return $hasil;
  }
  private static function normalisasi($data,$minmax)
  {
    $no =0;
    $hasil = [];
    foreach ($data as $d) {
      $no2 = 0;
     foreach ($d['hasil'] as $nilai) {
       if($minmax[$no2]['tipe'] == 0){
         $hasil[$no][] = ($minmax[$no2]['min']/$nilai);
       }
       else if($minmax[$no2]['tipe'] == 1){
        $hasil[$no][] = ($nilai/$minmax[$no2]['max']);
       }
       $no2++;
     }
     $no++;
    }
    return $hasil;
  }
  private static function hitungPeringkat($data,$normalisasi,$kriteria)
  {
    $hasil = [];
    $no=0;
    foreach ($normalisasi as $normal) {
      $no2=0;
      $total = 0;
      // $soals = [];
      foreach ($normal as $nilai) {
        $total += ($nilai * $kriteria[$no2]['bobot']);
        // array_push($soals,$kriteria[$no2]['soal']);
        $no2++;
      }
      array_push($hasil,[
        'bantuan_id'=>$data[$no]['id'],
        // 'user_id'=>$data[$no]['user_id'],
        // 'soal' => $soals,
        // 'jawaban' => $data[$no]['jawabans'],
        'total'=> round($total,4)
      ]);
      $no++;
    }
    $total = array_column($hasil, 'total');
    array_multisort($total, SORT_DESC, $hasil);
    return $hasil;
  }
}
?>