<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class QlfController extends Controller
{
    public function index(Request $request)
    {
        // 1. Parameter QLF Utama
        $A = $request->input('biaya_kerugian', 50000); 
        $D = $request->input('batas_toleransi', 100);  
        $jumlahHariProduksi = $request->input('hari_produksi', 30);
        
        $k = 0;
        if ($D != 0) {
            $k = $A / ($D * $D);
        }

        // Ambil data inputan manual dari user (jika ada)
        $inputData = $request->input('qlfdata', []);
        
        // Jika belum ada input, berikan 1 baris kosong sebagai default
        if (empty($inputData)) {
            $inputData = [
                ['jenis_jaring' => '', 'target' => 0, 'aktual' => 0, 'produksi_hari' => 0]
            ];
        }

        $tableData = [];
        $totalLossHari = 0;
        $totalLossBulan = 0;
        $totalLossTahun = 0;
        
        $lossPerJaring = [];

        // Lakukan perhitungan untuk setiap baris inputan manual
        foreach ($inputData as $index => $row) {
            $jenisJaring = $row['jenis_jaring'] ?? 'Jaring ' . ($index + 1);
            if(trim($jenisJaring) == '') {
                $jenisJaring = 'Jaring ' . ($index + 1);
            }
            
            $T = (float) ($row['target'] ?? 0);
            $y = (float) ($row['aktual'] ?? 0);
            $produksiHari = (float) ($row['produksi_hari'] ?? 0);
            
            // Perhitungan
            $lossPerUnit = $k * pow(($y - $T), 2);
            $lossPerHari = $lossPerUnit * $produksiHari;
            $lossPerBulan = $lossPerHari * $jumlahHariProduksi;
            $lossPerTahun = $lossPerBulan * 12;

            $tableData[] = [
                'jenis_jaring' => $jenisJaring,
                'T' => $T,
                'y' => $y,
                'loss_per_unit' => $lossPerUnit,
                'produksi_hari' => $produksiHari,
                'loss_per_hari' => $lossPerHari,
                'loss_per_bulan' => $lossPerBulan,
                'loss_per_tahun' => $lossPerTahun,
            ];

            // Akumulasi
            $totalLossHari += $lossPerHari;
            $totalLossBulan += $lossPerBulan;
            $totalLossTahun += $lossPerTahun;

            // Untuk Grafik
            if (!isset($lossPerJaring[$jenisJaring])) {
                $lossPerJaring[$jenisJaring] = 0;
            }
            $lossPerJaring[$jenisJaring] += $lossPerBulan;
        }

        // Menentukan Kerugian Terbesar
        $jenisJaringTerbesar = '-';
        $maxLoss = -1;
        foreach ($lossPerJaring as $jaring => $loss) {
            if ($loss > $maxLoss && $loss > 0) {
                $maxLoss = $loss;
                $jenisJaringTerbesar = $jaring;
            }
        }

        // Siapkan data untuk Chart.js
        $chartBarLabels = array_keys($lossPerJaring);
        $chartBarData = array_values($lossPerJaring);

        // Tren Kerugian (Line Chart) -> Karena tidak ada bulan spesifik, kita tampilkan akumulasi per jaring
        $chartLineLabels = $chartBarLabels;
        $chartLineData = $chartBarData;

        // Doughnut Chart
        $chartDoughnutData = [];
        foreach ($chartBarData as $loss) {
            if ($totalLossBulan > 0) {
                $chartDoughnutData[] = round(($loss / $totalLossBulan) * 100, 2);
            } else {
                $chartDoughnutData[] = 0;
            }
        }

        return view('qc.qlf.index', compact(
            'A', 'D', 'k', 'jumlahHariProduksi',
            'tableData', 'inputData',
            'totalLossHari', 'totalLossBulan', 'totalLossTahun', 'jenisJaringTerbesar',
            'chartBarLabels', 'chartBarData', 
            'chartLineLabels', 'chartLineData',
            'chartDoughnutData'
        ));
    }
}
