<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect('login');
});

Auth::routes(['register' => false,'verify' => true]);

Route::get('/home', 'HomeController@index')->name('home')->middleware(['auth','verified']);


Route::group(['namespace' => 'Admin','prefix'=>'admin','middleware' => ['auth','verified']],function(){
    Route::get('/','DashboardController@index')->name('admin');
    Route::get('dashboard','DashboardController@index');

    Route::get('profile', 'ProfileController@index')->name('profile.index');
    Route::put('profile', 'ProfileController@update');
    Route::get('profile/zakat/{zakatId}', 'ProfileController@zakat')->name('profile.zakat');
    Route::put('profile/zakat/{zakatId}', 'ProfileController@zakat_store')->name('profile.zakat');
    Route::get('profile/aduan', 'ProfileController@aduan_create');
    Route::post('profile/aduan', 'ProfileController@aduan_store');
    Route::get('profile/pelayanan', 'ProfileController@pelayanan_create');
    Route::post('profile/pelayanan', 'ProfileController@pelayanan_store');
    
    Route::post('profile/likeaduan/{aduanId}', 'ProfileController@likeAduan');
    Route::post('profile/komentaraduan/{aduanId}', 'ProfileController@komentarAduan');
    Route::delete('profile/komentaraduan/{komentarId}', 'ProfileController@komentarAduan_delete');

    Route::get('users/import', 'UserController@import_create');
    Route::post('users/import', 'UserController@import_store');
    Route::resource('users', 'UserController');


    Route::resource('roles', 'RoleController');

    Route::resource('profiledesas', 'ProfileDesaController');
    Route::get('profiledesas/{profileDesaId}/add-image','ProfileDesaController@add_image');
    Route::post('profiledesas/images/{profileDesaId}','ProfileDesaController@post_image');
    Route::delete('profiledesas/images/{imageId}','ProfileDesaController@delete_image');

    Route::resource('jenisaduans', 'JenisAduanController');

    Route::resource('jenispelayanans', 'JenisPelayananController');

    Route::resource('jenisberitas', 'JenisBeritaController');

    Route::resource('jenisbantuans', 'JenisBantuanController');

    // Pelayanan
    Route::resource('pelayanans', 'PelayananController');
    Route::get('pelayanans/{pelayananId}/add-image','PelayananController@add_image');
    Route::post('pelayanans/images/{pelayananId}','PelayananController@post_image');
    Route::delete('pelayanans/images/{imageId}','PelayananController@delete_image');
    // Route::get('laporanpelayanan','LaporanPelayananController@index')->name('laporanpelayanan.index');

    // Aduan
    Route::resource('aduans', 'AduanController');
    Route::get('aduans/{aduanId}/add-image','AduanController@add_image');
    Route::post('aduans/images/{aduanId}','AduanController@post_image');
    Route::delete('aduans/images/{imageId}','AduanController@delete_image');
    // Route::get('laporanaduan','LaporanAduanController@index')->name('laporanaduan.index');

    // Bantuan
    Route::resource('bantuans', 'BantuanController')->only('index','destroy','show');
    // Route::get('laporanaduan','LaporanBantuanController@index')->name('laporanaduan.index');

    // Berita
    Route::resource('beritas', 'BeritaController');
    Route::get('beritas/{beritaId}/add-image','BeritaController@add_image');
    Route::post('beritas/images/{beritaId}','BeritaController@post_image');
    Route::delete('beritas/images/{imageId}','BeritaController@delete_image');
    // Route::get('laporanberitas','LaporanBeritaController@index')->name('laporanberita.index');

    Route::resource('zakats', 'ZakatController');
    Route::delete('zakats/amil/{amilZakat}','ZakatController@amil_destroy');
    Route::get('zakats/{zakatId}/pembagian','ZakatController@pembagian_create');
    Route::post('zakats/{zakatId}/pembagian','ZakatController@pembagian_store');
    Route::delete('zakats/{zakatId}/pembagian/reset','ZakatController@pembagian_reset');


    // SoalJawaban
    Route::resource('soaljawabans', 'SoalJawabanController');
    Route::get('soaljawabans/{soaljawabanId}/add-jawaban','SoalJawabanController@create_jawaban');
    Route::post('soaljawabans/{soalJawabanId}/add-jawaban','SoalJawabanController@store_jawaban');
    Route::get('soaljawabans/{soalJawabanId}/edit-jawaban/{jawabanId}','SoalJawabanController@edit_jawaban');
    Route::put('soaljawabans/{soalJawabanId}/edit-jawaban/{jawabanId}','SoalJawabanController@update_jawaban');
    Route::delete('soaljawabans/{soalJawabanId}/delete-jawaban/{jawabanId}','SoalJawabanController@destroy_jawaban');
});