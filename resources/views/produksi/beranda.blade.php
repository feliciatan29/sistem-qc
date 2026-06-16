@extends('produksi.layout')

@section('title', 'Dashboard Produksi')

@section('header', 'Dashboard Produksi')

@section('content')

<!-- Header Dashboard -->
<div class="page-heading mb-4">

    <div class="page-heading-copy">

        <span class="page-icon">
            <i class="bi bi-speedometer2"></i>
        </span>

        <div>
            <p class="eyebrow mb-1">
                Admin Produksi
            </p>

            <h2 class="mb-1">
                Dashboard Produksi
            </h2>

            <p class="text-muted mb-0">
                Monitoring Produksi Jaring Industri
            </p>
        </div>

    </div>

</div>

<!-- Statistik -->
<div class="row g-3 mb-4">

    <div class="col-md-3">

        <div class="metric-card metric-primary">

            <div class="metric-top">

                <span class="metric-label">
                    Total Produksi
                </span>

                <span class="metric-icon">
                    <i class="bi bi-box-seam"></i>
                </span>

            </div>

            <div class="metric-value">
                {{ $totalProduksi ?? 0 }}
            </div>

        </div>

    </div>

    <div class="col-md-3">

        <div class="metric-card metric-success">

            <div class="metric-top">

                <span class="metric-label">
                    Hasil Produksi
                </span>

                <span class="metric-icon">
                    <i class="bi bi-clipboard-data"></i>
                </span>

            </div>

            <div class="metric-value">
                {{ $hasilProduksi ?? 0 }}
            </div>

        </div>

    </div>

    <div class="col-md-3">

        <div class="metric-card metric-warning">

            <div class="metric-top">

                <span class="metric-label">
                    Pengaturan Mesin
                </span>

                <span class="metric-icon">
                    <i class="bi bi-sliders"></i>
                </span>

            </div>

            <div class="metric-value">
                {{ $totalPengaturan ?? 0 }}
            </div>

        </div>

    </div>

    <div class="col-md-3">

        <div class="metric-card metric-danger">

            <div class="metric-top">

                <span class="metric-label">
                    Data Kerusakan
                </span>

                <span class="metric-icon">
                    <i class="bi bi-exclamation-triangle"></i>
                </span>

            </div>

            <div class="metric-value">
                {{ $totalKerusakan ?? 0 }}
            </div>

        </div>

    </div>

</div>

<!-- Shortcut Menu -->
<div class="row g-3 mb-4">

    <div class="col-md-4">

        <div class="card shadow-sm h-100">

            <div class="card-body text-center">

                <i class="bi bi-box-seam fs-1"></i>

                <h5 class="mt-3">
                    Data Produksi
                </h5>

                <p class="text-muted">
                    Kelola data produksi jaring.
                </p>

                <a href="{{ route('produksi.index') }}"
                   class="btn btn-primary">

                    Buka Data Produksi

                </a>

            </div>

        </div>

    </div>

    <div class="col-md-4">

        <div class="card shadow-sm h-100">

            <div class="card-body text-center">

                <i class="bi bi-sliders fs-1"></i>

                <h5 class="mt-3">
                    Pengaturan Mesin
                </h5>

                <p class="text-muted">
                    Kelola setting mesin produksi.
                </p>

                <a href="{{ route('pengaturan.index') }}"
                   class="btn btn-warning">

                    Buka Pengaturan

                </a>

            </div>

        </div>

    </div>

    <div class="col-md-4">

        <div class="card shadow-sm h-100">

            <div class="card-body text-center">

                <i class="bi bi-exclamation-octagon fs-1"></i>

                <h5 class="mt-3">
                    Data Kerusakan
                </h5>

                <p class="text-muted">
                    Kelola data kerusakan mesin.
                </p>

                <a href="{{ route('kerusakan.index') }}"
                   class="btn btn-danger">

                    Buka Data Kerusakan

                </a>

            </div>

        </div>

    </div>

</div>

<!-- Grafik Produksi -->
<div class="card shadow-sm mb-4">

    <div class="card-header">

        <h5 class="mb-0">
            Grafik Produksi
        </h5>

    </div>

    <div class="card-body">

        <canvas id="grafikProduksi"></canvas>

    </div>

</div>

<!-- Hasil Produksi Terbaru -->
<div class="card shadow-sm">

    <div class="card-header">

        <h5 class="mb-0">
            Hasil Produksi Terbaru
        </h5>

    </div>

    <div class="card-body">

        <div class="table-responsive">

            <table class="table table-bordered align-middle">

                <thead>

                    <tr>
                        <th>Tanggal</th>
                        <th>Jenis Jaring</th>
                        <th>Mesin</th>
                        <th>Hasil Produksi</th>
                    </tr>

                </thead>

                <tbody>

                    @forelse($produksiTerbaru ?? [] as $data)

                    <tr>

                        <td>{{ $data->tanggal }}</td>
                        <td>{{ $data->jenis_jaring }}</td>
                        <td>{{ $data->mesin }}</td>
                        <td>{{ $data->hasil_produksi }}</td>

                    </tr>

                    @empty

                    <tr>

                        <td colspan="4" class="text-center">
                            Belum ada data produksi
                        </td>

                    </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

@endsection

@push('scripts')

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>

const ctx = document.getElementById('grafikProduksi');

new Chart(ctx, {

    type: 'line',

    data: {
        labels: @json($labels ?? []),

        datasets: [{
            label: 'Jumlah Produksi',
            data: @json($dataGrafik ?? []),
            borderWidth: 2
        }]
    }

});

</script>

@endpush
