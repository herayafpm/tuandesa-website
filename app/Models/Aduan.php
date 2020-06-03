<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Aduan extends Model
{
    protected $fillable = [
        'user_id',
        'jenis_aduan_id',
        'komentar',
        'status'
    ];
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
    public function jenisaduan()
    {
        return $this->belongsTo('App\Models\JenisAduan','jenis_aduan_id','id');
    }
    public static function statuses()
    {
        return [
            0 => 'Proses',
            1 => 'Selesai',
        ];
    }

    public function comments()
    {
        return $this->hasMany('App\Models\AduanComment');
    }
    public function likes()
    {
        return $this->hasMany('App\Models\AduanLike');
    }
    public function aduan_images()
    {
        return $this->hasMany('App\Models\AduanImage');
    }
    public function getStatus($stat)
    {
        $status = Aduan::statuses()[$stat];
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
