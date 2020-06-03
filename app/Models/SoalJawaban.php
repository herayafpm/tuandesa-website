<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SoalJawaban extends Model
{
    protected $fillable = [
        'jenis_bantuan_id',
        'soal',
        'bobot',
        'tipe'
    ];
    public function jawabans()
    {
        return $this->hasMany('App\Models\Jawaban');
    }
    public static function tipes()
    {
        return [
            0 => 'Cost',
            1 => 'Benefit',
        ];
    }
    public function getTipes($tipe)
    {
        $tipes = $this->tipes()[$tipe];
        return $tipes;
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
