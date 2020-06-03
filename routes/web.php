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


Auth::routes(['register' => false]);
///// API Area /////
Route::get('api/alumni/{id}/{status}', 'AdministrasiController@api_alumni')->name('api.alumni')->middleware('role:odin|rektor|dekan|admin|humas|admin');
Route::get('/test1', 'AdministrasiController@dttest')->name('dttest');
Route::get('api/akun', 'AdministrasiController@api_akun')->name('api.akun');
///////////////////
Route::get('/logout', function () {
    Auth::logout();
    session()->flush();
    return redirect('/');
})->name('logoutsemua');
Route::get('/', 'DepanController@index')->name('index');
Route::get('/home', 'DepanController@index')->name('beranda');
Route::get('/informasi', 'DepanController@informasi')->name('informasi');
Route::get('/informasi/{id}', 'DepanController@informasi_detil')->name('informasi.detil');
Route::get('/lowongan', 'DepanController@lowongan')->name('lowongan');
Route::get('/lowongan/{id}', 'DepanController@lowongan_detil')->name('lowongan.detil');
Route::get('/alumni', 'DepanController@index')->name('alumni');

Route::prefix('/administrasi')->middleware('role:odin|rektor|dekan|admin|humas')->group(function () {
    Route::get('/', 'AdministrasiController@index')->name('administrasi');
    Route::get('/profil', 'AdministrasiController@profil')->name('administrasi.profil');
    Route::post('/profil/simpan', 'AdministrasiController@profil_simpan')->name('administrasi.profil.simpan');
    Route::get('/alumni/{prodi_id}/{status}', 'AdministrasiController@hal_alumni')->name('administrasi.alumni');
    Route::post('/alumni/simpan', 'AdministrasiController@alumni_simpan')->name('alumni.simpan');
    Route::post('/alumni/hapus', 'AdministrasiController@alumni_hapus')->name('alumni.hapus');
    Route::post('/alumni/edit', 'AdministrasiController@alumni_edit')->name('alumni.edit');
    Route::get('/akun', 'AdministrasiController@akun')->name('administrasi.akun')->middleware('role:odin');
    Route::post('/akun/simpan', 'AdministrasiController@akun_simpan')->name('akun.simpan')->middleware('role:odin');
    Route::post('/akun/hapus', 'AdministrasiController@akun_hapus')->name('akun.hapus')->middleware('role:odin');
    Route::get('/laporan/dikti/{prodi_id}', 'AdministrasiController@lapordikti')->name('lapor.dikti')->middleware('role:odin|admin|humas');
});
Route::prefix('/dikti')->middleware('role:odin|rektor|dekan|admin|humas')->group(function () {
    Route::get('/laporan/{prodi_id}', 'DiktiController@laporandikti')->name('laporan.dikti')->middleware('role:odin|admin|humas');
    Route::get('/import', 'DiktiController@importdikti')->name('dikti.import')->middleware('role:odin|admin|humas');
});
Route::post('/import/alumni', 'AdministrasiController@import_mahasiswa')->name('import.alumni');
Route::post('/import/dikti', 'DiktiController@import_dikti')->name('import.dikti');
/////// Area Mahaisswa //////////
Route::prefix('/alumni/pribadi')->middleware('role:mahasiswa')->group(function () {
    Route::get('/', 'MahasiswaController@profil')->name('alumni.profil');
    Route::post('/profil/simpan', 'MahasiswaController@profil_simpan')->name('alumni.profil.simpan');
});
////// Area Tracer /////
Route::prefix('/tracer')->middleware('role:mahasiswa')->group(function () {
    Route::get('/f1', 'TracerController@tampilf1')->name('tracer.f1');
    Route::post('/f1/simpan', 'TracerController@simpanf1')->name('simpan.f1');
    Route::get('/f2', 'TracerController@tampilf2')->name('tracer.f2');
    Route::post('/f2/simpan', 'TracerController@simpanf2')->name('simpan.f2');
    Route::get('/f3', 'TracerController@tampilf3')->name('tracer.f3');
    Route::post('/f3/simpan', 'TracerController@simpanf3')->name('simpan.f3');
    Route::get('/f4', 'TracerController@tampilf4')->name('tracer.f4');
    Route::post('/f4/simpan', 'TracerController@simpanf4')->name('simpan.f4');
    //f5,f6,f7,f7a
    Route::get('/f5', 'TracerController@tampilf5')->name('tracer.f5');
    Route::post('/f5/simpan', 'TracerController@simpanf5')->name('simpan.f5');
    Route::get('/f8', 'TracerController@tampilf8')->name('tracer.f8');
    Route::post('/f8/simpan', 'TracerController@simpanf8')->name('simpan.f8');
    Route::get('/f9', 'TracerController@tampilf9')->name('tracer.f9');
    Route::post('/f9/simpan', 'TracerController@simpanf9')->name('simpan.f9');
    Route::get('/f10', 'TracerController@tampilf10')->name('tracer.f10');
    Route::post('/f10/simpan', 'TracerController@simpanf10')->name('simpan.f10');
    Route::get('/f11', 'TracerController@tampilf11')->name('tracer.f11');
    Route::post('/f11/simpan', 'TracerController@simpanf11')->name('simpan.f11');
    Route::get('/f16', 'TracerController@tampilf16')->name('tracer.f16');
    Route::post('/f16/simpan', 'TracerController@simpanf16')->name('simpan.f16');
    Route::get('/f17', 'TracerController@tampilf17')->name('tracer.f17');
    Route::post('/f17/simpan', 'TracerController@simpanf17')->name('simpan.f17');
    Route::get('/f99', 'TracerController@tampilf99')->name('tracer.f99');
});
Route::get('/stakeholder', 'TracerController@tampilstakeholder')->name('tracer.stakeholder')->middleware('role:stakeholder');
Route::get('/stakeholder/selesai', 'TracerController@selesaistakeholder')->name('tracer.stakeholder.selesai')->middleware('role:stakeholder');
Route::post('/stakeholder/simpan', 'TracerController@simpanstakeholder')->name('tracer.stakeholder.simpan')->middleware('role:stakeholder');
Route::get('/cetak/tracer', 'CetakController@pdfbukti')->middleware('auth')->name('cetak.tracer');
Route::get('/cetak/stakeholder', 'CetakController@pdfbuktistakeholder')->middleware('auth')->name('cetak.stakeholder');
///////////////////////
Route::prefix('/admnistrasi/statistik')->middleware('role:odin|admin|rektor|humas|dekan')->group(function () {
    Route::get('/{prodi}/{tahunangkatan}/{tahunlulus}', 'StatistikController@rekaptracer')->name('statistik.tracer');
    Route::post('/rs', 'StatistikController@rekaptracer2')->name('statistik.tracer2');
    Route::get('/{prodi_id}', 'StatistikController@index')->name('statistik.index');
});

Route::get('/sh', function () {
    return view('administrasi.stakeholder.index');
});
