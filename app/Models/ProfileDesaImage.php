<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProfileDesaImage extends Model
{
    protected $fillable = [
        'profile_desa_id',
        'path'
    ];
    public $timestamps = false;

    public function profile_desa()
    {
        return $this->belongsTo('App\Models\ProfileDesa');
    }
}
