@extends('produksi.layout')

@section('title', 'Dashboard Produksi')

@section('content')

    {{-- ==================== HEADER DASHBOARD ==================== --}}
    <div class="page-heading">
        <div class="page-heading-copy">
            <span class="page-icon"><i class="bi bi-speedometer2" aria-hidden="true"></i></span>
            <div>
                <p class="eyebrow mb-1">PRODUKSI</p>
                <h1 class="h3 mb-1">Dashboard Produksi</h1>
                <p class="text-muted mb-0">Sistem Manajemen Kualitas Jaring Industri</p>
            </div>
        </div>
    </div>

    {{-- ================= CARD STATISTIK ================= --}}
    <section class="row g-3 mt-1" aria-label="Statistik Produksi">
        <div class="col-12 col-sm-6 col-xl-3">
            <article class="metric-card metric-primary h-100 d-flex flex-column justify-content-between">
                <div>
                    <div class="metric-top">
                        <span class="metric-label">Total Produksi</span>
                        <span class="metric-icon"><i class="bi bi-box-seam" aria-hidden="true"></i></span>
                    </div>
                    <div class="metric-value">{{ $totalProduksi }}</div>
                </div>
            </article>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <article class="metric-card metric-success h-100 d-flex flex-column justify-content-between">
                <div>
                    <div class="metric-top">
                        <span class="metric-label">Produksi Selesai</span>
                        <span class="metric-icon"><i class="bi bi-check-circle" aria-hidden="true"></i></span>
                    </div>
                    <div class="metric-value">{{ $produksiSelesai }}</div>
                </div>
            </article>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <article class="metric-card metric-warning h-100 d-flex flex-column justify-content-between">
                <div>
                    <div class="metric-top">
                        <span class="metric-label">Pengaturan Mesin</span>
                        <span class="metric-icon"><i class="bi bi-gear-fill" aria-hidden="true"></i></span>
                    </div>
                    <div class="metric-value">{{ $totalPengaturan }}</div>
                </div>
            </article>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <article class="metric-card metric-danger h-100 d-flex flex-column justify-content-between">
                <div>
                    <div class="metric-top">
                        <span class="metric-label">Total Defect (QC)</span>
                        <span class="metric-icon"><i class="bi bi-exclamation-triangle" aria-hidden="true"></i></span>
                    </div>
                    <div class="metric-value">{{ $totalDefect ?? 0 }}</div>
                </div>
            </article>
        </div>
    </section>

    {{-- ================= GRAFIK ================= --}}
    <div class="row g-3 mt-1">
        <div class="col-lg-8">
            <div class="panel h-100">
                <div class="panel-header">
                    <div>
                        <h2 class="h5 mb-1 section-title"><i class="bi bi-graph-up text-primary" aria-hidden="true"></i><span>Grafik Jumlah Produksi Jaring</span></h2>
                    </div>
                </div>
                <div class="px-3 pb-3" style="min-height: 260px;">
                    <canvas id="produksiChart" height="100"></canvas>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="panel h-100">
                <div class="panel-header">
                    <div>
                        <h2 class="h5 mb-1 section-title"><i class="bi bi-pie-chart text-success" aria-hidden="true"></i><span>Status Produksi</span></h2>
                    </div>
                </div>
                <div class="px-3 pb-3" style="min-height: 260px; display:flex; justify-content:center;">
                    <canvas id="statusChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    {{-- ================= BAWAH ================= --}}
    <div class="row g-3 mt-1">
        <div class="col-lg-7">
            <div class="panel h-100">
                <div class="panel-header">
                    <div>
                        <h2 class="h5 mb-1 section-title"><i class="bi bi-clock-history text-secondary" aria-hidden="true"></i><span>Aktivitas Produksi Terbaru</span></h2>
                    </div>
                </div>
                <div class="table-responsive px-3 pb-3">
                    <table class="table mb-0 align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>Jenis Jaring</th>
                                <th>Bulan</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentProduksi as $rp)
                            <tr>
                                <td class="fw-bold">P{{ str_pad($rp->id, 3, '0', STR_PAD_LEFT) }}</td>
                                <td>{{ $rp->jenis_jaring }}</td>
                                <td>{{ \Carbon\Carbon::parse($rp->bulan_produksi)->translatedFormat('F') }}</td>
                                <td>
                                    @if($rp->status == 'Data Selesai')
                                        <span class="badge bg-danger">Data Selesai</span>
                                    @elseif($rp->status == 'Proses')
                                        <span class="badge bg-warning text-dark">Proses</span>
                                    @else
                                        <span class="badge bg-success">Aktif</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="4" class="text-center text-muted">Belum ada data</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-lg-5">
            <div class="panel mb-3">
                <div class="panel-header">
                    <div>
                        <h2 class="h5 mb-1 section-title"><i class="bi bi-bullseye text-danger" aria-hidden="true"></i><span>Target Produksi</span></h2>
                    </div>
                </div>
                <div class="px-3 pb-4">
                    <div class="d-flex justify-content-between align-items-end mb-2">
                        <h2 class="fw-bold mb-0">73%</h2>
                    </div>
                    <div class="progress" style="height:12px; border-radius: 6px;">
                        <div class="progress-bar bg-success" style="width:73%"></div>
                    </div>
                </div>
            </div>

            <div class="panel">
                <div class="panel-header">
                    <div>
                        <h2 class="h5 mb-1 section-title"><i class="bi bi-cpu text-info" aria-hidden="true"></i><span>Informasi Mesin</span></h2>
                    </div>
                </div>
                <div class="px-3 pb-3">
                    <div class="d-flex justify-content-between border-bottom py-2">
                        <span>Pengaturan Tersimpan</span>
                        <span class="fw-bold">{{ $totalPengaturan }}</span>
                    </div>
                    <div class="d-flex justify-content-between py-2">
                        <span>Total Cek QC</span>
                        <span class="fw-bold text-danger">{{ \App\Models\PemeriksaanQC::count() }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const produksiChart = new Chart(
            document.getElementById('produksiChart'), {
                type: 'bar',
                data: {
                    labels: {!! json_encode($months) !!},
                    datasets: {!! json_encode($chartDatasets) !!}
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: true }
                    }
                }
            }
        );

        const statusChart = new Chart(
            document.getElementById('statusChart'), {
                type: 'doughnut',
                data: {
                    labels: ['Data Selesai', 'Proses', 'Aktif'],
                    datasets: [{
                        data: [
                            {{ $statusCounts['Data Selesai'] ?? 0 }},
                            {{ $statusCounts['Proses'] ?? 0 }},
                            {{ $statusCounts['Aktif'] ?? 0 }}
                        ],
                        backgroundColor: [
                            'rgba(25, 135, 84, 0.8)',
                            'rgba(255, 193, 7, 0.8)',
                            'rgba(13, 110, 253, 0.8)'
                        ]
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false
                }
            }
        );
    });
    </script>
@endpush
