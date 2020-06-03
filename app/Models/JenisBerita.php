<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JenisBerita extends Model
{
    protected $fillable = [
        'name'
    ];
    public function beritas()
    {
        return $this->hasMany('App\Models\Berita');
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
