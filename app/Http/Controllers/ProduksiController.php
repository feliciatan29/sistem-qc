<?php

namespace App\Http\Controllers;

use App\Models\DataProduksi;
use App\Models\PengaturanMesin;
use App\Models\PemeriksaanQC;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProduksiController extends Controller
{
    public function dashboard()
    {
        // Metric Cards
        $totalProduksi = DataProduksi::count();
        $produksiSelesai = DataProduksi::where('status', 'Data Selesai')->count();
        $totalPengaturan = PengaturanMesin::count();
        $totalDefect = PemeriksaanQC::sum('total_defect');

        // Status Chart Data
        $statusCounts = [
            'Aktif' => DataProduksi::where('status', 'Aktif')->count(),
            'Proses' => DataProduksi::where('status', 'Proses')->count(),
            'Data Selesai' => DataProduksi::where('status', 'Data Selesai')->count(),
        ];

        // Grafik Jumlah Produksi Jaring per Bulan (Hanya bulan yang ada data)
        // Kita tidak batasi hanya tahun ini agar semua bulan yang diinput muncul, atau tetap batasi tahun? 
        // User bilang "sesuai dengan yang sudah saya input", kita ambil semua data.
        $rawProduksi = DataProduksi::orderBy('bulan_produksi')->get();
        
        $uniqueBulan = $rawProduksi->pluck('bulan_produksi')->unique()->values()->toArray();
        $months = [];
        foreach($uniqueBulan as $b) {
            $months[] = \Carbon\Carbon::parse($b)->translatedFormat('M Y');
        }

        $jaringTypes = $rawProduksi->pluck('jenis_jaring')->unique()->values()->toArray();
        if(empty($jaringTypes)) $jaringTypes = ['Mono', 'Multi'];

        $chartDatasets = [];
        $colors = [
            'rgba(13, 110, 253, 0.8)', // blue
            'rgba(25, 135, 84, 0.8)', // green
            'rgba(255, 193, 7, 0.8)', // yellow
            'rgba(220, 53, 69, 0.8)', // red
            'rgba(111, 66, 193, 0.8)' // purple
        ];
        
        foreach($jaringTypes as $index => $type) {
            $data = [];
            foreach($uniqueBulan as $b) {
                // Hitung total untuk jenis jaring $type pada bulan $b
                $total = $rawProduksi->where('jenis_jaring', $type)
                                     ->where('bulan_produksi', $b)
                                     ->sum('jumlah_pesanan');
                $data[] = (float)$total;
            }

            $chartDatasets[] = [
                'label' => $type,
                'data' => $data,
                'backgroundColor' => $colors[$index % count($colors)],
                'borderWidth' => 1,
                'borderRadius' => 4
            ];
        }

        // Recent Data (Bawah)
        $recentProduksi = DataProduksi::latest()->take(5)->get();

        return view('produksi.beranda', compact(
            'totalProduksi', 'produksiSelesai', 'totalPengaturan', 'totalDefect',
            'statusCounts', 'months', 'chartDatasets', 'recentProduksi'
        ));
    }

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
            'status'           => 'required|in:Aktif,Proses,Data Selesai',
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
            'status'           => 'required|in:Aktif,Proses,Data Selesai',
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
