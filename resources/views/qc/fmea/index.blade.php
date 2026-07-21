@extends('qc.layoutqc')

@push('styles')
    <style>
        .table-fmea thead th {
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            background: #f8fafc;
            color: #475569;
            vertical-align: middle;
            text-align: center;
        }
        .table-fmea tbody td {
            vertical-align: middle;
            font-size: 0.9rem;
        }
        .input-fmea {
            width: 70px;
            text-align: center;
            font-weight: 600;
        }
        .rank-1 { background: #ef4444; color: white; border-radius: 4px; padding: 2px 8px; font-weight: bold; }
        .rank-2 { background: #f97316; color: white; border-radius: 4px; padding: 2px 8px; font-weight: bold; }
        .rank-3 { background: #f59e0b; color: white; border-radius: 4px; padding: 2px 8px; font-weight: bold; }
        
        .info-panel {
            background: #f1f5f9;
            border-left: 4px solid #3b82f6;
            border-radius: 8px;
            padding: 1.2rem;
        }
        .info-title {
            font-size: 1rem;
            font-weight: bold;
            color: #1e293b;
            margin-bottom: 0.8rem;
        }
        .info-list li {
            margin-bottom: 0.5rem;
            color: #334155;
            font-size: 0.9rem;
        }
    </style>
@endpush

@section('content')
<div class="page-heading mb-4">
    <div class="page-heading-copy">
        <span class="page-icon">
            <i class="bi bi-shield-exclamation"></i>
        </span>
        <div>
            <p class="eyebrow mb-1">Quality Control</p>
            <h1 class="h3 mb-1">Analisis FMEA (Failure Mode and Effect Analysis)</h1>
            <p class="text-muted mb-0">Analisis risiko defect berdasarkan metode FMEA pada Jaring Monofilament dan Multifilament.</p>
        </div>
    </div>
</div>

@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<!-- Filter Card -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <form action="{{ route('qc.fmea.index') }}" method="GET" class="row g-3 align-items-end">
            <div class="col-md-4">
                <label for="tahun" class="form-label fw-bold">Filter Tahun Produksi</label>
                <select name="tahun" id="tahun" class="form-select">
                    <option value="">-- Semua Tahun --</option>
                    @foreach($availableYears as $y)
                        <option value="{{ $y }}" {{ request('tahun') == $y ? 'selected' : '' }}>Tahun {{ $y }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <button type="submit" class="btn btn-primary px-4">
                    <i class="bi bi-filter me-2"></i> Terapkan Filter
                </button>
                @if(request('tahun'))
                    <a href="{{ route('qc.fmea.index') }}" class="btn btn-light ms-2">Reset</a>
                @endif
            </div>
        </form>
    </div>
</div>

@if($tahun != '')
<div class="alert alert-info border-0 shadow-sm mb-4">
    <i class="bi bi-info-circle-fill me-2"></i> Menampilkan Data Filter Untuk Tahun: <strong>{{ $tahun }}</strong> (Nilai S, O, D dan RPN menyesuaikan dari penilaian utama).
</div>

<!-- DATA FILTERED -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm border-top border-info border-3">
            <div class="card-header bg-white border-bottom p-3">
                <h5 class="mb-0 fw-bold"><i class="bi bi-grid-3x3 text-info me-2"></i> Hasil Filter FMEA - Monofilament (Tahun {{ $tahun }})</h5>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered table-fmea mb-0">
                    <thead>
                        <tr>
                            <th rowspan="2" class="text-start ps-4">Kategori Defect</th>
                            <th rowspan="2">Total Defect (Pcs)</th>
                            <th rowspan="2">Kontribusi (%)</th>
                            <th colspan="3">Penilaian Risiko</th>
                            <th rowspan="2">RPN (S×O×D)</th>
                            <th rowspan="2">Ranking</th>
                        </tr>
                        <tr>
                            <th>Severity (S)</th>
                            <th>Occurrence (O)</th>
                            <th>Detection (D)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($monoDataFiltered as $item)
                            <tr>
                                <td class="fw-bold ps-4 text-dark">{{ $item->kategori }}</td>
                                <td class="text-center">{{ number_format($item->total_pcs) }}</td>
                                <td class="text-center fw-semibold text-primary">{{ number_format($item->kontribusi, 2) }}%</td>
                                <td class="text-center bg-light">{{ $item->severity }}</td>
                                <td class="text-center bg-light">{{ $item->occurrence }}</td>
                                <td class="text-center bg-light">{{ $item->detection }}</td>
                                <td class="text-center fw-bold text-danger fs-5">{{ $item->rpn }}</td>
                                <td class="text-center">
                                    <span class="{{ $item->ranking == 1 ? 'rank-1' : ($item->ranking == 2 ? 'rank-2' : ($item->ranking == 3 ? 'rank-3' : 'fw-bold text-secondary')) }}">
                                        #{{ $item->ranking }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="row mb-5">
    <div class="col-12">
        <div class="card border-0 shadow-sm border-top border-info border-3">
            <div class="card-header bg-white border-bottom p-3">
                <h5 class="mb-0 fw-bold"><i class="bi bi-grid-3x3-gap text-info me-2"></i> Hasil Filter FMEA - Multifilament (Tahun {{ $tahun }})</h5>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered table-fmea mb-0">
                    <thead>
                        <tr>
                            <th rowspan="2" class="text-start ps-4">Kategori Defect</th>
                            <th rowspan="2">Total Defect (Pcs)</th>
                            <th rowspan="2">Kontribusi (%)</th>
                            <th colspan="3">Penilaian Risiko</th>
                            <th rowspan="2">RPN (S×O×D)</th>
                            <th rowspan="2">Ranking</th>
                        </tr>
                        <tr>
                            <th>Severity (S)</th>
                            <th>Occurrence (O)</th>
                            <th>Detection (D)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($multiDataFiltered as $item)
                            <tr>
                                <td class="fw-bold ps-4 text-dark">{{ $item->kategori }}</td>
                                <td class="text-center">{{ number_format($item->total_pcs) }}</td>
                                <td class="text-center fw-semibold text-primary">{{ number_format($item->kontribusi, 2) }}%</td>
                                <td class="text-center bg-light">{{ $item->severity }}</td>
                                <td class="text-center bg-light">{{ $item->occurrence }}</td>
                                <td class="text-center bg-light">{{ $item->detection }}</td>
                                <td class="text-center fw-bold text-danger fs-5">{{ $item->rpn }}</td>
                                <td class="text-center">
                                    <span class="{{ $item->ranking == 1 ? 'rank-1' : ($item->ranking == 2 ? 'rank-2' : ($item->ranking == 3 ? 'rank-3' : 'fw-bold text-secondary')) }}">
                                        #{{ $item->ranking }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endif

<!-- DATA OVERALL -->
<div class="alert alert-secondary border-0 shadow-sm mb-4">
    <i class="bi bi-database me-2"></i> <strong>Data Analisis Keseluruhan (Semua Tahun)</strong> - Input penilaian S, O, D dapat disesuaikan di tabel ini.
</div>

<form action="{{ route('qc.fmea.update') }}" method="POST">
    @csrf

    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom p-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold"><i class="bi bi-grid-3x3 text-primary me-2"></i> Analisis FMEA Keseluruhan - Monofilament</h5>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered table-fmea mb-0">
                        <thead>
                            <tr>
                                <th rowspan="2" class="text-start ps-4">Kategori Defect</th>
                                <th rowspan="2">Total Defect (Pcs)</th>
                                <th rowspan="2">Kontribusi (%)</th>
                                <th colspan="3">Penilaian Risiko</th>
                                <th rowspan="2">RPN (S×O×D)</th>
                                <th rowspan="2">Ranking</th>
                            </tr>
                            <tr>
                                <th>Severity (S)</th>
                                <th>Occurrence (O)</th>
                                <th>Detection (D)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($monoDataOverall as $item)
                                <tr>
                                    <td class="fw-bold ps-4 text-dark">{{ $item->kategori }}</td>
                                    <td class="text-center">{{ number_format($item->total_pcs) }}</td>
                                    <td class="text-center fw-semibold text-primary">{{ number_format($item->kontribusi, 2) }}%</td>
                                    <td class="text-center">
                                        <input type="number" name="fmea[{{ $item->id }}][severity]" class="form-control input-fmea mx-auto shadow-sm" value="{{ $item->severity }}" min="1" max="10" required>
                                    </td>
                                    <td class="text-center">
                                        <input type="number" name="fmea[{{ $item->id }}][occurrence]" class="form-control input-fmea mx-auto shadow-sm" value="{{ $item->occurrence }}" min="1" max="10" required>
                                    </td>
                                    <td class="text-center">
                                        <input type="number" name="fmea[{{ $item->id }}][detection]" class="form-control input-fmea mx-auto shadow-sm" value="{{ $item->detection }}" min="1" max="10" required>
                                    </td>
                                    <td class="text-center fw-bold text-danger fs-5">{{ $item->rpn }}</td>
                                    <td class="text-center">
                                        <span class="{{ $item->ranking == 1 ? 'rank-1' : ($item->ranking == 2 ? 'rank-2' : ($item->ranking == 3 ? 'rank-3' : 'fw-bold text-secondary')) }}">
                                            #{{ $item->ranking }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom p-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold"><i class="bi bi-grid-3x3-gap text-indigo me-2"></i> Analisis FMEA Keseluruhan - Multifilament</h5>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered table-fmea mb-0">
                        <thead>
                            <tr>
                                <th rowspan="2" class="text-start ps-4">Kategori Defect</th>
                                <th rowspan="2">Total Defect (Pcs)</th>
                                <th rowspan="2">Kontribusi (%)</th>
                                <th colspan="3">Penilaian Risiko</th>
                                <th rowspan="2">RPN (S×O×D)</th>
                                <th rowspan="2">Ranking</th>
                            </tr>
                            <tr>
                                <th>Severity (S)</th>
                                <th>Occurrence (O)</th>
                                <th>Detection (D)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($multiDataOverall as $item)
                                <tr>
                                    <td class="fw-bold ps-4 text-dark">{{ $item->kategori }}</td>
                                    <td class="text-center">{{ number_format($item->total_pcs) }}</td>
                                    <td class="text-center fw-semibold text-primary">{{ number_format($item->kontribusi, 2) }}%</td>
                                    <td class="text-center">
                                        <input type="number" name="fmea[{{ $item->id }}][severity]" class="form-control input-fmea mx-auto shadow-sm" value="{{ $item->severity }}" min="1" max="10" required>
                                    </td>
                                    <td class="text-center">
                                        <input type="number" name="fmea[{{ $item->id }}][occurrence]" class="form-control input-fmea mx-auto shadow-sm" value="{{ $item->occurrence }}" min="1" max="10" required>
                                    </td>
                                    <td class="text-center">
                                        <input type="number" name="fmea[{{ $item->id }}][detection]" class="form-control input-fmea mx-auto shadow-sm" value="{{ $item->detection }}" min="1" max="10" required>
                                    </td>
                                    <td class="text-center fw-bold text-danger fs-5">{{ $item->rpn }}</td>
                                    <td class="text-center">
                                        <span class="{{ $item->ranking == 1 ? 'rank-1' : ($item->ranking == 2 ? 'rank-2' : ($item->ranking == 3 ? 'rank-3' : 'fw-bold text-secondary')) }}">
                                            #{{ $item->ranking }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="text-end mb-4">
        <button type="submit" class="btn btn-primary btn-lg shadow-sm px-4">
            <i class="bi bi-save me-2"></i> Simpan & Update RPN
        </button>
    </div>
</form>

<div class="row">
    <div class="col-lg-6 mb-4 mb-lg-0">
        <div class="info-panel h-100 shadow-sm">
            <div class="info-title"><i class="bi bi-info-square me-2 text-primary"></i> Definisi Penilaian Risiko</div>
            <ul class="list-unstyled info-list mb-0">
                <li><strong>Severity (S):</strong> Tingkat keparahan dampak defect terhadap kualitas jaring.</li>
                <li><strong>Occurrence (O):</strong> Frekuensi terjadinya defect berdasarkan data historis pemeriksaan QC.</li>
                <li><strong>Detection (D):</strong> Kemampuan proses QC dalam mendeteksi defect sebelum produk diproses lebih lanjut.</li>
            </ul>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="info-panel h-100 shadow-sm" style="border-left-color: #f59e0b; background-color: #fffbeb;">
            <div class="info-title"><i class="bi bi-exclamation-triangle me-2 text-warning"></i> Sumber Nilai S, O, dan D</div>
            <p class="mb-0 text-dark" style="font-size: 0.9rem; line-height: 1.6;">
                Nilai <strong>Severity (S)</strong>, <strong>Occurrence (O)</strong>, dan <strong>Detection (D)</strong> ditentukan secara manual oleh <strong>Admin QC</strong> berdasarkan data historis defect, kondisi proses produksi, hasil inspeksi, dan evaluasi kualitas. <br><br>Sistem tidak menghitung nilai S, O, dan D secara otomatis, namun sistem akan menghitung nilai <strong>RPN</strong> (Risk Priority Number) dan <strong>Ranking</strong> secara otomatis setelah nilai S, O, dan D di-simpan. Defect dengan nilai RPN tertinggi menjadi prioritas utama perbaikan.
            </p>
        </div>
    </div>
</div>
@endsection
