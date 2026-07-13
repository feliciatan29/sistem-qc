<?php

namespace App\Http\Controllers;

use App\Models\DataProduksi;
use App\Models\PengaturanMesin;
use Illuminate\Http\Request;

class PengaturanController extends Controller
{
    public function index()
    {
        $pengaturan = PengaturanMesin::all();

        return view('produksi.pengaturan_mesin.index', compact('pengaturan'));
    }

    public function create()
    {
        $dataproduksi = DataProduksi::all();

        return view('produksi.pengaturan_mesin.create', compact('dataproduksi'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode_mesin'   => 'required|string|max:255|unique:tbl_pengaturan,kode_mesin',
            'jenis_jaring' => 'required|string|max:255',
            'ukuran_jaring'=> 'required|string|max:255',
            'MD_jaring'    => 'required|numeric|min:0',
            'RPM_jaring'   => 'required|numeric|min:0',
            'status'       => 'required|in:Aktif,Nonaktif',
        ]);

        PengaturanMesin::create($validated);

        return redirect()
            ->route('pengaturan-mesin.index')
            ->with('success', 'Data Pengaturan Mesin berhasil ditambahkan.');
    }

    public function show(PengaturanMesin $pengaturan)
    {
        //
    }

    public function edit(PengaturanMesin $pengaturan_mesin)
    {
        $pengaturan = $pengaturan_mesin;
        return view('produksi.pengaturan_mesin.edit', compact('pengaturan'));
    }

    public function update(Request $request, PengaturanMesin $pengaturan_mesin)
    {
        $validated = $request->validate([
            'kode_mesin'   => 'required|string|max:255|unique:tbl_pengaturan,kode_mesin,' . $pengaturan_mesin->id,
            'jenis_jaring' => 'required|string|max:255',
            'ukuran_jaring'=> 'required|string|max:255',
            'MD_jaring'    => 'required|numeric|min:0',
            'RPM_jaring'   => 'required|numeric|min:0',
            'status'       => 'required|in:Aktif,Nonaktif',
        ]);

        $pengaturan_mesin->update($validated);

        return redirect()
            ->route('pengaturan-mesin.index')
            ->with('success', 'Data Pengaturan Mesin berhasil diperbarui.');
    }

    public function destroy(PengaturanMesin $pengaturan_mesin)
    {
        $pengaturan_mesin->delete();

        return redirect()
            ->route('pengaturan-mesin.index')
            ->with('success', 'Data Pengaturan Mesin berhasil dihapus.');
    }
}
