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
    public function index()
    {
        $mono = DB::table('tbl_pemeriksaanqc')
            ->selectRaw('SUM(rr) as rr, SUM(pr) as pr, SUM(rps) as rps, SUM(`super`) as `super`, SUM(rj) as rj, SUM(berbulu) as berbulu, SUM(rusak_blok) as rusak_blok')
            ->where('jenis_jaring', 'like', '%Mono%')
            ->first();

        $multi = DB::table('tbl_pemeriksaanqc')
            ->selectRaw('SUM(rr) as rr, SUM(pr) as pr, SUM(rps) as rps, SUM(`super`) as `super`, SUM(rj) as rj, SUM(berbulu) as berbulu, SUM(rusak_blok) as rusak_blok')
            ->where('jenis_jaring', 'like', '%Multi%')
            ->first();

        return view('qc.defect_summary.index', compact('mono', 'multi'));
    }

    /**
     * Display Pareto Diagram Analysis.
     */
    public function pareto(Request $request)
    {
        $filterJaring = $request->input('jenis_jaring', 'Semua Data');

        $query = DB::table('tbl_pemeriksaanqc')
            ->selectRaw('
                SUM(rr) as rr, 
                SUM(pr) as pr, 
                SUM(rps) as rps, 
                SUM(`super`) as `super`, 
                SUM(rj) as rj, 
                SUM(berbulu) as berbulu, 
                SUM(rusak_blok) as rusak_blok
            ');

        if ($filterJaring === 'Monofilament') {
            $query->where('jenis_jaring', 'like', '%Mono%');
        } elseif ($filterJaring === 'Multifilament') {
            $query->where('jenis_jaring', 'like', '%Multi%');
        }

        $result = $query->first();

        $defects = [];
        if ($result) {
            $defects = [
                ['kategori' => 'RR', 'pcs' => (int) $result->rr],
                ['kategori' => 'PR', 'pcs' => (int) $result->pr],
                ['kategori' => 'RPS', 'pcs' => (int) $result->rps],
                ['kategori' => 'SUPER', 'pcs' => (int) $result->super],
                ['kategori' => 'RJ', 'pcs' => (int) $result->rj],
                ['kategori' => 'Berbulu', 'pcs' => (int) $result->berbulu],
                ['kategori' => 'Rusak Blok', 'pcs' => (int) $result->rusak_blok],
            ];
        }

        // Filter kategori yang memiliki pcs > 0 saja
        $defects = array_filter($defects, function($item) {
            return $item['pcs'] > 0;
        });

        // Urutkan berdasarkan Total Pcs terbesar ke terkecil
        usort($defects, function($a, $b) {
            return $b['pcs'] <=> $a['pcs'];
        });

        $totalDefect = array_sum(array_column($defects, 'pcs'));
        
        // Hitung Persentase dan Kumulatif
        $kumulatif = 0;
        $chartData = [
            'labels' => [],
            'pcs' => [],
            'kumulatif' => []
        ];
        
        foreach ($defects as &$defect) {
            $persentase = $totalDefect > 0 ? ($defect['pcs'] / $totalDefect) * 100 : 0;
            $kumulatif += $persentase;
            
            // Batasi kumulatif maksimal 100
            if ($kumulatif > 100) $kumulatif = 100;
            
            $defect['persentase'] = round($persentase, 2);
            $defect['kumulatif'] = round($kumulatif, 2);
            
            $chartData['labels'][] = $defect['kategori'];
            $chartData['pcs'][] = $defect['pcs'];
            $chartData['kumulatif'][] = $defect['kumulatif'];
        }
        unset($defect); // hapus reference

        $dominantDefect = !empty($defects) ? $defects[0]['kategori'] : '-';
        $dominantPercentage = !empty($defects) ? $defects[0]['persentase'] : 0;
        $jumlahKategori = count($defects);

        return view('qc.analisis_pareto.index', compact(
            'defects', 
            'totalDefect', 
            'dominantDefect', 
            'dominantPercentage', 
            'jumlahKategori', 
            'filterJaring',
            'chartData'
        ));
    }
}
