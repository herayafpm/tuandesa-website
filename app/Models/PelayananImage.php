<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PelayananImage extends Model
{
    protected $fillable = [
        'pelayanan_id',
        'path'
    ];
}
