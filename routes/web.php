<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

use App\Http\Controllers\ProduksiController;
use App\Http\Controllers\PengaturanController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application.
|
*/

/*
|--------------------------------------------------------------------------
| Halaman Awal
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('produksi.beranda');
});

/*
|--------------------------------------------------------------------------
| Dashboard Produksi
|--------------------------------------------------------------------------
*/

Route::get('/produksi/dashboard', function () {
    return view('produksi.beranda');
})->name('produksi.dashboard');

/*
|--------------------------------------------------------------------------
| Data Produksi (CRUD)
|--------------------------------------------------------------------------
*/

Route::resource(
    'produksi/data-produksi',
    ProduksiController::class
)->names('produksi');

/*
|--------------------------------------------------------------------------
| Pengaturan Mesin (CRUD)
|--------------------------------------------------------------------------
*/

Route::resource(
    'pengaturan-mesin',
    PengaturanController::class
);
