<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BeritaLike extends Model
{
    protected $fillable = [
        'user_id',
        'berita_id'
    ];
    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id', 'id');
    }
    public function berita()
    {
        return $this->belongsTo('App\Models\Berita', 'berita_id', 'id');
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
