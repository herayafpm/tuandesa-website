<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Aduan;
use App\Models\Pelayanan;
use App\Models\Bantuan;
use App\Models\User;
class DashboardController extends Controller
{
    public function index()
    {
        $aduans = Aduan::get()->count();
        $pelayanans = Pelayanan::get()->count();
        $bantuans = Bantuan::get()->count();
        $users = User::get()->count();
        return view('admin.dashboard.index',compact('users','aduans','pelayanans','bantuans'));
    }
}
