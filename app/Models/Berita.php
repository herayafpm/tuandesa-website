<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Berita extends Model
{
    protected $fillable = [
        'user_id',
        'jenis_berita_id',
        'komentar',
        'status'
    ];
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
    public function jenisberita()
    {
        return $this->belongsTo('App\Models\JenisBerita','jenis_berita_id','id');
    }
    public static function statuses()
    {
        return [
            0 => 'Proses',
            1 => 'Selesai',
        ];
    }
    public function berita_images()
    {
        return $this->hasMany('App\Models\BeritaImage');
    }
    public function getStatus($stat)
    {
        $status = Berita::statuses()[$stat];
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
