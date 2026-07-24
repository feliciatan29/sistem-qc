@extends('qc.layoutqc')

@section('title', 'Estimasi Kerugian (QLF)')

@push('styles')
<style>
  .summary-card {
    display: flex;
    align-items: center;
    gap: 1rem;
    background: #fff;
    border-radius: 1rem;
    box-shadow: 0 .25rem .75rem rgba(0, 0, 0, .06);
    padding: 1.1rem 1.25rem;
    height: 100%;
    transition: transform .2s ease, box-shadow .2s ease;
  }

  .summary-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 .75rem 1.5rem rgba(0, 0, 0, .1);
  }

  .summary-icon {
    flex-shrink: 0;
    width: 3rem;
    height: 3rem;
    border-radius: .85rem;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
    color: #fff;
  }

  .summary-icon.bg-primary-soft { background: #0d6efd; }
  .summary-icon.bg-success-soft { background: #198754; }
  .summary-icon.bg-warning-soft { background: #ffc107; color: #212529; }
  .summary-icon.bg-danger-soft { background: #dc3545; }

  .summary-copy .summary-title {
    font-size: .8rem;
    color: #6c757d;
    margin-bottom: .2rem;
  }

  .summary-copy .summary-value {
    font-weight: 600;
    font-size: 1rem;
    margin-bottom: 0;
  }

  .chart-placeholder {
    min-height: 260px;
    display: flex;
    flex-direction: column;
    justify-content: center;
    gap: .5rem;
  }
</style>
@endpush

@section('content')

  {{-- ==================== HEADER DASHBOARD ==================== --}}
  <div class="page-heading">
    <div class="page-heading-copy">
      <span class="page-icon"><i class="bi bi-cash-stack" aria-hidden="true"></i></span>
      <div>
        <p class="eyebrow mb-1">Quality Control</p>
        <h1 class="h3 mb-1">Estimasi Kerugian (Quality Loss Function)</h1>
        <p class="text-muted mb-0">Menghitung estimasi kerugian kualitas berdasarkan input data produksi Anda.</p>
      </div>
    </div>
  </div>

  @if(session('success'))
      <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
          {{ session('success') }}
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
  @endif

  <form method="POST" action="{{ route('qc.qlf.index') }}" id="qlfForm">
      @csrf
      {{-- ==================== FORM PARAMETER ==================== --}}
      <div class="panel mb-4">
        <div class="panel-header">
          <h2 class="h5 mb-0 section-title"><i class="bi bi-sliders text-primary me-2"></i>Parameter Konstanta QLF</h2>
        </div>
        <div class="panel-body p-3">
            <div class="row g-3">
                <div class="col-md-3">
                    <label for="biaya_kerugian" class="form-label text-muted small">Biaya Kerugian (Rp)</label>
                    <input type="number" class="form-control" id="biaya_kerugian" name="biaya_kerugian" value="{{ $A }}" required>
                </div>
                
                <div class="col-md-3">
                    <label for="batas_toleransi" class="form-label text-muted small">Batas Toleransi</label>
                    <input type="number" class="form-control" id="batas_toleransi" name="batas_toleransi" value="{{ $D }}" required step="any">
                </div>

                <div class="col-md-3">
                    <label for="hari_produksi" class="form-label text-muted small">Jumlah Hari Produksi</label>
                    <input type="number" class="form-control" id="hari_produksi" name="hari_produksi" value="{{ $jumlahHariProduksi }}" required min="1" max="31">
                </div>
            </div>

            <div class="mt-3 small text-muted">
                <em>Konstanta Quality Loss Function = {{ number_format($k, 4, ',', '.') }}</em>
            </div>
        </div>
      </div>

      {{-- ==================== INPUT DATA MANUAL ==================== --}}
      <div class="panel mb-4">
        <div class="panel-header d-flex justify-content-between align-items-center">
            <h2 class="h5 mb-0 section-title"><i class="bi bi-keyboard text-primary me-2"></i>Input Data Produksi</h2>
            <button type="button" class="btn btn-sm btn-outline-primary" id="addRowBtn">
                <i class="bi bi-plus-lg"></i> Tambah Baris
            </button>
        </div>
        <div class="panel-body p-0">
            <div class="table-responsive">
                <table class="table table-borderless mb-0 text-center" id="inputTable">
                    <thead class="table-light">
                        <tr>
                            <th class="text-start">Jenis Jaring</th>
                            <th>Target</th>
                            <th>Hasil Aktual</th>
                            <th>Produksi / Hari</th>
                            <th style="width: 50px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="inputBody">
                        @foreach($inputData as $index => $row)
                        <tr>
                            <td>
                                <input type="text" class="form-control" name="qlfdata[{{ $index }}][jenis_jaring]" value="{{ $row['jenis_jaring'] ?? '' }}" placeholder="Nama Jaring" required>
                            </td>
                            <td>
                                <input type="number" class="form-control" name="qlfdata[{{ $index }}][target]" value="{{ $row['target'] ?? 0 }}" step="any" required>
                            </td>
                            <td>
                                <input type="number" class="form-control" name="qlfdata[{{ $index }}][aktual]" value="{{ $row['aktual'] ?? 0 }}" step="any" required>
                            </td>
                            <td>
                                <input type="number" class="form-control" name="qlfdata[{{ $index }}][produksi_hari]" value="{{ $row['produksi_hari'] ?? 0 }}" step="any" required>
                            </td>
                            <td>
                                <button type="button" class="btn btn-sm btn-danger btn-remove-row" {{ count($inputData) <= 1 ? 'disabled' : '' }}>
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="panel-footer bg-light p-3 text-end">
            <button type="submit" class="btn btn-primary px-4"><i class="bi bi-calculator me-2"></i>Hitung Kalkulasi</button>
        </div>
      </div>
  </form>

  {{-- ==================== RINGKASAN ANALISIS ==================== --}}
  <section class="row g-3 mb-4" aria-label="Ringkasan Analisis">
    <div class="col-12 col-sm-6 col-xl-3">
      <div class="summary-card">
        <span class="summary-icon bg-warning-soft"><i class="bi bi-calendar-day" aria-hidden="true"></i></span>
        <div class="summary-copy">
          <p class="summary-title mb-1">Total Loss per Hari</p>
          <p class="summary-value text-truncate" title="Rp{{ number_format($totalLossHari, 0, ',', '.') }}">Rp{{ number_format($totalLossHari, 0, ',', '.') }}</p>
        </div>
      </div>
    </div>

    <div class="col-12 col-sm-6 col-xl-3">
      <div class="summary-card">
        <span class="summary-icon bg-primary-soft"><i class="bi bi-calendar-month" aria-hidden="true"></i></span>
        <div class="summary-copy">
          <p class="summary-title mb-1">Total Loss per Bulan</p>
          <p class="summary-value text-truncate" title="Rp{{ number_format($totalLossBulan, 0, ',', '.') }}">Rp{{ number_format($totalLossBulan, 0, ',', '.') }}</p>
        </div>
      </div>
    </div>

    <div class="col-12 col-sm-6 col-xl-3">
      <div class="summary-card">
        <span class="summary-icon bg-success-soft"><i class="bi bi-calendar3" aria-hidden="true"></i></span>
        <div class="summary-copy">
          <p class="summary-title mb-1">Total Loss per Tahun</p>
          <p class="summary-value text-truncate" title="Rp{{ number_format($totalLossTahun, 0, ',', '.') }}">Rp{{ number_format($totalLossTahun, 0, ',', '.') }}</p>
        </div>
      </div>
    </div>

    <div class="col-12 col-sm-6 col-xl-3">
      <div class="summary-card">
        <span class="summary-icon bg-danger-soft"><i class="bi bi-exclamation-triangle" aria-hidden="true"></i></span>
        <div class="summary-copy">
          <p class="summary-title mb-1">Kerugian Terbesar (Jaring)</p>
          <p class="summary-value text-truncate" title="{{ $jenisJaringTerbesar }}">{{ $jenisJaringTerbesar }}</p>
        </div>
      </div>
    </div>
  </section>

  {{-- ==================== TABEL HASIL ==================== --}}
  <div class="panel mb-4">
    <div class="panel-header">
      <h2 class="h5 mb-0 section-title"><i class="bi bi-table text-primary me-2"></i>Hasil Perhitungan QLF</h2>
    </div>
    <div class="panel-body p-0">
        <div class="table-responsive">
            <table class="table table-hover table-striped mb-0 text-center">
                <thead class="table-light">
                    <tr>
                        <th class="text-start">Jenis Jaring</th>
                        <th>Target</th>
                        <th>Hasil Aktual</th>
                        <th>Loss per Unit (Rp)</th>
                        <th>Produksi/Hari</th>
                        <th>Loss per Hari (Rp)</th>
                        <th>Loss per Bulan (Rp)</th>
                        <th>Loss per Tahun (Rp)</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tableData as $row)
                        <tr>
                            <td class="text-start fw-bold">{{ $row['jenis_jaring'] }}</td>
                            <td>{{ number_format($row['T'], 0, ',', '.') }}</td>
                            <td>{{ number_format($row['y'], 0, ',', '.') }}</td>
                            <td>{{ number_format($row['loss_per_unit'], 0, ',', '.') }}</td>
                            <td>{{ number_format($row['produksi_hari'], 0, ',', '.') }}</td>
                            <td>{{ number_format($row['loss_per_hari'], 0, ',', '.') }}</td>
                            <td>{{ number_format($row['loss_per_bulan'], 0, ',', '.') }}</td>
                            <td>{{ number_format($row['loss_per_tahun'], 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-4 text-muted">Tidak ada data untuk ditampilkan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
  </div>

  {{-- ==================== PANEL GRAFIK ==================== --}}
  <section class="row g-3 mb-4">
    <div class="col-12 col-xl-4">
      <div class="panel h-100">
        <div class="panel-header">
          <div>
            <h2 class="h5 mb-1 section-title"><i class="bi bi-bar-chart-fill text-primary" aria-hidden="true"></i><span>Total Kerugian (Bulan) per Jaring</span></h2>
          </div>
        </div>
        <div class="chart-placeholder px-3 pb-3">
          <canvas id="chartBarLoss"></canvas>
        </div>
      </div>
    </div>

    <div class="col-12 col-xl-4">
      <div class="panel h-100">
        <div class="panel-header">
          <div>
            <h2 class="h5 mb-1 section-title"><i class="bi bi-graph-up text-danger" aria-hidden="true"></i><span>Tren Kerugian (Line)</span></h2>
          </div>
        </div>
        <div class="chart-placeholder px-3 pb-3">
          <canvas id="chartLineLoss"></canvas>
        </div>
      </div>
    </div>

    <div class="col-12 col-xl-4">
      <div class="panel h-100">
        <div class="panel-header">
          <div>
            <h2 class="h5 mb-1 section-title"><i class="bi bi-pie-chart-fill text-warning" aria-hidden="true"></i><span>Kontribusi Kerugian (%)</span></h2>
          </div>
        </div>
        <div class="chart-placeholder px-3 pb-3">
          <canvas id="chartDoughnutLoss"></canvas>
        </div>
      </div>
    </div>
  </section>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Dynamic Form Row Script
    let rowIndex = {{ count($inputData) }};
    const inputBody = document.getElementById('inputBody');
    const addRowBtn = document.getElementById('addRowBtn');

    function updateRemoveButtons() {
        const rows = inputBody.querySelectorAll('tr');
        const removeBtns = inputBody.querySelectorAll('.btn-remove-row');
        if (rows.length <= 1) {
            removeBtns.forEach(btn => btn.disabled = true);
        } else {
            removeBtns.forEach(btn => btn.disabled = false);
        }
    }

    addRowBtn.addEventListener('click', function() {
        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td>
                <input type="text" class="form-control" name="qlfdata[${rowIndex}][jenis_jaring]" placeholder="Nama Jaring" required>
            </td>
            <td>
                <input type="number" class="form-control" name="qlfdata[${rowIndex}][target]" value="0" step="any" required>
            </td>
            <td>
                <input type="number" class="form-control" name="qlfdata[${rowIndex}][aktual]" value="0" step="any" required>
            </td>
            <td>
                <input type="number" class="form-control" name="qlfdata[${rowIndex}][produksi_hari]" value="0" step="any" required>
            </td>
            <td>
                <button type="button" class="btn btn-sm btn-danger btn-remove-row">
                    <i class="bi bi-trash"></i>
                </button>
            </td>
        `;
        inputBody.appendChild(tr);
        rowIndex++;
        updateRemoveButtons();
    });

    inputBody.addEventListener('click', function(e) {
        if (e.target.closest('.btn-remove-row')) {
            const rows = inputBody.querySelectorAll('tr');
            if (rows.length > 1) {
                e.target.closest('tr').remove();
                updateRemoveButtons();
            }
        }
    });

    // Chart.js Script
    const chartBarLabels = {!! json_encode($chartBarLabels) !!};
    const chartBarData = {!! json_encode($chartBarData) !!};
    const chartLineLabels = {!! json_encode($chartLineLabels) !!};
    const chartLineData = {!! json_encode($chartLineData) !!};
    const chartDoughnutData = {!! json_encode($chartDoughnutData) !!};

    const chartColors = [
        'rgba(13, 110, 253, 0.8)',
        'rgba(255, 193, 7, 0.8)',
        'rgba(220, 53, 69, 0.8)',
        'rgba(25, 135, 84, 0.8)',
        'rgba(111, 66, 193, 0.8)',
        'rgba(253, 126, 20, 0.8)',
        'rgba(32, 201, 151, 0.8)'
    ];

    const barDatasets = chartBarLabels.map((label, index) => {
        return {
            label: label,
            data: [chartBarData[index]],
            backgroundColor: chartColors[index % chartColors.length],
            borderRadius: 4
        };
    });

    // 1. Grafik Batang (Bar Chart)
    const ctxBar = document.getElementById('chartBarLoss').getContext('2d');
    new Chart(ctxBar, {
        type: 'bar',
        data: {
            labels: ['Total Kerugian per Jaring'],
            datasets: barDatasets
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: true,
                    position: 'bottom'
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            let label = context.dataset.label || '';
                            if (label) {
                                label += ': Rp';
                            }
                            if (context.parsed.y !== null) {
                                label += new Intl.NumberFormat('id-ID').format(context.parsed.y);
                            }
                            return label;
                        }
                    }
                }
            },
            scales: {
                y: { beginAtZero: true }
            }
        }
    });

    // 2. Grafik Line (Tren Kerugian)
    const ctxLine = document.getElementById('chartLineLoss').getContext('2d');
    new Chart(ctxLine, {
        type: 'line',
        data: {
            labels: chartLineLabels, 
            datasets: [{
                label: 'Tren Kerugian',
                data: chartLineData,
                borderColor: 'rgba(220, 53, 69, 1)',
                backgroundColor: 'rgba(220, 53, 69, 0.1)',
                borderWidth: 2,
                tension: 0.4,
                fill: true,
                pointBackgroundColor: 'rgba(220, 53, 69, 1)',
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            let label = context.label || '';
                            if (label) {
                                label += ' - Kerugian: Rp';
                            }
                            if (context.parsed.y !== null) {
                                label += new Intl.NumberFormat('id-ID').format(context.parsed.y);
                            }
                            return label;
                        }
                    }
                }
            },
            scales: {
                y: { beginAtZero: true }
            }
        }
    });

    // 3. Grafik Doughnut (Persentase Kontribusi)
    const ctxDoughnut = document.getElementById('chartDoughnutLoss').getContext('2d');
    new Chart(ctxDoughnut, {
        type: 'doughnut',
        data: {
            labels: chartBarLabels,
            datasets: [{
                data: chartDoughnutData,
                backgroundColor: [
                    'rgba(220, 53, 69, 0.8)',
                    'rgba(255, 193, 7, 0.8)',
                    'rgba(13, 110, 253, 0.8)',
                    'rgba(25, 135, 84, 0.8)',
                    'rgba(111, 66, 193, 0.8)',
                    'rgba(253, 126, 20, 0.8)',
                    'rgba(32, 201, 151, 0.8)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return context.label + ': ' + context.parsed + '%';
                        }
                    }
                }
            }
        }
    });
});
</script>
@endpush
