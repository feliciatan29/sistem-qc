<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

use App\Http\Controllers\ProduksiController;
use App\Http\Controllers\PengaturanController;
use App\Http\Controllers\PemeriksaanQCController;

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

/*
|--------------------------------------------------------------------------
| Dashboard Quality Control (QC)
|--------------------------------------------------------------------------
*/

Route::get('/qc/dashboard', function () {
    return view('qc.berandaqc');
})->name('qc.dashboard');

/*
|--------------------------------------------------------------------------
| Data Pemeriksaan QC (CRUD)
|--------------------------------------------------------------------------
*/

Route::get('qc/pemeriksaan-qc/get-produksi/{id}', [PemeriksaanQCController::class, 'getProduksi'])->name('qc.pemeriksaan.get-produksi');
Route::resource('qc/pemeriksaan-qc', PemeriksaanQCController::class)->names('qc.pemeriksaan');

Route::get('qc/defect-summary', [App\Http\Controllers\QccDefectController::class, 'index'])->name('qc.defect.summary');
Route::get('qc/analisis-pareto', [App\Http\Controllers\QccDefectController::class, 'pareto'])->name('qc.analisis.pareto');

// Modul Analisis FMEA
Route::get('qc/fmea', [App\Http\Controllers\FmeaController::class, 'index'])->name('qc.fmea.index');
Route::post('qc/fmea/update', [App\Http\Controllers\FmeaController::class, 'update'])->name('qc.fmea.update');
