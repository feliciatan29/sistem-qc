<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TaguchiController extends Controller
{
    public function index(Request $request)
    {
        $jenis = $request->input('jenis_jaring');
        $bulan = $request->input('bulan');
        $tahun = $request->input('tahun');

        $availableYears = DB::table('tbl_pemeriksaanqc')
            ->whereNotNull('bulan_produksi')
            ->selectRaw('SUBSTRING(bulan_produksi, 1, 4) as tahun')
            ->distinct()
            ->orderByDesc('tahun')
            ->pluck('tahun')
            ->toArray();
            
        if (!in_array('2025', $availableYears)) $availableYears[] = '2025';
        if (!in_array('2026', $availableYears)) $availableYears[] = '2026';
        rsort($availableYears);

        // Kategori Defect yang tersedia di tabel
        $availableKategori = [
            'rr' => 'RR',
            'pr' => 'PR',
            'rps' => 'RPS',
            'super' => 'SUPER',
            'rj' => 'RJ',
            'berbulu' => 'Berbulu',
            'rusak_blok' => 'Rusak Blok'
        ];

        // Jika form disubmit
        if ($jenis) {
            
            $qcQuery = DB::table('tbl_pemeriksaanqc')->where('jenis_jaring', 'like', "%$jenis%");
            $pengaturanQuery = DB::table('tbl_pengaturan')->where('jenis_jaring', 'like', "%$jenis%");
            
            $periodeText = "Keseluruhan (Semua Waktu)";
            
            if ($bulan && $tahun) {
                $bulanProduksi = $tahun . '-' . str_pad($bulan, 2, '0', STR_PAD_LEFT);
                $qcQuery->where('bulan_produksi', $bulanProduksi);
                $pengaturanQuery->where('bulan_produksi', $bulanProduksi);
                $periodeText = "Tahun $tahun Bulan $bulan";
            } elseif ($tahun) {
                $qcQuery->where('bulan_produksi', 'like', "$tahun-%");
                $pengaturanQuery->where('bulan_produksi', 'like', "$tahun-%");
                $periodeText = "Tahun $tahun";
            }

            // 1. Ambil Data Pengaturan Mesin (L9) - Eksperimen
            $allPengaturan = (clone $pengaturanQuery)
                ->orderBy('id', 'asc')
                ->get();
                
            $l9_limit = min(9, $allPengaturan->count());
            $pengaturan = $allPengaturan->take($l9_limit);

            if ($l9_limit == 0) {
                return view('qc.taguchi.index', compact('availableYears'))->with('error_taguchi', "Tidak ada data pengaturan mesin untuk periode $periodeText dan jenis jaring tersebut.");
            }

            // 2. Ambil Data Pemeriksaan QC
            $allQc = (clone $qcQuery)
                ->orderBy('id', 'asc')
                ->get();

            if ($allQc->count() == 0) {
                return view('qc.taguchi.index', compact('availableYears'))->with('error_taguchi', "Tidak ada data Pemeriksaan QC untuk periode $periodeText dan jenis jaring tersebut.");
            }

            // Tentukan rasio n (Trial per eksperimen)
            $n_qc_per_exp = max(1, floor($allQc->count() / max(1, $allPengaturan->count())));
            $qc = $allQc->take($l9_limit * $n_qc_per_exp);

            // Validasi Kecukupan Data Taguchi L9 (Hanya Info jika < 9, bukan Error)
            $info_taguchi = null;
            if ($l9_limit < 9) {
                $info_taguchi = "Data belum mencapai 9 eksperimen (baru ada $l9_limit pengaturan mesin). Sistem tetap melakukan perhitungan dengan data yang tersedia.";
            }

            // Ekstrak Level Faktor berdasarkan jenis jaring (Perbaikan: tidak langsung keluar dari database)
            $levels = [
                'A' => ['-', '-', '-'],
                'B' => ['-', '-', '-'],
                'C' => ['-', '-', '-']
            ];

            if (stripos($jenis, 'Mono') !== false) {
                $levels['A'] = ['0.15', '0.20', '0.25'];
                $levels['B'] = ['70', '100', '200'];
                $levels['C'] = ['18', '17', '17']; // Level 3 di 17 sesuai instruksi
            } elseif (stripos($jenis, 'Multi') !== false) {
                // Placeholder untuk Multifilament, bisa disesuaikan nanti
                $levels['A'] = ['210 D/2', '210 D/3', '210 D/4'];
                $levels['B'] = ['50', '60', '70'];
                $levels['C'] = ['15', '16', '17'];
            } else {
                // Fallback jika ada kategori jaring lain
                $faktorA = $pengaturan->pluck('ukuran_jaring')->unique()->values()->toArray();
                $faktorB = $pengaturan->pluck('MD_jaring')->unique()->values()->toArray();
                $faktorC = $pengaturan->pluck('RPM_jaring')->unique()->values()->toArray();

                $levels['A'] = array_pad($faktorA, 3, '-');
                $levels['B'] = array_pad($faktorB, 3, '-');
                $levels['C'] = array_pad($faktorC, 3, '-');
            }

            // Bangun Matriks L9
            $l9_matrix = [];
            $total_sn = 0;
            
            for ($i = 0; $i < $l9_limit; $i++) {
                $p = $pengaturan[$i];
                
                // Ambil nilai Total Defect (Y) untuk eksperimen ini
                $qc_items = [];
                for($j = 0; $j < $n_qc_per_exp; $j++) {
                    $qc_idx = ($i * $n_qc_per_exp) + $j;
                    if(isset($qc[$qc_idx])) {
                        $qc_items[] = (float) $qc[$qc_idx]->total_defect;
                    }
                }
                
                $n = count($qc_items);
                $sum_y2 = 0;
                $sum_y = 0;
                
                if ($n == 0) {
                    $sum_y2 = 0.0001;
                    $n = 1;
                } else {
                    foreach($qc_items as $y) {
                        $val = $y == 0 ? 0.0001 : $y; // Mencegah log(0)
                        $sum_y2 += ($val * $val);
                        $sum_y += $y;
                    }
                }
                
                // Rumus S/N Ratio Smaller is Better: -10 * log10( sum(Y^2) / n )
                $sn_ratio = -10 * log10($sum_y2 / $n);
                $total_sn += $sn_ratio;
                $mean_y = $sum_y / $n;

                // Tentukan level berdasarkan Standar Orthogonal Array L9 agar perhitungan tidak 0 di level tertentu
                $standard_l9 = [
                    [1, 1, 1], // Exp 1
                    [1, 2, 2], // Exp 2
                    [1, 3, 3], // Exp 3
                    [2, 1, 2], // Exp 4
                    [2, 2, 3], // Exp 5
                    [2, 3, 1], // Exp 6
                    [3, 1, 3], // Exp 7
                    [3, 2, 1], // Exp 8
                    [3, 3, 2]  // Exp 9
                ];

                if (isset($standard_l9[$i])) {
                    $lvlA = $standard_l9[$i][0];
                    $lvlB = $standard_l9[$i][1];
                    $lvlC = $standard_l9[$i][2];
                } else {
                    $lvlA = 1; $lvlB = 1; $lvlC = 1;
                }

                $l9_matrix[] = [
                    'exp' => $i + 1,
                    'A_val' => $p->ukuran_jaring,
                    'B_val' => $p->MD_jaring,
                    'C_val' => $p->RPM_jaring,
                    'A_lvl' => $lvlA,
                    'B_lvl' => $lvlB,
                    'C_lvl' => $lvlC,
                    'trials' => $qc_items,
                    'mean_y' => $mean_y,
                    'sn' => $sn_ratio
                ];
            }

            // --- Response Table S/N Ratio & Mean ---
            $responseTable = [
                'A' => [1 => 0, 2 => 0, 3 => 0],
                'B' => [1 => 0, 2 => 0, 3 => 0],
                'C' => [1 => 0, 2 => 0, 3 => 0]
            ];
            $responseTableMean = [
                'A' => [1 => 0, 2 => 0, 3 => 0],
                'B' => [1 => 0, 2 => 0, 3 => 0],
                'C' => [1 => 0, 2 => 0, 3 => 0]
            ];
            $counts = [
                'A' => [1 => 0, 2 => 0, 3 => 0],
                'B' => [1 => 0, 2 => 0, 3 => 0],
                'C' => [1 => 0, 2 => 0, 3 => 0]
            ];

            foreach ($l9_matrix as $row) {
                if(isset($responseTable['A'][$row['A_lvl']])) { 
                    $responseTable['A'][$row['A_lvl']] += $row['sn']; 
                    $responseTableMean['A'][$row['A_lvl']] += $row['mean_y'];
                    $counts['A'][$row['A_lvl']]++; 
                }
                if(isset($responseTable['B'][$row['B_lvl']])) { 
                    $responseTable['B'][$row['B_lvl']] += $row['sn']; 
                    $responseTableMean['B'][$row['B_lvl']] += $row['mean_y'];
                    $counts['B'][$row['B_lvl']]++; 
                }
                if(isset($responseTable['C'][$row['C_lvl']])) { 
                    $responseTable['C'][$row['C_lvl']] += $row['sn']; 
                    $responseTableMean['C'][$row['C_lvl']] += $row['mean_y'];
                    $counts['C'][$row['C_lvl']]++; 
                }
            }

            // Hitung Rata-rata S/N dan Mean per level
            foreach (['A', 'B', 'C'] as $f) {
                for ($l = 1; $l <= 3; $l++) {
                    $responseTable[$f][$l] = $counts[$f][$l] > 0 ? $responseTable[$f][$l] / $counts[$f][$l] : 0;
                    $responseTableMean[$f][$l] = $counts[$f][$l] > 0 ? $responseTableMean[$f][$l] / $counts[$f][$l] : 0;
                }
            }

            // Hitung Delta dan Rank (S/N Ratio)
            $deltas = [];
            foreach (['A', 'B', 'C'] as $f) {
                $valid_sn = [];
                for($l = 1; $l <= 3; $l++) {
                    if($counts[$f][$l] > 0) $valid_sn[] = $responseTable[$f][$l];
                }
                $max = count($valid_sn) > 0 ? max($valid_sn) : 0;
                $min = count($valid_sn) > 0 ? min($valid_sn) : 0;
                $deltas[$f] = $max - $min;
            }
            
            arsort($deltas);
            $ranks = [];
            $rank = 1;
            foreach ($deltas as $f => $val) {
                $ranks[$f] = $rank++;
            }

            // Setting Optimum (Max S/N Ratio)
            $optimum = [];
            foreach (['A', 'B', 'C'] as $f) {
                $max_sn = -999999;
                $opt_lvl = 1;
                for($l = 1; $l <= 3; $l++) {
                    if($counts[$f][$l] > 0 && $responseTable[$f][$l] > $max_sn) {
                        $max_sn = $responseTable[$f][$l];
                        $opt_lvl = $l;
                    }
                }
                $optimum[$f] = $opt_lvl;
            }

            // --- ANOVA S/N Ratio ---
            $avg_total_sn = $l9_limit > 0 ? $total_sn / $l9_limit : 0;
            $SST = 0;
            foreach ($l9_matrix as $row) {
                $SST += pow($row['sn'] - $avg_total_sn, 2);
            }

            $SSA = 0; $SSB = 0; $SSC = 0;
            $DFA = 0; $DFB = 0; $DFC = 0;

            for ($l = 1; $l <= 3; $l++) {
                if($counts['A'][$l] > 0) $DFA++;
                if($counts['B'][$l] > 0) $DFB++;
                if($counts['C'][$l] > 0) $DFC++;

                $SSA += $counts['A'][$l] * pow($responseTable['A'][$l] - $avg_total_sn, 2);
                $SSB += $counts['B'][$l] * pow($responseTable['B'][$l] - $avg_total_sn, 2);
                $SSC += $counts['C'][$l] * pow($responseTable['C'][$l] - $avg_total_sn, 2);
            }

            $DFA = max(0, $DFA - 1);
            $DFB = max(0, $DFB - 1);
            $DFC = max(0, $DFC - 1);
            
            $DFT = max(0, $l9_limit - 1);
            $DFE = max(0, $DFT - ($DFA + $DFB + $DFC));

            $SSE = $SST - ($SSA + $SSB + $SSC);
            // Mencegah SS negatif akibat presisi floating point
            $SSE = $SSE < 0 ? 0 : $SSE; 
            
            $MSA = $DFA > 0 ? $SSA / $DFA : 0;
            $MSB = $DFB > 0 ? $SSB / $DFB : 0;
            $MSC = $DFC > 0 ? $SSC / $DFC : 0;
            $MSE = $DFE > 0 ? $SSE / $DFE : 0;

            $FA = $MSE > 0 ? $MSA / $MSE : 0;
            $FB = $MSE > 0 ? $MSB / $MSE : 0;
            $FC = $MSE > 0 ? $MSC / $MSE : 0;

            $ContA = $SST > 0 ? ($SSA / $SST) * 100 : 0;
            $ContB = $SST > 0 ? ($SSB / $SST) * 100 : 0;
            $ContC = $SST > 0 ? ($SSC / $SST) * 100 : 0;
            $ContE = $SST > 0 ? ($SSE / $SST) * 100 : 0;

            $anova = [
                'A' => ['df' => $DFA, 'ss' => $SSA, 'ms' => $MSA, 'f' => $FA, 'p' => $this->approximatePValue($FA, $DFA, $DFE), 'cont' => $ContA],
                'B' => ['df' => $DFB, 'ss' => $SSB, 'ms' => $MSB, 'f' => $FB, 'p' => $this->approximatePValue($FB, $DFB, $DFE), 'cont' => $ContB],
                'C' => ['df' => $DFC, 'ss' => $SSC, 'ms' => $MSC, 'f' => $FC, 'p' => $this->approximatePValue($FC, $DFC, $DFE), 'cont' => $ContC],
                'Error' => ['df' => $DFE, 'ss' => $SSE, 'ms' => $MSE, 'f' => '-', 'p' => '-', 'cont' => $ContE],
                'Total' => ['df' => $DFT, 'ss' => $SST, 'ms' => '-', 'f' => '-', 'p' => '-', 'cont' => 100]
            ];

            return view('qc.taguchi.index', compact(
                'availableYears', 'levels', 'l9_matrix', 'n_qc_per_exp',
                'responseTable', 'responseTableMean', 'deltas', 'ranks', 'optimum', 'anova', 'periodeText', 'info_taguchi', 'avg_total_sn'
            ));
        }

        return view('qc.taguchi.index', compact('availableYears', 'availableKategori'));
    }

    // Fungsi perkiraan kasar P-Value untuk F-Distribution (2,2 DOF) sebagai placeholder sederhana
    // Karena implementasi CDF F-Dist murni cukup panjang di PHP tanpa library tambahan.
    private function approximatePValue($f, $df1, $df2) {
        if ($f == 0 || $f === '-') return '-';
        // Formula pasti untuk P(F > f) dengan df1=2, df2=2 adalah:
        // P = 1 / (1 + f)
        if ($df1 == 2 && $df2 == 2) {
            return 1 / (1 + $f);
        }
        return '-'; // Fallback
    }
}
