<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\ProduksiController;
use App\Http\Controllers\PengaturanController;
use App\Http\Controllers\PemeriksaanQCController;
use App\Http\Controllers\AuthController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    if (Auth::check()) {
        $user = Auth::user();
        if ($user->role === 'admin_produksi') {
            return redirect()->route('produksi.dashboard');
        } elseif ($user->role === 'admin_qc') {
            return redirect()->route('qc.dashboard');
        }
    }
    return redirect('/login');
});

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'login'])->middleware('guest');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

/*
|--------------------------------------------------------------------------
| Dashboard Produksi
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {
    Route::get('/produksi/dashboard', [App\Http\Controllers\ProduksiController::class, 'dashboard'])->name('produksi.dashboard');

    Route::resource('produksi/data-produksi', ProduksiController::class)->names('produksi');
    
    Route::resource('pengaturan-mesin', PengaturanController::class);
});

/*
|--------------------------------------------------------------------------
| Dashboard Quality Control (QC)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {
    Route::get('/qc/dashboard', function () {
        return view('qc.berandaqc');
    })->name('qc.dashboard');

    Route::get('qc/pemeriksaan-qc/get-produksi/{id}', [PemeriksaanQCController::class, 'getProduksi'])->name('qc.pemeriksaan.get-produksi');
    Route::resource('qc/pemeriksaan-qc', PemeriksaanQCController::class)->names('qc.pemeriksaan');

    Route::get('qc/defect-summary', [App\Http\Controllers\QccDefectController::class, 'index'])->name('qc.defect.summary');
    Route::get('qc/analisis-pareto', [App\Http\Controllers\QccDefectController::class, 'pareto'])->name('qc.analisis.pareto');

    // Modul Analisis FMEA
    Route::get('qc/fmea', [App\Http\Controllers\FmeaController::class, 'index'])->name('qc.fmea.index');
    Route::post('qc/fmea/update', [App\Http\Controllers\FmeaController::class, 'update'])->name('qc.fmea.update');

    // Modul Optimasi Taguchi
    Route::get('qc/taguchi', [App\Http\Controllers\TaguchiController::class, 'index'])->name('qc.taguchi.index');

    // Modul Estimasi Kerugian (QLF)
    Route::match(['get', 'post'], 'qc/estimasi-kerugian', [App\Http\Controllers\QlfController::class, 'index'])->name('qc.qlf.index');
});
