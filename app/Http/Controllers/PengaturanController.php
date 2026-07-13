<?php

namespace App\Http\Controllers;

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
        return view('produksi.pengaturan_mesin.create');
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

    public function edit(PengaturanMesin $pengaturan)
    {
        return view('produksi.pengaturan_mesin.edit', compact('pengaturan'));
    }

    public function update(Request $request, PengaturanMesin $pengaturan)
    {
        $validated = $request->validate([
            'kode_mesin'   => 'required|string|max:255|unique:tbl_pengaturan,kode_mesin,' . $pengaturan->id,
            'jenis_jaring' => 'required|string|max:255',
            'ukuran_jaring'=> 'required|string|max:255',
            'MD_jaring'    => 'required|numeric|min:0',
            'RPM_jaring'   => 'required|numeric|min:0',
            'status'       => 'required|in:Aktif,Nonaktif',
        ]);

        $pengaturan->update($validated);

        return redirect()
            ->route('pengaturan-mesin.index')
            ->with('success', 'Data Pengaturan Mesin berhasil diperbarui.');
    }

    public function destroy(PengaturanMesin $pengaturan)
    {
        $pengaturan->delete();

        return redirect()
            ->route('pengaturan-mesin.index')
            ->with('success', 'Data Pengaturan Mesin berhasil dihapus.');
    }
}
