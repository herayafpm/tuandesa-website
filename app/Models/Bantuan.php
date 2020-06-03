<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bantuan extends Model
{
    protected $fillable = [
        'user_id',
        'jenis_bantuan_id',
        'komentar',
        'status'
    ];
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
    public function jenisbantuan()
    {
        return $this->belongsTo('App\Models\JenisBantuan','jenis_bantuan_id','id');
    }
    public static function statuses()
    {
        return [
            0 => 'Proses',
            1 => 'Tidak Diterima',
            2 => 'Diterima',
        ];
    }
    public function bantuan_images()
    {
        return $this->hasMany('App\Models\BantuanImage');
    }
    public function bantuan_jawabans()
    {
        return $this->hasMany('App\Models\BantuanJawaban');
    }
    public function getStatus($stat)
    {
        $status = $this->statuses()[$stat];
        return $status;
    }
    public function getCreatedAtAttribute()
    {
        return \Carbon\Carbon::parse($this->attributes['created_at'])
        ->translatedFormat('d F Y H:i:s');
    }

    public function getUpdatedAtAttribute()
    {
        return \Carbon\Carbon::parse($this->attributes['updated_at'])
        ->diffForHumans();
    }
}
