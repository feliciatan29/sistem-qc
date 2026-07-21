<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Fmea;

class FmeaController extends Controller
{
    public function index(Request $request)
    {
        $tahun = $request->input('tahun', '');

        // 1. Get total defects for Mono (Overall)
        $monoOverall = DB::table('tbl_pemeriksaanqc')
            ->selectRaw('SUM(rr) as rr, SUM(pr) as pr, SUM(rps) as rps, SUM(`super`) as `super`, SUM(rj) as rj')
            ->where('jenis_jaring', 'like', '%Mono%')
            ->first();

        // 2. Get total defects for Multi (Overall)
        $multiOverall = DB::table('tbl_pemeriksaanqc')
            ->selectRaw('SUM(rr) as rr, SUM(pr) as pr, SUM(rps) as rps, SUM(rj) as rj, SUM(berbulu) as berbulu, SUM(rusak_blok) as rusak_blok')
            ->where('jenis_jaring', 'like', '%Multi%')
            ->first();

        $monoDataOverall = $this->processFmeaData('Monofilament', [
            'RR' => $monoOverall->rr ?? 0,
            'PR' => $monoOverall->pr ?? 0,
            'RPS' => $monoOverall->rps ?? 0,
            'SUPER' => $monoOverall->super ?? 0,
            'RJ' => $monoOverall->rj ?? 0,
        ]);

        $multiDataOverall = $this->processFmeaData('Multifilament', [
            'RR' => $multiOverall->rr ?? 0,
            'PR' => $multiOverall->pr ?? 0,
            'RPS' => $multiOverall->rps ?? 0,
            'RJ' => $multiOverall->rj ?? 0,
            'Berbulu' => $multiOverall->berbulu ?? 0,
            'Rusak Blok' => $multiOverall->rusak_blok ?? 0,
        ]);

        $monoDataFiltered = null;
        $multiDataFiltered = null;

        if ($tahun != '') {
            $monoFiltered = DB::table('tbl_pemeriksaanqc')
                ->selectRaw('SUM(rr) as rr, SUM(pr) as pr, SUM(rps) as rps, SUM(`super`) as `super`, SUM(rj) as rj')
                ->where('jenis_jaring', 'like', '%Mono%')
                ->where('bulan_produksi', 'like', $tahun . '%')
                ->first();

            $multiFiltered = DB::table('tbl_pemeriksaanqc')
                ->selectRaw('SUM(rr) as rr, SUM(pr) as pr, SUM(rps) as rps, SUM(rj) as rj, SUM(berbulu) as berbulu, SUM(rusak_blok) as rusak_blok')
                ->where('jenis_jaring', 'like', '%Multi%')
                ->where('bulan_produksi', 'like', $tahun . '%')
                ->first();

            $monoDataFiltered = $this->processFmeaData('Monofilament', [
                'RR' => $monoFiltered->rr ?? 0,
                'PR' => $monoFiltered->pr ?? 0,
                'RPS' => $monoFiltered->rps ?? 0,
                'SUPER' => $monoFiltered->super ?? 0,
                'RJ' => $monoFiltered->rj ?? 0,
            ]);

            $multiDataFiltered = $this->processFmeaData('Multifilament', [
                'RR' => $multiFiltered->rr ?? 0,
                'PR' => $multiFiltered->pr ?? 0,
                'RPS' => $multiFiltered->rps ?? 0,
                'RJ' => $multiFiltered->rj ?? 0,
                'Berbulu' => $multiFiltered->berbulu ?? 0,
                'Rusak Blok' => $multiFiltered->rusak_blok ?? 0,
            ]);
        }

        // Available years
        $availablePeriods = DB::table('tbl_pemeriksaanqc')
            ->whereNotNull('bulan_produksi')
            ->where('bulan_produksi', '!=', '')
            ->select('bulan_produksi')
            ->distinct()
            ->orderByDesc('bulan_produksi')
            ->pluck('bulan_produksi');

        $availableYears = [];
        foreach ($availablePeriods as $p) {
            $y = substr($p, 0, 4);
            if (!in_array($y, $availableYears)) {
                $availableYears[] = $y;
            }
        }
        
        if (!in_array('2025', $availableYears)) $availableYears[] = '2025';
        if (!in_array('2026', $availableYears)) $availableYears[] = '2026';
        rsort($availableYears);

        return view('qc.fmea.index', compact('monoDataOverall', 'multiDataOverall', 'monoDataFiltered', 'multiDataFiltered', 'tahun', 'availableYears'));
    }

    private function processFmeaData($jenisJaring, $defects)
    {
        $totalAll = array_sum($defects);
        $result = [];

        foreach ($defects as $kategori => $totalPcs) {
            $fmea = Fmea::firstOrCreate(
                ['jenis_jaring' => $jenisJaring, 'kategori_defect' => $kategori],
                ['severity' => 1, 'occurrence' => 1, 'detection' => 1]
            );

            $kontribusi = $totalAll > 0 ? ($totalPcs / $totalAll) * 100 : 0;
            $rpn = $fmea->severity * $fmea->occurrence * $fmea->detection;

            $result[] = (object)[
                'id' => $fmea->id,
                'kategori' => $kategori,
                'total_pcs' => $totalPcs,
                'kontribusi' => $kontribusi,
                'severity' => $fmea->severity,
                'occurrence' => $fmea->occurrence,
                'detection' => $fmea->detection,
                'rpn' => $rpn,
            ];
        }

        // Sort by RPN descending, then by Total Pcs descending for tiebreaker
        usort($result, function($a, $b) {
            if ($b->rpn == $a->rpn) {
                return $b->total_pcs <=> $a->total_pcs;
            }
            return $b->rpn <=> $a->rpn;
        });

        // Assign ranking
        $rank = 1;
        foreach ($result as $item) {
            $item->ranking = $rank++;
        }

        return $result;
    }

    public function update(Request $request)
    {
        $fmeaData = $request->input('fmea', []);
        
        foreach ($fmeaData as $id => $values) {
            $fmea = Fmea::find($id);
            if ($fmea) {
                $fmea->update([
                    'severity' => $values['severity'] ?? 1,
                    'occurrence' => $values['occurrence'] ?? 1,
                    'detection' => $values['detection'] ?? 1,
                ]);
            }
        }

        return redirect()->route('qc.fmea.index')->with('success', 'Data Analisis FMEA berhasil disimpan!');
    }
}
