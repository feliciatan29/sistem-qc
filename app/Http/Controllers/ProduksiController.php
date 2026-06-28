<?php

namespace App\Http\Controllers;

use App\Models\DataProduksi;
use Illuminate\Http\Request;

class ProduksiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $produksi = DataProduksi::latest()->paginate(10);

        return view('produksi.data_produksi.index', compact('produksi'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('produksi.data_produksi.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'jenis_jaring'     => 'required|string|max:255',
            'bulan_produksi'   => 'required',
            'jumlah_pesanan'   => 'required|numeric|min:1',
            'status'           => 'required|in:Aktif,Proses,Nonaktif',
        ]);

        DataProduksi::create([
            'jenis_jaring'     => $request->jenis_jaring,
            'bulan_produksi'   => $request->bulan_produksi,
            'jumlah_pesanan'   => $request->jumlah_pesanan,
            'status'           => $request->status,
        ]);

        return redirect()
            ->route('produksi.index')
            ->with('success', 'Data produksi berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $produksi = DataProduksi::findOrFail($id);

        return view('produksi.data_produksi.show', compact('produksi'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $produksi = DataProduksi::findOrFail($id);

        return view('produksi.data_produksi.edit', compact('produksi'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'jenis_jaring'     => 'required|string|max:255',
            'bulan_produksi'   => 'required',
            'jumlah_pesanan'   => 'required|numeric|min:1',
            'status'           => 'required|in:Aktif,Proses,Nonaktif',
        ]);

        $produksi = DataProduksi::findOrFail($id);

        $produksi->update([
            'jenis_jaring'     => $request->jenis_jaring,
            'bulan_produksi'   => $request->bulan_produksi,
            'jumlah_pesanan'   => $request->jumlah_pesanan,
            'status'           => $request->status,
        ]);

        return redirect()
            ->route('produksi.index')
            ->with('success', 'Data produksi berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $produksi = DataProduksi::findOrFail($id);

        $produksi->delete();

        return redirect()
            ->route('produksi.index')
            ->with('success', 'Data produksi berhasil dihapus.');
    }
}
