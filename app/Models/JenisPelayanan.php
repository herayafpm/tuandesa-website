<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JenisPelayanan extends Model
{
    protected $fillable = [
        'name'
    ];
    public function pelayanans()
    {
        return $this->hasMany('App\Models\Pelayanan');
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
