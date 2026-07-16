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
    <div class="bg-white p-2 rounded-3 shadow-sm border border-light">
        <form method="GET" action="{{ route('qc.analisis.pareto') }}" class="d-flex align-items-center gap-2 m-0">
            <label for="jenis_jaring" class="form-label mb-0 fw-bold text-muted small px-2">Filter:</label>
            <select name="jenis_jaring" id="jenis_jaring" class="form-select border-0 bg-light text-dark fw-semibold" onchange="this.form.submit()" style="border-radius: 6px; width: 170px; cursor: pointer;">
                <option value="Semua Data" {{ $filterJaring == 'Semua Data' ? 'selected' : '' }}>Semua Data</option>
                <option value="Monofilament" {{ $filterJaring == 'Monofilament' ? 'selected' : '' }}>Monofilament</option>
                <option value="Multifilament" {{ $filterJaring == 'Multifilament' ? 'selected' : '' }}>Multifilament</option>
            </select>
        </form>
    </div>
</div>

<!-- Summary Cards -->
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="card stat-card h-100">
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
        <div class="card stat-card h-100">
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
        <div class="card stat-card h-100">
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
        <div class="card stat-card h-100">
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
    <div class="col-lg-7">
        <div class="card border-0 shadow-sm overflow-hidden h-100">
            <div class="card-header bg-white border-bottom p-3">
                <h5 class="mb-0 fw-bold text-dark">Grafik Pareto ({{ $filterJaring }})</h5>
            </div>
            <div class="card-body">
                @if($totalDefect > 0)
                    <div class="chart-container">
                        <canvas id="paretoChart"></canvas>
                    </div>
                @else
                    <div class="d-flex align-items-center justify-content-center h-100 text-muted">
                        <p class="mb-0">Tidak ada data defect.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Data Table -->
    <div class="col-lg-5">
        <div class="card border-0 shadow-sm overflow-hidden h-100">
            <div class="card-header bg-white border-bottom p-4">
                <h5 class="mb-0 fw-bold text-dark"><i class="bi bi-list-columns-reverse text-primary me-2"></i> Detail Data Analisis</h5>
            </div>
            <div class="table-responsive">
                <table class="table pareto-table">
                    <thead>
                        <tr>
                            <th style="width:70px" class="ps-4">Rank</th>
                            <th>Kategori Defect</th>
                            <th class="text-end">Total Pcs</th>
                            <th class="text-end">Kontribusi</th>
                            <th class="text-end pe-4">Kumulatif</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($defects as $index => $defect)
                        <tr>
                            <td class="ps-4">
                                <span class="rank-badge rank-{{ $index + 1 <= 3 ? $index + 1 : '' }}">
                                    {{ $index + 1 }}
                                </span>
                            </td>
                            <td class="fw-bold text-dark">{{ $defect['kategori'] }}</td>
                            <td class="text-end fw-semibold">{{ number_format($defect['pcs']) }}</td>
                            <td class="text-end">{{ $defect['persentase'] }}%</td>
                            <td class="text-end pe-4">{{ $defect['kumulatif'] }}%</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">Tidak ada data</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    @if($totalDefect > 0)
        const ctx = document.getElementById('paretoChart').getContext('2d');
        
        let barColor = 'rgba(54, 162, 235, 0.7)'; // Default blue
        let barBorderColor = 'rgba(54, 162, 235, 1)';
        
        @if($filterJaring == 'Monofilament')
            barColor = 'rgba(59, 130, 246, 0.7)';
            barBorderColor = 'rgba(59, 130, 246, 1)';
        @elseif($filterJaring == 'Multifilament')
            barColor = 'rgba(99, 102, 241, 0.7)';
            barBorderColor = 'rgba(99, 102, 241, 1)';
        @endif

        const chartData = {!! json_encode($chartData) !!};

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: chartData.labels,
                datasets: [
                    {
                        label: 'Persentase Kumulatif (%)',
                        data: chartData.kumulatif,
                        type: 'line',
                        borderColor: '#ef4444',
                        backgroundColor: '#ef4444',
                        borderWidth: 2,
                        pointBackgroundColor: '#ef4444',
                        pointRadius: 4,
                        pointHoverRadius: 6,
                        yAxisID: 'y1',
                        fill: false,
                        tension: 0.1
                    },
                    {
                        label: 'Total Pcs Defect',
                        data: chartData.pcs,
                        type: 'bar',
                        backgroundColor: barColor,
                        borderColor: barBorderColor,
                        borderWidth: 1,
                        borderRadius: 4,
                        yAxisID: 'y',
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
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.dataset.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                if (context.parsed.y !== null) {
                                    label += context.parsed.y;
                                    if (context.dataset.yAxisID === 'y1') {
                                        label += '%';
                                    }
                                }
                                return label;
                            }
                        }
                    },
                    legend: {
                        position: 'bottom'
                    }
                },
                scales: {
                    x: {
                        grid: {
                            display: false
                        }
                    },
                    y: {
                        type: 'linear',
                        display: true,
                        position: 'left',
                        title: {
                            display: true,
                            text: 'Total Pcs'
                        },
                        beginAtZero: true,
                        grid: {
                            color: '#f1f5f9'
                        }
                    },
                    y1: {
                        type: 'linear',
                        display: true,
                        position: 'right',
                        title: {
                            display: true,
                            text: 'Kumulatif (%)'
                        },
                        min: 0,
                        max: 100,
                        grid: {
                            drawOnChartArea: false,
                        }
                    }
                }
            }
        });
    @endif
});
</script>
@endpush

