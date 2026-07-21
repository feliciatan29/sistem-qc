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

            // 1. Tentukan Defect Dominan (Kategori dengan jumlah defect terbesar pada filter tsb)
            $qcSums = (clone $qcQuery)
                ->selectRaw('SUM(rr) as rr, SUM(pr) as pr, SUM(rps) as rps, SUM(`super`) as `super`, SUM(rj) as rj, SUM(berbulu) as berbulu, SUM(rusak_blok) as rusak_blok')
                ->first();

            if (!$qcSums || ($qcSums->rr == null && $qcSums->rj == null)) {
                return view('qc.taguchi.index', compact('availableYears'))->with('error_taguchi', "Tidak ada data Pemeriksaan QC untuk periode $periodeText dan jenis jaring tersebut.");
            }

            // Cari nilai maximum dari semua kolom defect
            $defectCounts = [
                'rr' => (int)$qcSums->rr,
                'pr' => (int)$qcSums->pr,
                'rps' => (int)$qcSums->rps,
                'super' => (int)$qcSums->super,
                'rj' => (int)$qcSums->rj,
                'berbulu' => (int)$qcSums->berbulu,
                'rusak_blok' => (int)$qcSums->rusak_blok,
            ];

            arsort($defectCounts);
            $kategori = array_key_first($defectCounts); // Kolom defect terbanyak
            $kategoriName = $availableKategori[$kategori] ?? strtoupper($kategori);
            $maxDefect = $defectCounts[$kategori];

            if ($maxDefect == 0) {
                return view('qc.taguchi.index', compact('availableYears'))->with('error_taguchi', "Tidak ada defect yang ditemukan pada periode $periodeText dan jenis jaring tersebut (semua 0).");
            }

            // 2. Ambil 9 Data Pengaturan Mesin (L9)
            $pengaturan = (clone $pengaturanQuery)
                ->orderBy('id', 'asc')
                ->limit(9)
                ->get();

            // 3. Ambil 18 Data Pemeriksaan QC (Trial 1 & Trial 2 untuk 9 Eksperimen)
            $qc = (clone $qcQuery)
                ->orderBy('id', 'asc')
                ->limit(18)
                ->get();

            // Validasi Kecukupan Data Taguchi L9
            if ($pengaturan->count() < 9 || $qc->count() < 18) {
                return view('qc.taguchi.index', compact('availableYears'))->with('error_taguchi', "Data belum mencukupi untuk dilakukan optimasi Taguchi L9. Dibutuhkan minimal 9 data pengaturan mesin dan 18 data pemeriksaan QC untuk filter $periodeText tersebut.");
            }

            // Ekstrak Level Faktor
            $faktorA = $pengaturan->pluck('ukuran_jaring')->unique()->values()->toArray();
            $faktorB = $pengaturan->pluck('MD_jaring')->unique()->values()->toArray();
            $faktorC = $pengaturan->pluck('RPM_jaring')->unique()->values()->toArray();

            // Pastikan kita memiliki 3 level, jika kurang kita ambil apa adanya dan isi default
            $levels = [
                'A' => array_pad($faktorA, 3, '-'),
                'B' => array_pad($faktorB, 3, '-'),
                'C' => array_pad($faktorC, 3, '-')
            ];

            // Bangun Matriks L9
            $l9_matrix = [];
            $total_sn = 0;
            
            for ($i = 0; $i < 9; $i++) {
                $p = $pengaturan[$i];
                $trial1 = (float) $qc[$i * 2]->{$kategori};
                $trial2 = (float) $qc[($i * 2) + 1]->{$kategori};
                
                // Mencegah log(0)
                $t1 = $trial1 == 0 ? 0.0001 : $trial1;
                $t2 = $trial2 == 0 ? 0.0001 : $trial2;
                
                // Rumus S/N Ratio Smaller is Better: -10 * log10((T1^2 + T2^2)/2)
                $sn_ratio = -10 * log10( (($t1 * $t1) + ($t2 * $t2)) / 2 );
                $total_sn += $sn_ratio;

                // Tentukan level (1, 2, atau 3) berdasarkan index unik
                $lvlA = array_search($p->ukuran_jaring, $levels['A']) + 1;
                $lvlB = array_search($p->MD_jaring, $levels['B']) + 1;
                $lvlC = array_search($p->RPM_jaring, $levels['C']) + 1;

                $l9_matrix[] = [
                    'exp' => $i + 1,
                    'A_val' => $p->ukuran_jaring,
                    'B_val' => $p->MD_jaring,
                    'C_val' => $p->RPM_jaring,
                    'A_lvl' => $lvlA,
                    'B_lvl' => $lvlB,
                    'C_lvl' => $lvlC,
                    'trial1' => $trial1,
                    'trial2' => $trial2,
                    'sn' => $sn_ratio
                ];
            }

            // --- Response Table S/N Ratio ---
            $responseTable = [
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
                if(isset($responseTable['A'][$row['A_lvl']])) { $responseTable['A'][$row['A_lvl']] += $row['sn']; $counts['A'][$row['A_lvl']]++; }
                if(isset($responseTable['B'][$row['B_lvl']])) { $responseTable['B'][$row['B_lvl']] += $row['sn']; $counts['B'][$row['B_lvl']]++; }
                if(isset($responseTable['C'][$row['C_lvl']])) { $responseTable['C'][$row['C_lvl']] += $row['sn']; $counts['C'][$row['C_lvl']]++; }
            }

            // Hitung Rata-rata S/N per level
            foreach (['A', 'B', 'C'] as $f) {
                for ($l = 1; $l <= 3; $l++) {
                    $responseTable[$f][$l] = $counts[$f][$l] > 0 ? $responseTable[$f][$l] / $counts[$f][$l] : 0;
                }
            }

            // Hitung Delta dan Rank
            $deltas = [];
            foreach (['A', 'B', 'C'] as $f) {
                $max = max($responseTable[$f]);
                $min = min($responseTable[$f]);
                $deltas[$f] = $max - $min;
            }
            
            arsort($deltas);
            $ranks = [];
            $rank = 1;
            foreach ($deltas as $f => $val) {
                $ranks[$f] = $rank++;
            }

            // Setting Optimum
            $optimum = [
                'A' => array_keys($responseTable['A'], max($responseTable['A']))[0],
                'B' => array_keys($responseTable['B'], max($responseTable['B']))[0],
                'C' => array_keys($responseTable['C'], max($responseTable['C']))[0]
            ];

            // --- ANOVA S/N Ratio ---
            $avg_total_sn = $total_sn / 9;
            $SST = 0;
            foreach ($l9_matrix as $row) {
                $SST += pow($row['sn'] - $avg_total_sn, 2);
            }

            $SSA = 0; $SSB = 0; $SSC = 0;
            for ($l = 1; $l <= 3; $l++) {
                $SSA += 3 * pow($responseTable['A'][$l] - $avg_total_sn, 2);
                $SSB += 3 * pow($responseTable['B'][$l] - $avg_total_sn, 2);
                $SSC += 3 * pow($responseTable['C'][$l] - $avg_total_sn, 2);
            }

            $SSE = $SST - ($SSA + $SSB + $SSC);
            // Mencegah SS negatif akibat presisi floating point
            $SSE = $SSE < 0 ? 0 : $SSE; 

            $DFA = 2; $DFB = 2; $DFC = 2; $DFE = 2; $DFT = 8;
            
            $MSA = $SSA / $DFA;
            $MSB = $SSB / $DFB;
            $MSC = $SSC / $DFC;
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
                'availableYears', 'availableKategori', 'levels', 'l9_matrix', 
                'responseTable', 'deltas', 'ranks', 'optimum', 'anova', 'kategoriName', 'kategori', 'periodeText'
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
