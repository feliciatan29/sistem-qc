@extends('qc.layoutqc')

@section('title', 'Data Defect (QCC Summary)')

@push('styles')
<style>
    /* ── Stat Cards ─────────────────────────────────────── */
    .stat-card {
        border-radius: 14px;
        border: 0;
        overflow: hidden;
        box-shadow: 0 4px 18px rgba(0,0,0,.08);
        transition: transform .2s, box-shadow .2s;
    }
    .stat-card:hover { transform: translateY(-3px); box-shadow: 0 8px 24px rgba(0,0,0,.13); }
    .stat-card .card-body { padding: 1.4rem 1.6rem; }
    .stat-icon {
        width: 48px; height: 48px; border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.4rem; flex-shrink: 0;
    }
    .stat-label  { font-size: .75rem; font-weight: 600; text-transform: uppercase; letter-spacing: .06em; color: #64748b; }
    .stat-value  { font-size: 2rem; font-weight: 800; line-height: 1.1; }
    .stat-sub    { font-size: .8rem; color: #64748b; margin-top: .15rem; }

    /* ── Table Styling ──────────────────────────────────── */
    .qcc-table { table-layout: fixed; margin-bottom: 0 !important; }
    .qcc-table thead th {
        background: #f8fafc;
        color: #475569;
        font-size: .7rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .08em;
        border-bottom: 2px solid #e2e8f0;
        padding: 1rem .75rem;
    }
    .qcc-table tbody td { padding: .85rem .75rem; vertical-align: middle; border-bottom: 1px solid #f1f5f9; }
    .qcc-table tbody tr:hover { background: #f8fafc; }

    /* ── Components ─────────────────────────────────────── */
    .rank-badge {
        display: inline-flex; align-items: center; justify-content: center;
        width: 26px; height: 26px; border-radius: 8px;
        font-size: .75rem; font-weight: 700;
        background: #f1f5f9; color: #475569;
    }
    .rank-1 { background: #fde68a; color: #92400e; }
    .rank-2 { background: #e2e8f0; color: #334155; }
    .rank-3 { background: #fed7aa; color: #9a3412; }

    .pct-bar { height: 8px; border-radius: 4px; background: #e2e8f0; overflow: hidden; }
    .pct-bar-fill { height: 100%; border-radius: 4px; transition: width .5s ease; }

    .section-chip {
        display: inline-flex; align-items: center; gap: .5rem;
        padding: .4rem 1rem; border-radius: 8px;
        font-size: .75rem; font-weight: 700; text-transform: uppercase; letter-spacing: .05em;
    }
</style>
@endpush

@section('content')

<div class="page-heading mb-4">
    <div class="page-heading-copy">
        <span class="page-icon"><i class="bi bi-exclamation-circle"></i></span>
        <div>
            <p class="eyebrow mb-1">Quality Control</p>
            <h1 class="h3 mb-1">Data Defect – QCC Summary</h1>
        </div>
    </div>
</div>

@php
    $mRR = (int)($mono->rr ?? 0); $mPR = (int)($mono->pr ?? 0); $mRPS = (int)($mono->rps ?? 0); $mSUP = (int)($mono->super ?? 0); $mRJ = (int)($mono->rj ?? 0);
    $totalMono = $mRR + $mPR + $mRPS + $mSUP + $mRJ;
    $monoRows = collect([['label'=>'RR','val'=>$mRR],['label'=>'PR','val'=>$mPR],['label'=>'RPS','val'=>$mRPS],['label'=>'SUPER','val'=>$mSUP],['label'=>'RJ','val'=>$mRJ]])->sortByDesc('val');
    $dominantMono = $monoRows->first()['label'] ?? '-';

    $uRR = (int)($multi->rr ?? 0); $uPR = (int)($multi->pr ?? 0); $uRPS = (int)($multi->rps ?? 0); $uSUP = (int)($multi->super ?? 0); $uRJ = (int)($multi->rj ?? 0); $uBER = (int)($multi->berbulu ?? 0); $uRBL = (int)($multi->rusak_blok ?? 0);
    $totalMulti = $uRR + $uPR + $uRPS + $uSUP + $uRJ + $uBER + $uRBL;
    $multiRows = collect([['label'=>'RR','val'=>$uRR],['label'=>'PR','val'=>$uPR],['label'=>'RPS','val'=>$uRPS],['label'=>'SUPER','val'=>$uSUP],['label'=>'RJ','val'=>$uRJ],['label'=>'Berbulu','val'=>$uBER],['label'=>'Rusak Blok','val'=>$uRBL]])->sortByDesc('val');
    $dominantMulti = $multiRows->first()['label'] ?? '-';
@endphp

<div class="row g-3 mb-4">
    @foreach([['Mono', $totalMono, 'primary', 'bi-grid-3x3'], ['Multi', $totalMulti, 'indigo', 'bi-grid-3x3-gap']] as $data)
    <div class="col-6 col-md-3">
        <div class="card stat-card h-100">
            <div class="card-body d-flex gap-3 align-items-center">
                <div class="stat-icon bg-{{$data[2]}} bg-opacity-10 text-{{$data[2]}}"><i class="bi {{$data[3]}}"></i></div>
                <div>
                    <div class="stat-label">Total {{$data[0]}}</div>
                    <div class="stat-value text-{{$data[2]}}">{{ number_format($data[1]) }}</div>
                </div>
            </div>
        </div>
    </div>
    @endforeach
    <div class="col-6 col-md-3">
        <div class="card stat-card h-100">
            <div class="card-body d-flex gap-3 align-items-center">
                <div class="stat-icon bg-warning bg-opacity-10 text-warning"><i class="bi bi-trophy"></i></div>
                <div>
                    <div class="stat-label">Dominan Mono</div>
                    <div class="stat-value text-warning" style="font-size:1.5rem">{{ $dominantMono }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card stat-card h-100">
            <div class="card-body d-flex gap-3 align-items-center">
                <div class="stat-icon bg-danger bg-opacity-10 text-danger"><i class="bi bi-trophy-fill"></i></div>
                <div>
                    <div class="stat-label">Dominan Multi</div>
                    <div class="stat-value text-danger" style="font-size:1.5rem">{{ $dominantMulti }}</div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    @foreach([['title'=>'Monofilament', 'total'=>$totalMono, 'rows'=>$monoRows, 'color'=>'#3b82f6'], ['title'=>'Multifilament', 'total'=>$totalMulti, 'rows'=>$multiRows, 'color'=>'#6366f1']] as $tbl)
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm overflow-hidden">
            <div class="card-header bg-white border-bottom p-3">
                <div class="section-chip" style="background:#f1f5f9;color:#334155">{{$tbl['title']}}</div>
            </div>
            <div class="table-responsive">
                <table class="table qcc-table">
                    <thead>
                        <tr>
                            <th style="width:60px" class="ps-3">Rank</th>
                            <th>Kategori</th>
                            <th class="text-end">Pcs</th>
                            <th style="width:140px">Persentase</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($tbl['rows'] as $i => $row)
                        @php $pct = $tbl['total'] > 0 ? ($row['val'] / $tbl['total']) * 100 : 0; @endphp
                        <tr>
                            <td class="ps-3"><span class="rank-badge rank-{{$loop->iteration <= 3 ? $loop->iteration : ''}}">{{$loop->iteration}}</span></td>
                            <td class="fw-bold text-dark">{{$row['label']}}</td>
                            <td class="text-end">{{number_format($row['val'])}}</td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="pct-bar flex-grow-1"><div class="pct-bar-fill" style="width:{{$pct}}%;background:{{$tbl['color']}}"></div></div>
                                    <small class="text-muted" style="width:35px">{{number_format($pct,0)}}%</small>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endforeach
</div>

<div class="mt-4 card border-0 shadow-sm col-lg-5">
    <div class="card-header bg-white fw-bold border-bottom">Keterangan Defect</div>
    <div class="card-body">
        <ul class="list-unstyled mb-0 small text-muted">
            <li><span class="fw-bold text-dark">RR</span> - Rusak Ringan | <span class="fw-bold text-dark">PR</span> - Parah Ringan</li>
            <li><span class="fw-bold text-dark">RPS</span> - Rusak Parah Sekali | <span class="fw-bold text-dark">RJ</span> - Rusak Jalur</li>
            <li><span class="fw-bold text-dark">SUPER</span> - Ketebalan tidak sesuai | <span class="fw-bold text-dark">Berbulu</span> - Serat terurai</li>
            <li><span class="fw-bold text-dark">Rusak Blok</span> - Mata jaring rusak</li>
        </ul>
    </div>
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Gunakan input search dari navbar (class search-input)
    const searchInput = document.querySelector('.search-input');
    
    if (searchInput) {
        searchInput.addEventListener('input', function () {
            const term = this.value.toLowerCase();
            // Cari di semua baris tabel qcc-table
            document.querySelectorAll('.qcc-table tbody tr').forEach(tr => {
                const txt = tr.textContent.toLowerCase();
                tr.style.display = txt.includes(term) ? '' : 'none';
            });
        });
    }
});
</script>
@endpush
