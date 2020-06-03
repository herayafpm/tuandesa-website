<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BantuanJawaban extends Model
{
    protected $fillable = [
        'bantuan_id',
        'soal_jawaban_id',
        'jawaban_id',
    ];
    public function bantuan()
    {
        return $this->belongsTo('App\Models\Bantuan');
    }
    public function soal_jawaban()
    {
        return $this->belongsTo('App\Models\SoalJawaban','soal_jawaban_id','id');
    }
    public function jawaban()
    {
        return $this->belongsTo('App\Models\Jawaban','jawaban_id','id');
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
