<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Zakat extends Model
{
    protected $fillable = [
        'name',
        'start',
        'end'
    ];

    public function amils()
    {
        return $this->hasMany('App\Models\ZakatAmil');
    }
    public function pembagians()
    {
        return $this->hasMany('App\Models\ZakatPembagian');
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
