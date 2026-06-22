<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProduksiController;

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
    return view('produksi.beranda');
});

Route::get('/produksi/dashboard', function () {
    return view('produksi.beranda');
})->name('produksi.dashboard');

Route::get('/produksi/data-produksi', [ProduksiController::class, 'index'])
    ->name('produksi.data');


