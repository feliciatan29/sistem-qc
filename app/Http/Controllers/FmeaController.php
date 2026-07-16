<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Fmea;

class FmeaController extends Controller
{
    public function index()
    {
        // Get total defects for Mono
        $mono = DB::table('tbl_pemeriksaanqc')
            ->selectRaw('SUM(rr) as rr, SUM(pr) as pr, SUM(rps) as rps, SUM(`super`) as `super`, SUM(rj) as rj')
            ->where('jenis_jaring', 'like', '%Mono%')
            ->first();

        // Get total defects for Multi
        $multi = DB::table('tbl_pemeriksaanqc')
            ->selectRaw('SUM(rr) as rr, SUM(pr) as pr, SUM(rps) as rps, SUM(rj) as rj, SUM(berbulu) as berbulu, SUM(rusak_blok) as rusak_blok')
            ->where('jenis_jaring', 'like', '%Multi%')
            ->first();

        $monoData = $this->processFmeaData('Monofilament', [
            'RR' => $mono->rr ?? 0,
            'PR' => $mono->pr ?? 0,
            'RPS' => $mono->rps ?? 0,
            'SUPER' => $mono->super ?? 0,
            'RJ' => $mono->rj ?? 0,
        ]);

        $multiData = $this->processFmeaData('Multifilament', [
            'RR' => $multi->rr ?? 0,
            'PR' => $multi->pr ?? 0,
            'RPS' => $multi->rps ?? 0,
            'RJ' => $multi->rj ?? 0,
            'Berbulu' => $multi->berbulu ?? 0,
            'Rusak Blok' => $multi->rusak_blok ?? 0,
        ]);

        return view('qc.fmea.index', compact('monoData', 'multiData'));
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
