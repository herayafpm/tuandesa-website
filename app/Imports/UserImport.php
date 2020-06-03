<?php

namespace App\Imports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsFailures;

class UserImport implements ToModel, WithValidation, WithHeadingRow, SkipsOnFailure
{
    use Importable,SkipsFailures;
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $user = new User([
            'name' => $row['nama'],
            'username' => $row['username'],
            'email' => $row['email'],
            'ttl' => $row['ttl'],
            'address' => $row['alamat'],
            'no_hp' => $row['no_hp'],
            'password' => bcrypt(config('app.default_pass')),
            'photo' => 'vendor/adminlte/img/avatar.png',
            'email_verified_at' => date('Y-m-d H:i:s'),
        ]);
        $user->assignRole('User');
        return $user;
    }
    public function  rules(): array {
        return [
            '*.nama' => 'required',
            '*.username' => 'required|unique:users,username',
            '*.email' => 'required|email|unique:users,email',
        ];
    }
}
