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
                    <div class="metric-value">152</div>
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
                    <div class="metric-value">130</div>
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
                    <div class="metric-value">20</div>
                </div>
            </article>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <article class="metric-card metric-danger h-100 d-flex flex-column justify-content-between">
                <div>
                    <div class="metric-top">
                        <span class="metric-label">Kerusakan Mesin</span>
                        <span class="metric-icon"><i class="bi bi-exclamation-triangle" aria-hidden="true"></i></span>
                    </div>
                    <div class="metric-value">5</div>
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
                        <h2 class="h5 mb-1 section-title"><i class="bi bi-graph-up text-primary" aria-hidden="true"></i><span>Grafik Produksi per Bulan</span></h2>
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
                            <tr>
                                <td class="fw-bold">P001</td>
                                <td>Jaring PE</td>
                                <td>Juni</td>
                                <td><span class="badge bg-success">Selesai</span></td>
                            </tr>
                            <tr>
                                <td class="fw-bold">P002</td>
                                <td>Jaring HD</td>
                                <td>Juni</td>
                                <td><span class="badge bg-warning text-dark">Proses</span></td>
                            </tr>
                            <tr>
                                <td class="fw-bold">P003</td>
                                <td>Jaring Nylon</td>
                                <td>Juli</td>
                                <td><span class="badge bg-primary">Aktif</span></td>
                            </tr>
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
                        <span>Mesin Aktif</span>
                        <span class="fw-bold">18</span>
                    </div>
                    <div class="d-flex justify-content-between border-bottom py-2">
                        <span>Tidak Aktif</span>
                        <span class="fw-bold text-warning">2</span>
                    </div>
                    <div class="d-flex justify-content-between py-2">
                        <span>Rusak</span>
                        <span class="fw-bold text-danger">1</span>
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
                type: 'line',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun'],
                    datasets: [{
                        label: 'Produksi',
                        data: [120, 150, 180, 160, 220, 250],
                        borderColor: 'rgba(13, 110, 253, 1)',
                        backgroundColor: 'rgba(13, 110, 253, 0.1)',
                        fill: true,
                        borderWidth: 3,
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false }
                    }
                }
            }
        );

        const statusChart = new Chart(
            document.getElementById('statusChart'), {
                type: 'doughnut',
                data: {
                    labels: ['Selesai', 'Proses', 'Aktif'],
                    datasets: [{
                        data: [65, 20, 15],
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
