<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class QccDefectController extends Controller
{
    /**
     * Display a simple summary of total defects per category
     * for Monofilament and Multifilament.
     */
    public function index(Request $request)
    {
        // 1. Ambil opsi periode (YYYY-MM) dari database
        $availablePeriods = DB::table('tbl_pemeriksaanqc')
            ->whereNotNull('bulan_produksi')
            ->where('bulan_produksi', '!=', '')
            ->select('bulan_produksi')
            ->distinct()
            ->orderByDesc('bulan_produksi')
            ->pluck('bulan_produksi');

        // Extract available years for "Tahun Keseluruhan" options
        $availableYears = [];
        foreach ($availablePeriods as $period) {
            $year = substr($period, 0, 4);
            if (!in_array($year, $availableYears)) {
                $availableYears[] = $year;
            }
        }

        // 2. Query All-Time Data (Keseluruhan)
        $monoAllTime = DB::table('tbl_pemeriksaanqc')
            ->selectRaw('SUM(jumlah_cek) as cek, SUM(baik) as baik, SUM(rr) as rr, SUM(pr) as pr, SUM(rps) as rps, SUM(`super`) as `super`, SUM(rj) as rj, SUM(berbulu) as berbulu, SUM(rusak_blok) as rusak_blok')
            ->where('jenis_jaring', 'like', '%Mono%')
            ->first();

        $multiAllTime = DB::table('tbl_pemeriksaanqc')
            ->selectRaw('SUM(jumlah_cek) as cek, SUM(baik) as baik, SUM(rr) as rr, SUM(pr) as pr, SUM(rps) as rps, SUM(`super`) as `super`, SUM(rj) as rj, SUM(berbulu) as berbulu, SUM(rusak_blok) as rusak_blok')
            ->where('jenis_jaring', 'like', '%Multi%')
            ->first();

        // 3. Query Filtered Data
        $queryMonoFiltered = DB::table('tbl_pemeriksaanqc')
            ->selectRaw('SUM(jumlah_cek) as cek, SUM(baik) as baik, SUM(rr) as rr, SUM(pr) as pr, SUM(rps) as rps, SUM(`super`) as `super`, SUM(rj) as rj, SUM(berbulu) as berbulu, SUM(rusak_blok) as rusak_blok')
            ->where('jenis_jaring', 'like', '%Mono%');

        $queryMultiFiltered = DB::table('tbl_pemeriksaanqc')
            ->selectRaw('SUM(jumlah_cek) as cek, SUM(baik) as baik, SUM(rr) as rr, SUM(pr) as pr, SUM(rps) as rps, SUM(`super`) as `super`, SUM(rj) as rj, SUM(berbulu) as berbulu, SUM(rusak_blok) as rusak_blok')
            ->where('jenis_jaring', 'like', '%Multi%');

        $isFiltered = false;
        $filterLabel = 'Filter';

        if ($request->has('periode') && $request->periode != '') {
            $periode = $request->periode; // '2026' or '2026-09'
            
            if (strlen($periode) == 4) { // Tahun Keseluruhan
                $queryMonoFiltered->where('bulan_produksi', 'like', $periode . '-%');
                $queryMultiFiltered->where('bulan_produksi', 'like', $periode . '-%');
                $isFiltered = true;
                $filterLabel = 'Tahun ' . $periode;
            } elseif (strlen($periode) == 7) { // Bulan Tertentu
                $queryMonoFiltered->where('bulan_produksi', $periode);
                $queryMultiFiltered->where('bulan_produksi', $periode);
                $isFiltered = true;
                
                $year = substr($periode, 0, 4);
                $month = substr($periode, 5, 2);
                $monthName = \Carbon\Carbon::create()->month((int)$month)->translatedFormat('F');
                $filterLabel = $monthName . ' ' . $year;
            }
        }

        $monoFiltered = $isFiltered ? $queryMonoFiltered->first() : clone $monoAllTime;
        $multiFiltered = $isFiltered ? $queryMultiFiltered->first() : clone $multiAllTime;

        // Cek apakah data kosong pada periode yang difilter
        $isEmpty = false;
        if (empty($monoFiltered->cek) && empty($multiFiltered->cek)) {
            $isEmpty = true;
        }

        // Data for Chart.js (menggunakan data filter jika ada, atau all-time jika tidak)
        if ($isEmpty) {
            $chartData = [
                'categories' => ['RR', 'PR', 'RPS', 'SUPER', 'RJ', 'Berbulu', 'Rusak Blok'],
                'mono' => [0, 0, 0, 0, 0, 0, 0],
                'multi' => [0, 0, 0, 0, 0, 0, 0]
            ];
        } else {
            $chartData = [
                'categories' => ['RR', 'PR', 'RPS', 'SUPER', 'RJ', 'Berbulu', 'Rusak Blok'],
                'mono' => [
                    (int)($monoFiltered->rr ?? 0), (int)($monoFiltered->pr ?? 0), (int)($monoFiltered->rps ?? 0), (int)($monoFiltered->super ?? 0), (int)($monoFiltered->rj ?? 0), (int)($monoFiltered->berbulu ?? 0), (int)($monoFiltered->rusak_blok ?? 0)
                ],
                'multi' => [
                    (int)($multiFiltered->rr ?? 0), (int)($multiFiltered->pr ?? 0), (int)($multiFiltered->rps ?? 0), (int)($multiFiltered->super ?? 0), (int)($multiFiltered->rj ?? 0), (int)($multiFiltered->berbulu ?? 0), (int)($multiFiltered->rusak_blok ?? 0)
                ]
            ];
        }

        return view('qc.defect_summary.index', compact(
            'availableYears', 'availablePeriods',
            'monoFiltered', 'multiFiltered',
            'monoAllTime', 'multiAllTime',
            'chartData', 'isFiltered', 'filterLabel', 'isEmpty'
        ));
    }

    /**
     * Display Pareto Diagram Analysis.
     */
    public function pareto(Request $request)
    {
        $filterJaring = $request->input('jenis_jaring', 'Semua Data');
        $periode = $request->input('periode', '');
        
        // 1. Ambil opsi periode dari database
        $availablePeriods = DB::table('tbl_pemeriksaanqc')
            ->whereNotNull('bulan_produksi')
            ->where('bulan_produksi', '!=', '')
            ->select('bulan_produksi')
            ->distinct()
            ->orderByDesc('bulan_produksi')
            ->pluck('bulan_produksi');

        $availableYears = [];
        foreach ($availablePeriods as $p) {
            $year = substr($p, 0, 4);
            if (!in_array($year, $availableYears)) {
                $availableYears[] = $year;
            }
        }

        // Base Query builder function
        $buildQuery = function($isFiltered = false) use ($filterJaring, $periode) {
            $query = DB::table('tbl_pemeriksaanqc')
                ->selectRaw('
                    SUM(rr) as rr, SUM(pr) as pr, SUM(rps) as rps, 
                    SUM(`super`) as `super`, SUM(rj) as rj, 
                    SUM(berbulu) as berbulu, SUM(rusak_blok) as rusak_blok
                ');

            if ($filterJaring === 'Monofilament') {
                $query->where('jenis_jaring', 'like', '%Mono%');
            } elseif ($filterJaring === 'Multifilament') {
                $query->where('jenis_jaring', 'like', '%Multi%');
            }

            if ($isFiltered && !empty($periode)) {
                if (strlen($periode) == 4) { // Tahun
                    $query->where('bulan_produksi', 'like', $periode . '-%');
                } elseif (strlen($periode) == 7) { // Bulan
                    $query->where('bulan_produksi', $periode);
                }
            }

            return $query->first();
        };

        $resultAllTime = $buildQuery(false);
        $resultFiltered = !empty($periode) ? $buildQuery(true) : null;
        $isFiltered = !empty($periode);

        // Process data function
        $processData = function($result) use ($filterJaring) {
            $defects = [];
            if ($result) {
                $defects = [
                    ['kategori' => 'RR', 'pcs' => (int) $result->rr],
                    ['kategori' => 'PR', 'pcs' => (int) $result->pr],
                    ['kategori' => 'RPS', 'pcs' => (int) $result->rps],
                    ['kategori' => 'SUPER', 'pcs' => (int) $result->super],
                    ['kategori' => 'RJ', 'pcs' => (int) $result->rj],
                ];
                if ($filterJaring !== 'Monofilament') {
                    $defects[] = ['kategori' => 'Berbulu', 'pcs' => (int) $result->berbulu];
                    $defects[] = ['kategori' => 'Rusak Blok', 'pcs' => (int) $result->rusak_blok];
                }
            }

            usort($defects, fn($a, $b) => $b['pcs'] <=> $a['pcs']);

            $totalDefect = array_sum(array_column($defects, 'pcs'));
            
            $kumulatif = 0;
            $chartData = ['labels' => [], 'pcs' => [], 'kumulatif' => []];
            
            foreach ($defects as &$defect) {
                $persentase = $totalDefect > 0 ? ($defect['pcs'] / $totalDefect) * 100 : 0;
                $kumulatif += $persentase;
                if ($kumulatif > 100) $kumulatif = 100;
                
                $defect['persentase'] = round($persentase, 2);
                $defect['kumulatif'] = round($kumulatif, 2);
                
                $chartData['labels'][] = $defect['kategori'];
                $chartData['pcs'][] = $defect['pcs'];
                $chartData['kumulatif'][] = $defect['kumulatif'];
            }
            unset($defect);

            return [
                'defects' => $defects,
                'totalDefect' => $totalDefect,
                'dominantDefect' => !empty($defects) ? $defects[0]['kategori'] : '-',
                'dominantPercentage' => !empty($defects) ? $defects[0]['persentase'] : 0,
                'jumlahKategori' => count($defects),
                'chartData' => $chartData
            ];
        };

        $dataAllTime = $processData($resultAllTime);
        $dataFiltered = $isFiltered ? $processData($resultFiltered) : null;

        // Label Filter
        $filterLabel = 'Filter';
        if ($isFiltered) {
            if (strlen($periode) == 4) {
                $filterLabel = 'Tahun ' . $periode;
            } elseif (strlen($periode) == 7) {
                $year = substr($periode, 0, 4);
                $month = substr($periode, 5, 2);
                $monthName = \Carbon\Carbon::create()->month((int)$month)->translatedFormat('F');
                $filterLabel = $monthName . ' ' . $year;
            }
        }

        return view('qc.analisis_pareto.index', compact(
            'filterJaring', 'periode', 'isFiltered', 'filterLabel',
            'availablePeriods', 'availableYears',
            'dataAllTime', 'dataFiltered'
        ));
    }
}
