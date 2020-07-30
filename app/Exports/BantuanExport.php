<?php

namespace App\Exports;

use App\Models\Bantuan;
use Maatwebsite\Excel\Concerns\FromArray;

class BantuanExport implements FromArray
{
    protected $bantuan;
    /**
    * @return \Illuminate\Support\Collection
    */
    public function __construct(array $bantuan) {
        $this->bantuan = $bantuan;
    }
    public function array(): array
    {
        return $this->bantuan;
    }
}
