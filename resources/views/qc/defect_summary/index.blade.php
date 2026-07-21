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
    
    /* ── Utilities ──────────────────────────────────────── */
    .color-dot {
        display: inline-block; width: 10px; height: 10px; border-radius: 50%; margin-right: 8px;
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

<section class="panel mb-4">
    <div class="panel-header d-flex justify-content-between align-items-center flex-wrap gap-3 p-3 bg-white shadow-sm rounded-4">
        <div>
            <h2 class="h5 mb-0 fw-bold text-dark">
                <i class="bi bi-filter-circle text-primary me-2"></i> Filter Data Defect
            </h2>
        </div>
        <div class="d-flex align-items-center flex-wrap gap-2">
            <form id="filterForm" action="" method="GET" class="d-flex gap-2 align-items-center">
                <select name="periode" class="form-select form-select-sm" style="width: 220px; border-radius: 8px;">
                    <option value="">Semua Bulan dan Tahun</option>
                    
                    @if(count($availableYears) > 0)
                        <optgroup label="Tahun Keseluruhan">
                            @foreach($availableYears as $y)
                                <option value="{{ $y }}" {{ request('periode') == $y ? 'selected' : '' }}>Tahun {{ $y }}</option>
                            @endforeach
                        </optgroup>
                    @endif

                    @if(count($availablePeriods) > 0)
                        <optgroup label="Per Bulan">
                            @foreach($availablePeriods as $p)
                                @php
                                    $pYear = substr($p, 0, 4);
                                    $pMonth = substr($p, 5, 2);
                                    $pName = \Carbon\Carbon::create()->month((int)$pMonth)->translatedFormat('F') . ' ' . $pYear;
                                @endphp
                                <option value="{{ $p }}" {{ request('periode') == $p ? 'selected' : '' }}>{{ $pName }}</option>
                            @endforeach
                        </optgroup>
                    @endif
                </select>
                <button type="submit" id="btnSubmitFilter" class="btn btn-primary btn-sm d-flex align-items-center shadow-sm" style="border-radius: 8px;">
                    <i class="bi bi-search me-1"></i> Tampilkan
                </button>
            </form>
        </div>
    </div>
</section>

@if($isEmpty)
<div class="alert alert-warning d-flex align-items-center shadow-sm rounded-4 mb-4" role="alert" style="border-left: 5px solid #eab308; background-color: #fefce8; color: #854d0e; border-color: #fef08a;">
    <i class="bi bi-info-circle-fill fs-4 me-3"></i>
    <div>
        <strong>Perhatian!</strong> Data pemeriksaan QC pada periode ini belum tersedia.
    </div>
</div>
@endif

@php
    // FILTERED DATA
    $mRR = (int)($monoFiltered->rr ?? 0); $mPR = (int)($monoFiltered->pr ?? 0); $mRPS = (int)($monoFiltered->rps ?? 0); $mSUP = (int)($monoFiltered->super ?? 0); $mRJ = (int)($monoFiltered->rj ?? 0);
    $totalMono = $mRR + $mPR + $mRPS + $mSUP + $mRJ;
    $monoCek = (int)($monoFiltered->cek ?? 0);
    $monoBaik = (int)($monoFiltered->baik ?? 0);
    $monoRusak = $monoCek - $monoBaik;
    $monoRows = collect([['label'=>'RR','val'=>$mRR],['label'=>'PR','val'=>$mPR],['label'=>'RPS','val'=>$mRPS],['label'=>'SUPER','val'=>$mSUP],['label'=>'RJ','val'=>$mRJ]])->sortByDesc('val');
    $dominantMono = $monoRows->first()['label'] ?? '-';

    $uRR = (int)($multiFiltered->rr ?? 0); $uPR = (int)($multiFiltered->pr ?? 0); $uRPS = (int)($multiFiltered->rps ?? 0); $uSUP = (int)($multiFiltered->super ?? 0); $uRJ = (int)($multiFiltered->rj ?? 0); $uBER = (int)($multiFiltered->berbulu ?? 0); $uRBL = (int)($multiFiltered->rusak_blok ?? 0);
    $totalMulti = $uRR + $uPR + $uRPS + $uSUP + $uRJ + $uBER + $uRBL;
    $multiCek = (int)($multiFiltered->cek ?? 0);
    $multiBaik = (int)($multiFiltered->baik ?? 0);
    $multiRusak = $multiCek - $multiBaik;
    $multiRows = collect([['label'=>'RR','val'=>$uRR],['label'=>'PR','val'=>$uPR],['label'=>'RPS','val'=>$uRPS],['label'=>'SUPER','val'=>$uSUP],['label'=>'RJ','val'=>$uRJ],['label'=>'Berbulu','val'=>$uBER],['label'=>'Rusak Blok','val'=>$uRBL]])->sortByDesc('val');
    $dominantMulti = $multiRows->first()['label'] ?? '-';

    $grandCek = $monoCek + $multiCek;
    $grandBaik = $monoBaik + $multiBaik;
    $grandRusak = $monoRusak + $multiRusak;
    $grandTotalDefect = $totalMono + $totalMulti;

    // ALL-TIME DATA
    $amRR = (int)($monoAllTime->rr ?? 0); $amPR = (int)($monoAllTime->pr ?? 0); $amRPS = (int)($monoAllTime->rps ?? 0); $amSUP = (int)($monoAllTime->super ?? 0); $amRJ = (int)($monoAllTime->rj ?? 0);
    $totalMonoAllTime = $amRR + $amPR + $amRPS + $amSUP + $amRJ;
    $monoAllTimeRows = collect([['label'=>'RR','val'=>$amRR],['label'=>'PR','val'=>$amPR],['label'=>'RPS','val'=>$amRPS],['label'=>'SUPER','val'=>$amSUP],['label'=>'RJ','val'=>$amRJ]]);

    $auRR = (int)($multiAllTime->rr ?? 0); $auPR = (int)($multiAllTime->pr ?? 0); $auRPS = (int)($multiAllTime->rps ?? 0); $auSUP = (int)($multiAllTime->super ?? 0); $auRJ = (int)($multiAllTime->rj ?? 0); $auBER = (int)($multiAllTime->berbulu ?? 0); $auRBL = (int)($multiAllTime->rusak_blok ?? 0);
    $totalMultiAllTime = $auRR + $auPR + $auRPS + $auSUP + $auRJ + $auBER + $auRBL;
    $multiAllTimeRows = collect([['label'=>'RR','val'=>$auRR],['label'=>'PR','val'=>$auPR],['label'=>'RPS','val'=>$auRPS],['label'=>'SUPER','val'=>$auSUP],['label'=>'RJ','val'=>$auRJ],['label'=>'Berbulu','val'=>$auBER],['label'=>'Rusak Blok','val'=>$auRBL]]);
    $grandTotalDefectAllTime = $totalMonoAllTime + $totalMultiAllTime;
@endphp

<div class="row g-3 mb-4">
    <div class="col-12 col-md-4">
        <div class="card stat-card h-100 rounded-4" style="background: #f8fafc;">
            <div class="card-body d-flex gap-3 align-items-center">
                <div class="stat-icon bg-secondary bg-opacity-10 text-secondary"><i class="bi bi-box-seam"></i></div>
                <div>
                    <div class="stat-label">Total Keseluruhan Cek</div>
                    <div class="stat-value text-secondary">{{ number_format($grandCek) }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-4">
        <div class="card stat-card h-100 rounded-4" style="background: #f0fdf4;">
            <div class="card-body d-flex gap-3 align-items-center">
                <div class="stat-icon bg-success bg-opacity-10 text-success"><i class="bi bi-check-circle"></i></div>
                <div>
                    <div class="stat-label">Total Keseluruhan Baik</div>
                    <div class="stat-value text-success">{{ number_format($grandBaik) }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-4">
        <div class="card stat-card h-100 rounded-4" style="background: #fef2f2;">
            <div class="card-body d-flex gap-3 align-items-center">
                <div class="stat-icon bg-danger bg-opacity-10 text-danger"><i class="bi bi-x-circle"></i></div>
                <div>
                    <div class="stat-label">Total Jaring Rusak</div>
                    <div class="stat-value text-danger">{{ number_format($grandRusak) }}</div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Chart Section -->
<div class="card border-0 shadow-sm mb-4 rounded-4">
    <div class="card-header bg-white border-bottom p-3">
        <h5 class="mb-0 fw-bold"><i class="bi bi-bar-chart-fill text-primary me-2"></i> Grafik Komparasi Defect (Mono vs Multi)</h5>
    </div>
    <div class="card-body p-4">
        @if($isEmpty)
            <div class="text-center text-muted py-4">
                <i class="bi bi-bar-chart text-muted opacity-25" style="font-size: 4rem;"></i>
                <p class="mt-3 mb-0">Grafik tidak tersedia karena tidak ada data pada periode ini.</p>
            </div>
        @else
            <canvas id="defectChart" height="80"></canvas>
        @endif
    </div>
</div>

<div class="row g-3 mb-4">
    @foreach([['Mono', $totalMono, 'primary', 'bi-grid-3x3'], ['Multi', $totalMulti, 'indigo', 'bi-grid-3x3-gap']] as $data)
    <div class="col-6 col-md-3">
        <div class="card stat-card h-100 rounded-4">
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
        <div class="card stat-card h-100 rounded-4">
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
        <div class="card stat-card h-100 rounded-4">
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
    @foreach([['title'=>'Monofilament', 'total'=>$totalMono, 'rows'=>$monoRows, 'color'=>'#3b82f6', 'totalAllTime'=>$totalMonoAllTime, 'allTimeRows'=>$monoAllTimeRows], ['title'=>'Multifilament', 'total'=>$totalMulti, 'rows'=>$multiRows, 'color'=>'#6366f1', 'totalAllTime'=>$totalMultiAllTime, 'allTimeRows'=>$multiAllTimeRows]] as $tbl)
    <div class="{{ $isFiltered ? 'col-lg-12 col-xl-6' : 'col-lg-6' }} d-flex align-items-stretch">
        <div class="card border-0 shadow-sm overflow-hidden h-100 rounded-4 w-100">
            <div class="card-header bg-white border-bottom p-3">
                <div class="section-chip" style="background:#f1f5f9;color:#334155">{{$tbl['title']}}</div>
                @if($isFiltered)
                <span class="badge bg-primary ms-2">Periode: {{ $filterLabel }}</span>
                @endif
            </div>
            <div class="table-responsive">
                <table class="table qcc-table mb-0">
                    <thead>
                        <tr>
                            <th style="width:60px" class="ps-3">Rank</th>
                            <th>Kategori</th>
                            @if($isFiltered)
                            <th class="text-end">Pcs ({{ $filterLabel }})</th>
                            <th style="width:145px">Persentase ({{ $filterLabel }})</th>
                            <th class="text-end" style="background: #f8fafc; border-left: 1px solid #e2e8f0;">Pcs (All-Time)</th>
                            <th style="width:145px; background: #f8fafc;">Persentase (All-Time)</th>
                            @else
                            <th class="text-end">Pcs</th>
                            <th style="width:140px">Persentase</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $colors = [
                                'RR' => '#facc15', // yellow-400
                                'PR' => '#f97316', // orange-500
                                'RPS' => '#ef4444', // red-500
                                'SUPER' => '#8b5cf6', // violet-500
                                'RJ' => '#3b82f6', // blue-500
                                'Berbulu' => '#14b8a6', // teal-500
                                'Rusak Blok' => '#64748b' // slate-500
                            ];
                        @endphp
                        @foreach($tbl['rows'] as $i => $row)
                        @php 
                            $pct = $grandTotalDefect > 0 ? ($row['val'] / $grandTotalDefect) * 100 : 0; 
                            $allTimeRow = $tbl['allTimeRows']->firstWhere('label', $row['label']);
                            $allTimeVal = $allTimeRow ? $allTimeRow['val'] : 0;
                            $pctAllTime = $grandTotalDefectAllTime > 0 ? ($allTimeVal / $grandTotalDefectAllTime) * 100 : 0;
                        @endphp
                        <tr>
                            <td class="ps-3"><span class="rank-badge rank-{{$loop->iteration <= 3 ? $loop->iteration : ''}}">{{$loop->iteration}}</span></td>
                            <td class="fw-bold text-dark">
                                <span class="color-dot" style="background: {{ $colors[$row['label']] ?? '#ccc' }}"></span>
                                {{$row['label']}}
                            </td>
                            <td class="text-end fw-bold text-dark">{{number_format($row['val'])}}</td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="pct-bar flex-grow-1"><div class="pct-bar-fill" style="width:{{$pct}}%;background:{{$tbl['color']}}"></div></div>
                                    <small class="text-muted fw-bold" style="width:35px">{{number_format($pct,0)}}%</small>
                                </div>
                            </td>
                            @if($isFiltered)
                            <td class="text-end text-muted" style="background: #f8fafc; border-left: 1px solid #e2e8f0;">{{number_format($allTimeVal)}}</td>
                            <td style="background: #f8fafc;">
                                <div class="d-flex align-items-center gap-2 opacity-75">
                                    <div class="pct-bar flex-grow-1" style="background:#e2e8f0"><div class="pct-bar-fill" style="width:{{$pctAllTime}}%;background:#94a3b8"></div></div>
                                    <small class="text-muted" style="width:35px">{{number_format($pctAllTime,0)}}%</small>
                                </div>
                            </td>
                            @endif
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endforeach
</div>

<div class="mt-4 card border-0 shadow-sm col-lg-5 rounded-4 mb-4">
    <div class="card-header bg-white fw-bold border-bottom p-3">
        <i class="bi bi-info-circle text-primary me-2"></i> Keterangan Defect
    </div>
    <div class="card-body p-4">
        <ul class="list-unstyled mb-0 small text-muted lh-lg">
            <li><span class="color-dot" style="background:#facc15"></span><span class="fw-bold text-dark">RR</span> - Rusak Ringan</li>
            <li><span class="color-dot" style="background:#f97316"></span><span class="fw-bold text-dark">PR</span> - Parah Ringan</li>
            <li><span class="color-dot" style="background:#ef4444"></span><span class="fw-bold text-dark">RPS</span> - Rusak Parah Sekali</li>
            <li><span class="color-dot" style="background:#3b82f6"></span><span class="fw-bold text-dark">RJ</span> - Rusak Jalur</li>
            <li><span class="color-dot" style="background:#8b5cf6"></span><span class="fw-bold text-dark">SUPER</span> - Ketebalan tidak sesuai</li>
            <li><span class="color-dot" style="background:#14b8a6"></span><span class="fw-bold text-dark">Berbulu</span> - Serat terurai</li>
            <li><span class="color-dot" style="background:#64748b"></span><span class="fw-bold text-dark">Rusak Blok</span> - Mata jaring rusak</li>
        </ul>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Loading Spinner on Filter Form Submit
    const filterForm = document.getElementById('filterForm');
    const submitBtn = document.getElementById('btnSubmitFilter');
    if (filterForm) {
        filterForm.addEventListener('submit', function() {
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>';
            submitBtn.disabled = true;
        });
    }

    // Chart.js initialization
    const chartData = {!! isset($chartData) ? json_encode($chartData) : 'null' !!};
    if (chartData) {
        const ctx = document.getElementById('defectChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: chartData.categories,
                datasets: [
                    {
                        label: 'Monofilament',
                        data: chartData.mono,
                        backgroundColor: 'rgba(59, 130, 246, 0.8)',
                        borderColor: 'rgba(59, 130, 246, 1)',
                        borderWidth: 1,
                        borderRadius: 4
                    },
                    {
                        label: 'Multifilament',
                        data: chartData.multi,
                        backgroundColor: 'rgba(99, 102, 241, 0.8)',
                        borderColor: 'rgba(99, 102, 241, 1)',
                        borderWidth: 1,
                        borderRadius: 4
                    }
                ]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { precision: 0 }
                    }
                },
                plugins: {
                    tooltip: {
                        mode: 'index',
                        intersect: false
                    }
                }
            }
        });
    }

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
