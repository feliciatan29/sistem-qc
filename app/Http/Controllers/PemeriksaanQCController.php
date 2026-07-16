<?php

namespace App\Http\Controllers;

use App\Models\PemeriksaanQC;
use App\Models\DataProduksi;
use Illuminate\Http\Request;

class PemeriksaanQCController extends Controller
{
    public function index()
    {
        $pemeriksaan = PemeriksaanQC::with('produksi')->latest()->paginate(10);
        return view('qc.pemeriksaan_qc.index', compact('pemeriksaan'));
    }

    public function create()
    {
        $produksi = DataProduksi::all();
        return view('qc.pemeriksaan_qc.create', compact('produksi'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_produksi' => 'required|exists:tbl_dataproduksi,id',
            'jumlah_cek' => 'required|integer|min:0',
            'baik' => 'required|integer|min:0',
            'rr' => 'required|integer|min:0',
            'pr' => 'required|integer|min:0',
            'rps' => 'required|integer|min:0',
            'super' => 'required|integer|min:0',
            'rj' => 'required|integer|min:0',
            'berbulu' => 'required|integer|min:0',
            'rusak_blok' => 'required|integer|min:0',
            'keterangan' => 'nullable|string',
        ]);

        $produksi = DataProduksi::findOrFail($request->id_produksi);

        // Validasi jumlah cek tidak melebihi pesanan
        if ($request->jumlah_cek > $produksi->jumlah_pesanan) {
            return back()->withErrors(['jumlah_cek' => 'Jumlah Cek tidak boleh melebihi Jumlah Pesanan.'])->withInput();
        }

        // Hitung Total Defect berdasarkan Jenis Jaring
        $isMono = stripos($produksi->jenis_jaring, 'Mono') !== false;
        if ($isMono) {
            $total_defect = $request->rr + $request->pr + $request->rps + $request->super + $request->rj;
        } else {
            $total_defect = $request->rr + $request->pr + $request->rps + $request->berbulu + $request->rusak_blok + $request->rj;
        }


        PemeriksaanQC::create([
            'id_produksi' => $request->id_produksi,
            'jenis_jaring' => $produksi->jenis_jaring,
            'bulan_produksi' => $produksi->bulan_produksi,
            'jumlah_pesanan' => $produksi->jumlah_pesanan,
            'jumlah_cek' => $request->jumlah_cek,
            'baik' => $request->baik,
            'rr' => $request->rr,
            'pr' => $request->pr,
            'rps' => $request->rps,
            'super' => $request->super,
            'rj' => $request->rj,
            'berbulu' => $request->berbulu,
            'rusak_blok' => $request->rusak_blok,
            'total_defect' => $total_defect,
            'keterangan' => $request->keterangan,
        ]);

        return redirect()->route('qc.pemeriksaan.index')->with('success', 'Data Pemeriksaan QC berhasil ditambahkan.');
    }

    public function show($id)
    {
        $pemeriksaan = PemeriksaanQC::with('produksi')->findOrFail($id);
        return view('qc.pemeriksaan_qc.show', compact('pemeriksaan'));
    }

    public function edit($id)
    {
        $pemeriksaan = PemeriksaanQC::findOrFail($id);
        $produksi = DataProduksi::all();
        return view('qc.pemeriksaan_qc.edit', compact('pemeriksaan', 'produksi'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'id_produksi' => 'required|exists:tbl_dataproduksi,id',
            'jumlah_cek' => 'required|integer|min:0',
            'baik' => 'required|integer|min:0',
            'rr' => 'required|integer|min:0',
            'pr' => 'required|integer|min:0',
            'rps' => 'required|integer|min:0',
            'super' => 'required|integer|min:0',
            'rj' => 'required|integer|min:0',
            'berbulu' => 'required|integer|min:0',
            'rusak_blok' => 'required|integer|min:0',
            'keterangan' => 'nullable|string',
        ]);

        $pemeriksaan = PemeriksaanQC::findOrFail($id);
        $produksi = DataProduksi::findOrFail($request->id_produksi);

        // Validasi jumlah cek tidak melebihi pesanan
        if ($request->jumlah_cek > $produksi->jumlah_pesanan) {
            return back()->withErrors(['jumlah_cek' => 'Jumlah Cek tidak boleh melebihi Jumlah Pesanan.'])->withInput();
        }

        // Hitung Total Defect berdasarkan Jenis Jaring
        $isMono = stripos($produksi->jenis_jaring, 'Mono') !== false;
        if ($isMono) {
            $total_defect = $request->rr + $request->pr + $request->rps + $request->super + $request->rj;
        } else {
            $total_defect = $request->rr + $request->pr + $request->rps + $request->berbulu + $request->rusak_blok + $request->rj;
        }


        $pemeriksaan->update([
            'id_produksi' => $request->id_produksi,
            'jenis_jaring' => $produksi->jenis_jaring,
            'bulan_produksi' => $produksi->bulan_produksi,
            'jumlah_pesanan' => $produksi->jumlah_pesanan,
            'jumlah_cek' => $request->jumlah_cek,
            'baik' => $request->baik,
            'rr' => $request->rr,
            'pr' => $request->pr,
            'rps' => $request->rps,
            'super' => $request->super,
            'rj' => $request->rj,
            'berbulu' => $request->berbulu,
            'rusak_blok' => $request->rusak_blok,
            'total_defect' => $total_defect,
            'keterangan' => $request->keterangan,
        ]);

        return redirect()->route('qc.pemeriksaan.index')->with('success', 'Data Pemeriksaan QC berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $pemeriksaan = PemeriksaanQC::findOrFail($id);
        $pemeriksaan->delete();
        return redirect()->route('qc.pemeriksaan.index')->with('success', 'Data Pemeriksaan QC berhasil dihapus.');
    }

    public function getProduksi($id)
    {
        $produksi = DataProduksi::find($id);
        return response()->json($produksi);
    }
}
