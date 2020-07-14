<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BantuanPemeringkatan extends Model
{
    protected $fillable = [
        'judul',
        'jenis_bantuan_id',
        'start',
        'end'
    ];
    public function jenisbantuan()
    {
        return $this->belongsTo('App\Models\JenisBantuan','jenis_bantuan_id','id');
    }
    public function pemeringkatan()
    {
        return $this->hasMany('App\Models\BantuanPemeringkatanDetail');
    }
}
