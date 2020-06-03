<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::post('login', 'Api\UserController@login');

Route::group(['namespace' => 'Api','middleware' => 'auth:api','prefix'=>'profile'], function(){
    Route::get('/', 'UserController@profile');
    Route::put('/', 'UserController@update');
    Route::get('aduans', 'UserController@aduans');
    Route::get('pelayanans', 'UserController@pelayanans');
    Route::get('last_login', function () {
        $user = Auth::user();
        $now = date('Y-m-d H:i:s');
        $user->update(['last_login' => $now]);
        return response()->json([
            'status' => true,
            'messages' => "Berhasil masuk!",
            'data' => $user
        ], 200);
    });
});
Route::group(['namespace' => 'Api','middleware' => 'auth:api','prefix'=>'aduan'], function(){
    Route::get('/', 'AduanController@index');
    Route::post('/', 'AduanController@store');
    Route::get('/jenis', 'AduanController@jenis');
    Route::get('/{aduanId}', 'AduanController@show');
    Route::post('/{aduanId}/like', 'AduanController@likeAduan');
    Route::post('/{aduanId}/comment', 'AduanController@komentarAduan');
    Route::delete('/comment/{commentId}', 'AduanController@komentarDelete');
});

Route::group(['namespace' => 'Api','middleware' => 'auth:api','prefix'=>'pelayanan'], function(){
    Route::get('/', 'PelayananController@index');
    Route::post('/', 'PelayananController@store');
    Route::get('/jenis', 'PelayananController@jenis');
    Route::get('/{pelayananId}', 'PelayananController@show');
});

Route::group(['namespace' => 'Api','middleware' => 'auth:api','prefix'=>'bantuan'], function(){
    Route::get('/', 'BantuanController@index');
    Route::post('/', 'BantuanController@store');
    Route::get('/jenis', 'BantuanController@jenis');
    Route::get('/{bantuanId}', 'BantuanController@show');
    Route::get('/soaljawaban/{jenisbantuanId}', 'BantuanController@soaljawaban');
});

Route::group(['namespace' => 'Api','middleware' => 'auth:api','prefix'=>'berita'], function(){
    Route::get('/', 'BeritaController@index');
    Route::post('/', 'BeritaController@store');
    Route::get('/jenis', 'BeritaController@jenis');
    Route::get('/{BeritaId}', 'BeritaController@show');
});

Route::group(['namespace' => 'Api','middleware' => 'auth:api','prefix'=>'zakat'], function(){
    Route::get('/', 'ZakatController@index');
    Route::post('/{ZakatId}', 'ZakatController@store');
    Route::get('/{ZakatId}', 'ZakatController@show');
});



Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
