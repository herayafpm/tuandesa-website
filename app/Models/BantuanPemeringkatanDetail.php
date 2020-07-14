<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BantuanPemeringkatanDetail extends Model
{
    protected $fillable = [
        'bantuan_pemeringkatan_id',
        'bantuan_id',
        'peringkat',
        'total'
    ];

    public function bantuan()
    {
        return $this->belongsTo('App\Models\Bantuan');
    }
    public function bantuan_pemeringkatan()
    {
        return $this->belongsTo('App\Models\BantuanPemeringkatan');
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
