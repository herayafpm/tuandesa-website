<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pelayanan extends Model
{
    protected $fillable = [
        'user_id',
        'jenis_pelayanan_id',
        'komentar',
        'status'
    ];
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
    public function jenispelayanan()
    {
        return $this->belongsTo('App\Models\JenisPelayanan','jenis_pelayanan_id','id');
    }
    public static function statuses()
    {
        return [
            0 => 'Proses',
            1 => 'Selesai',
        ];
    }
    public function pelayanan_images()
    {
        return $this->hasMany('App\Models\PelayananImage');
    }
    public function getStatus($stat)
    {
        $status = Pelayanan::statuses()[$stat];
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
