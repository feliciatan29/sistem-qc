@extends('produksi.layout')

@section('title', 'Dashboard Produksi')

@section('content')

    <div class="container-fluid px-3 px-lg-4 py-4">



        {{-- ================= CARD ================= --}}
        <div class="row g-4 mb-4">

            <div class="col-xl-3 col-md-6">

                <div class="card border-0 shadow-sm h-100">

                    <div class="card-body d-flex justify-content-between align-items-center">

                        <div>

                            <small class="text-muted">

                                Total Produksi

                            </small>

                            <h2 class="fw-bold mb-0">

                                152

                            </h2>

                        </div>

                        <div class="rounded-circle bg-primary bg-opacity-10 p-3">

                            <i class="bi bi-box-seam text-primary fs-2"></i>

                        </div>

                    </div>

                </div>

            </div>

            <div class="col-xl-3 col-md-6">

                <div class="card border-0 shadow-sm h-100">

                    <div class="card-body d-flex justify-content-between align-items-center">

                        <div>

                            <small class="text-muted">

                                Produksi Selesai

                            </small>

                            <h2 class="fw-bold mb-0">

                                130

                            </h2>

        </div>

                        <div class="rounded-circle bg-success bg-opacity-10 p-3">

                            <i class="bi bi-check-circle text-success fs-2"></i>

    </div>

                    </div>

                </div>

            </div>

            <div class="col-xl-3 col-md-6">

                <div class="card border-0 shadow-sm h-100">

                    <div class="card-body d-flex justify-content-between align-items-center">

                        <div>

                            <small class="text-muted">

                                Pengaturan Mesin

                            </small>

                            <h2 class="fw-bold mb-0">

                                20

                            </h2>

                        </div>

                        <div class="rounded-circle bg-warning bg-opacity-10 p-3">

                            <i class="bi bi-gear-fill text-warning fs-2"></i>

                        </div>

                    </div>

                </div>

            </div>

            <div class="col-xl-3 col-md-6">

                <div class="card border-0 shadow-sm h-100">

                    <div class="card-body d-flex justify-content-between align-items-center">

                        <div>

                            <small class="text-muted">

                                Kerusakan Mesin

                            </small>

                            <h2 class="fw-bold mb-0">

                                5

                            </h2>

                        </div>

                        <div class="rounded-circle bg-danger bg-opacity-10 p-3">

                            <i class="bi bi-exclamation-triangle text-danger fs-2"></i>

                        </div>

                    </div>

                </div>

            </div>

        </div>

        {{-- ================= GRAFIK ================= --}}
        <div class="row g-4 mb-4">

            <div class="col-lg-8">

                <div class="card shadow-sm border-0">

                    <div class="card-header bg-white">

                        <h5 class="mb-0">

                            Grafik Produksi per Bulan

                        </h5>

    </div>

                    <div class="card-body">

                        <canvas id="produksiChart" height="100"></canvas>

                    </div>

                </div>

            </div>

            <div class="col-lg-4">

                <div class="card shadow-sm border-0">

                    <div class="card-header bg-white">

                        <h5 class="mb-0">

                            Status Produksi

                        </h5>

                    </div>

                    <div class="card-body">

                        <canvas id="statusChart"></canvas>

                    </div>

                </div>

            </div>

        </div>

        {{-- ================= BAWAH ================= --}}
        <div class="row g-4">

            <div class="col-lg-7">

                <div class="card shadow-sm border-0">

                    <div class="card-header bg-white">

                        <h5 class="mb-0">

                            Aktivitas Produksi Terbaru

                        </h5>

    </div>

                    <div class="card-body p-0">

                        <table class="table mb-0">

                            <thead>

                                <tr>

                                    <th>ID</th>

                                    <th>Jenis Jaring</th>

                                    <th>Bulan</th>

                                    <th>Status</th>

                                </tr>

                            </thead>

                            <tbody>

                                <tr>

                                    <td>P001</td>

                                    <td>Jaring PE</td>

                                    <td>Juni</td>

                                    <td><span class="badge bg-success">Selesai</span></td>

                                </tr>

                                <tr>

                                    <td>P002</td>

                                    <td>Jaring HD</td>

                                    <td>Juni</td>

                                    <td><span class="badge bg-warning text-dark">Proses</span></td>

                                </tr>

                                <tr>

                                    <td>P003</td>

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

                <div class="card shadow-sm border-0 mb-4">

                    <div class="card-header bg-white">

                        <h5 class="mb-0">

                            Target Produksi

                        </h5>

                    </div>

                    <div class="card-body">

                        <h2 class="fw-bold">

                            73%

                        </h2>

                        <div class="progress" style="height:12px">

                            <div class="progress-bar bg-success" style="width:73%">

                            </div>

                        </div>

                    </div>

                </div>

                <div class="card shadow-sm border-0">

                    <div class="card-header bg-white">

                        <h5 class="mb-0">

                            Informasi Mesin

                        </h5>

                    </div>

                    <div class="card-body">

                        <p class="mb-2">

                            Mesin Aktif

                            <span class="float-end fw-bold">

                                18

                            </span>

                        </p>

                        <p class="mb-2">

                            Tidak Aktif

                            <span class="float-end fw-bold text-warning">

                                2

                            </span>

                        </p>

                        <p class="mb-0">

                            Rusak

                            <span class="float-end fw-bold text-danger">

                                1

                            </span>

                        </p>

                    </div>

        </div>

            </div>

    </div>

</div>

    {{-- ================= CHART ================= --}}

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        const produksiChart = new Chart(
            document.getElementById('produksiChart'), {
                type: 'line',

                data: {

                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun'],

                    datasets: [{

                        label: 'Produksi',

                        data: [120, 150, 180, 160, 220, 250],

                        fill: true,

                        borderWidth: 3,

                        tension: .4

                    }]

                },

                options: {
                    responsive: true
                }

            });

        const statusChart = new Chart(
            document.getElementById('statusChart'), {

                type: 'doughnut',

                data: {

                    labels: ['Selesai', 'Proses', 'Aktif'],

                    datasets: [{

                        data: [65, 20, 15]

                    }]

                },

                options: {
                    responsive: true
                }

            });
    </script>

@endsection
