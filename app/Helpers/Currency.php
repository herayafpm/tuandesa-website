<?php
namespace App\Helpers;
use Str;
class Currency{
  public static function idr($angka)
  {
    $hasil =  number_format($angka,0, ',' , '.'); 
    return $hasil; 
  }
  public static function pembulatan($uang)
  {
    $uang = ceil($uang);
    $ribuan = substr($uang, -4);
    if($ribuan < 5000){
        $akhir= $uang - $ribuan;
    }else{
        $akhir = $uang +(5000 - $ribuan);
    }
    return $akhir;
    // $ribuan = substr($uang, -4);
    // if($ribuan < 5000){
    //   $akhir = $uang - $ribuan;
    // }else{
    //   $akhir = $uang + (5000-$ribuan);
    // }
    // return $akhir;
  }
  public static function pembulatanBeras($beras)
  {
    $desimal = explode('.',$beras)[1];
    if($desimal < 50){
      $akhir = $beras - (float) ('0.'.$desimal);
    }else{
      $akhir = $beras + (float) ('0.'.(50-$desimal));
      var_dump($akhir);
    }
    return $akhir;
  }
}