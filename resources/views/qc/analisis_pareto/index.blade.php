@extends('qc.layoutqc')

@section('title', 'Analisis Diagram Pareto')

@push('styles')
<style>
    /* ── Stat Cards ─────────────────────────────────────── */
    .stat-card {
        border-radius: 16px;
        border: 1px solid rgba(0,0,0,0.03);
        background: #fff;
        overflow: hidden;
        box-shadow: 0 4px 20px rgba(0,0,0,.04);
        transition: all .3s ease;
    }
    .stat-card:hover { transform: translateY(-5px); box-shadow: 0 12px 28px rgba(0,0,0,.08); }
    .stat-card .card-body { padding: 1.5rem; }
    .stat-icon {
        width: 54px; height: 54px; border-radius: 14px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.6rem; flex-shrink: 0;
    }
    .stat-label  { font-size: .8rem; font-weight: 600; text-transform: uppercase; letter-spacing: .05em; color: #64748b; margin-bottom: 0.2rem; }
    .stat-value  { font-size: 1.9rem; font-weight: 800; line-height: 1.1; letter-spacing: -0.02em; }

    /* ── Table Styling ──────────────────────────────────── */
    .pareto-table { margin-bottom: 0 !important; }
    .pareto-table thead th {
        background: #f8fafc;
        color: #64748b;
        font-size: .75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .05em;
        border-bottom: 2px solid #e2e8f0;
        padding: 1.2rem 1rem;
    }
    .pareto-table tbody td { 
        padding: 1rem; 
        vertical-align: middle; 
        border-bottom: 1px solid #f1f5f9; 
        font-size: 0.95rem;
        color: #334155;
    }
    .pareto-table tbody tr { transition: background-color 0.2s ease; }
    .pareto-table tbody tr:hover { background: #f8fafc; }

    /* ── Components ─────────────────────────────────────── */
    .rank-badge {
        display: inline-flex; align-items: center; justify-content: center;
        width: 28px; height: 28px; border-radius: 8px;
        font-size: .8rem; font-weight: 700;
        background: #f1f5f9; color: #64748b;
    }
    .rank-1 { background: #fee2e2; color: #b91c1c; }
    .rank-2 { background: #ffedd5; color: #c2410c; }
    .rank-3 { background: #fef3c7; color: #b45309; }
    
    .chart-container {
        position: relative;
        height: 450px;
        width: 100%;
    }
</style>
@endpush

@section('content')

<div class="page-heading mb-4 d-flex justify-content-between align-items-center">
    <div class="page-heading-copy">
        <span class="page-icon"><i class="bi bi-bar-chart-fill"></i></span>
        <div>
            <p class="eyebrow mb-1">Quality Control</p>
            <h1 class="h3 mb-1">Analisis Diagram Pareto</h1>
        </div>
    </div>
    
    <!-- Filter -->
    <div class="bg-white p-2 rounded-4 shadow-sm border border-light">
        <form id="filterForm" method="GET" action="{{ route('qc.analisis.pareto') }}" class="d-flex align-items-center flex-wrap gap-2 m-0">
            <label class="form-label mb-0 fw-bold text-muted small px-2">Filter:</label>
            
            <select name="jenis_jaring" class="form-select form-select-sm border-0 bg-light text-dark fw-semibold" style="border-radius: 6px; width: 150px; cursor: pointer;">
                <option value="Semua Data" {{ $filterJaring == 'Semua Data' ? 'selected' : '' }}>Semua Jenis</option>
                <option value="Monofilament" {{ $filterJaring == 'Monofilament' ? 'selected' : '' }}>Monofilament</option>
                <option value="Multifilament" {{ $filterJaring == 'Multifilament' ? 'selected' : '' }}>Multifilament</option>
            </select>

            <select name="periode" class="form-select form-select-sm border-0 bg-light text-dark fw-semibold" style="border-radius: 6px; width: 220px; cursor: pointer;">
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

            <button type="submit" id="btnSubmitFilter" class="btn btn-primary btn-sm d-flex align-items-center shadow-sm" style="border-radius: 6px;">
                <i class="bi bi-search me-1"></i> Tampilkan
            </button>
        </form>
    </div>
</div>

@php
    $blocks = [];
    if ($isFiltered && $dataFiltered) {
        $blocks[] = [
            'title' => 'Data Filter: ' . $filterLabel . ' (' . $filterJaring . ')',
            'data' => $dataFiltered,
            'chartId' => 'paretoChartFiltered'
        ];
    }
    
    $blocks[] = [
        'title' => 'Data Keseluruhan All-Time (' . $filterJaring . ')',
        'data' => $dataAllTime,
        'chartId' => 'paretoChartAllTime'
    ];
@endphp

@foreach($blocks as $idx => $block)
    @php
        $d = $block['data'];
        $totalDefect = $d['totalDefect'];
        $dominantDefect = $d['dominantDefect'];
        $dominantPercentage = $d['dominantPercentage'];
        $jumlahKategori = $d['jumlahKategori'];
        $defects = $d['defects'];
    @endphp

    <div class="mb-5">
        <h4 class="fw-bold mb-3 text-dark"><i class="bi bi-bar-chart-fill text-primary me-2"></i> {{ $block['title'] }}</h4>
        
        <!-- Summary Cards -->
        <div class="row g-3 mb-4">
            <div class="col-6 col-md-3">
                <div class="card stat-card h-100 rounded-4 shadow-sm border-0">
                    <div class="card-body d-flex gap-3 align-items-center">
                        <div class="stat-icon bg-primary bg-opacity-10 text-primary"><i class="bi bi-x-circle"></i></div>
                        <div>
                            <div class="stat-label">Total Defect</div>
                            <div class="stat-value text-primary">{{ number_format($totalDefect) }}</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card stat-card h-100 rounded-4 shadow-sm border-0">
                    <div class="card-body d-flex gap-3 align-items-center">
                        <div class="stat-icon bg-danger bg-opacity-10 text-danger"><i class="bi bi-exclamation-triangle"></i></div>
                        <div>
                            <div class="stat-label">Defect Dominan</div>
                            <div class="stat-value text-danger">{{ $dominantDefect }}</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card stat-card h-100 rounded-4 shadow-sm border-0">
                    <div class="card-body d-flex gap-3 align-items-center">
                        <div class="stat-icon bg-warning bg-opacity-10 text-warning"><i class="bi bi-pie-chart"></i></div>
                        <div>
                            <div class="stat-label">Persentase Dominan</div>
                            <div class="stat-value text-warning">{{ $dominantPercentage }}%</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card stat-card h-100 rounded-4 shadow-sm border-0">
                    <div class="card-body d-flex gap-3 align-items-center">
                        <div class="stat-icon bg-success bg-opacity-10 text-success"><i class="bi bi-tags"></i></div>
                        <div>
                            <div class="stat-label">Kategori Defect</div>
                            <div class="stat-value text-success">{{ $jumlahKategori }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <!-- Pareto Chart -->
            <div class="col-lg-7 d-flex align-items-stretch">
                <div class="card border-0 shadow-sm overflow-hidden h-100 rounded-4 w-100">
                    <div class="card-header bg-white border-bottom p-3">
                        <h5 class="mb-0 fw-bold text-dark">Grafik Pareto</h5>
                    </div>
                    <div class="card-body">
                        <div class="chart-container" style="position: relative; height:350px; width:100%">
                            <canvas id="{{ $block['chartId'] }}"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Data Table -->
            <div class="col-lg-5 d-flex align-items-stretch">
                <div class="card border-0 shadow-sm overflow-hidden h-100 rounded-4 w-100">
                    <div class="card-header bg-white border-bottom p-4">
                        <h5 class="mb-0 fw-bold text-dark"><i class="bi bi-list-columns-reverse text-primary me-2"></i> Detail Data Analisis</h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table pareto-table">
                                <thead>
                                    <tr>
                                        <th class="ps-4">Kategori Defect</th>
                                        <th class="text-center">Jumlah (Pcs)</th>
                                        <th class="text-center">% Kumulatif</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($defects as $index => $defect)
                                    <tr>
                                        <td class="ps-4">
                                            <div class="d-flex align-items-center gap-3">
                                                <div class="rank-badge rank-{{ $index < 3 ? ($index + 1) : '' }}">{{ $index + 1 }}</div>
                                                <span class="fw-bold text-dark">{{ $defect['kategori'] }}</span>
                                            </div>
                                        </td>
                                        <td class="text-center fw-bold text-dark">{{ number_format($defect['pcs']) }}</td>
                                        <td>
                                            <div class="d-flex align-items-center gap-2 justify-content-center">
                                                <span class="fw-bold {{ $defect['kumulatif'] >= 80 ? 'text-danger' : 'text-primary' }}">{{ $defect['kumulatif'] }}%</span>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="3" class="text-center py-4 text-muted">Data kosong</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endforeach

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

        const blocksData = {!! json_encode(array_map(function($b) {
            return [
                'chartId' => $b['chartId'],
                'chartData' => $b['data']['chartData'],
                'totalDefect' => $b['data']['totalDefect']
            ];
        }, $blocks)) !!};

        blocksData.forEach(function(block) {
            const canvas = document.getElementById(block.chartId);
            if (canvas) {
                const ctx = canvas.getContext('2d');
                
                new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: block.chartData.labels,
                            datasets: [
                                {
                                    label: 'Persentase Kumulatif (%)',
                                    data: block.chartData.kumulatif,
                                    type: 'line',
                                    borderColor: '#ef4444',
                                    backgroundColor: '#ef4444',
                                    borderWidth: 3,
                                    pointBackgroundColor: '#ffffff',
                                    pointBorderColor: '#ef4444',
                                    pointBorderWidth: 2,
                                    pointRadius: 4,
                                    pointHoverRadius: 6,
                                    yAxisID: 'y1',
                                    tension: 0.3
                                },
                                {
                                    label: 'Jumlah Defect (Pcs)',
                                    data: block.chartData.pcs,
                                    backgroundColor: '#3b82f6',
                                    borderRadius: 6,
                                    barPercentage: 0.6,
                                    yAxisID: 'y'
                                }
                            ]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            interaction: {
                                mode: 'index',
                                intersect: false,
                            },
                            plugins: {
                                legend: {
                                    position: 'top',
                                    labels: {
                                        usePointStyle: true,
                                        padding: 20,
                                        font: { family: "'Inter', sans-serif", size: 12, weight: '500' }
                                    }
                                },
                                tooltip: {
                                    backgroundColor: 'rgba(15, 23, 42, 0.9)',
                                    titleFont: { size: 13, family: "'Inter', sans-serif" },
                                    bodyFont: { size: 13, family: "'Inter', sans-serif" },
                                    padding: 12,
                                    cornerRadius: 8,
                                    callbacks: {
                                        label: function(context) {
                                            let label = context.dataset.label || '';
                                            if (label) label += ': ';
                                            if (context.datasetIndex === 0) {
                                                label += context.raw + '%';
                                            } else {
                                                label += context.raw + ' Pcs';
                                            }
                                            return label;
                                        }
                                    }
                                }
                            },
                            scales: {
                                x: {
                                    grid: { display: false },
                                    ticks: { font: { family: "'Inter', sans-serif", weight: '500' } }
                                },
                                y: {
                                    type: 'linear',
                                    display: true,
                                    position: 'left',
                                    title: { display: true, text: 'Jumlah Defect (Pcs)', font: { size: 12, weight: 'bold' } },
                                    grid: { color: '#f1f5f9' },
                                    beginAtZero: true
                                },
                                y1: {
                                    type: 'linear',
                                    display: true,
                                    position: 'right',
                                    title: { display: true, text: 'Persentase Kumulatif (%)', font: { size: 12, weight: 'bold' } },
                                    grid: { display: false },
                                    min: 0,
                                    max: 100,
                                    ticks: {
                                        callback: function(value) { return value + '%'; }
                                    }
                                }
                            }
                    }
                });
            }
        });
    });
</script>
@endpush
