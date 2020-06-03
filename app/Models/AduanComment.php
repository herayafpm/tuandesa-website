<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AduanComment extends Model
{
    protected $fillable = [
        'user_id',
        'aduan_id',
        'komentar'
    ];
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
    public function aduan()
    {
        return $this->belongsTo('App\Models\Aduan');
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
