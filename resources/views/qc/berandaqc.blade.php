@extends('qc.layoutqc')

@section('title', 'Dashboard QC')

@push('styles')
<style>
  /* Tambahan CSS khusus Dashboard QC - tidak mengubah CSS utama template */

  .navbar-title {
    color: #212529;
    font-size: 1rem;
    white-space: nowrap;
  }

  /* Panel chart placeholder, siap diganti dengan canvas Chart.js */
  .chart-placeholder {
    min-height: 260px;
    display: flex;
    flex-direction: column;
    justify-content: center;
    gap: .5rem;
  }

  .chart-placeholder canvas {
    width: 100% !important;
    max-height: 240px;
  }

  /* Ringkasan Analisis - card horizontal */
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

  /* Card statistik: pastikan efek hover tetap konsisten walau tanpa mengubah metric-card asli */
  .metric-card {
    transition: transform .2s ease, box-shadow .2s ease;
  }

  .metric-card:hover {
    transform: translateY(-4px);
  }

  .fmea-list, .taguchi-list {
    list-style: none;
    margin: 0;
    padding: 0;
    width: 100%;
  }

  .fmea-list li, .taguchi-list li {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: .6rem .25rem;
    border-bottom: 1px dashed #e9ecef;
    font-size: .9rem;
  }

  .fmea-list li:last-child, .taguchi-list li:last-child {
    border-bottom: none;
  }
</style>
@endpush

@section('content')

  {{-- ==================== HEADER DASHBOARD ==================== --}}
  <div class="page-heading">
    <div class="page-heading-copy">
      <span class="page-icon"><i class="bi bi-shield-check" aria-hidden="true"></i></span>
      <div>
        <p class="eyebrow mb-1">Quality Control</p>
        <h1 class="h3 mb-1">Dashboard Quality Control</h1>
        <p class="text-muted mb-0">Monitoring hasil pengendalian kualitas produksi secara real-time.</p>
      </div>
    </div>
    <div class="heading-actions">
      <button class="btn btn-outline-secondary btn-sm" type="button"><i class="bi bi-download" aria-hidden="true"></i> Export</button>
      <button class="btn btn-primary btn-sm" type="button"><i class="bi bi-file-earmark-plus" aria-hidden="true"></i> Buat Laporan</button>
    </div>
  </div>

  {{-- ==================== CARD STATISTIK ==================== --}}
  <section class="row g-3 mt-1" aria-label="Statistik Quality Control">
    <div class="col-12 col-sm-6 col-xl-3">
      <article class="metric-card metric-primary h-100 d-flex flex-column justify-content-between">
        <div>
          <div class="metric-top">
            <span class="metric-label">Total Data Produksi</span>
            <span class="metric-icon"><i class="bi bi-box-seam" aria-hidden="true"></i></span>
          </div>
          <div class="metric-value">12.480</div>
        </div>
        <div class="metric-meta mt-auto pt-2">
          <span class="text-success">+4.2%</span>
          <span>dari bulan lalu</span>
        </div>
      </article>
    </div>

    <div class="col-12 col-sm-6 col-xl-3">
      <article class="metric-card metric-danger h-100 d-flex flex-column justify-content-between">
        <div>
          <div class="metric-top">
            <span class="metric-label">Total Data Defect</span>
            <span class="metric-icon"><i class="bi bi-exclamation-circle" aria-hidden="true"></i></span>
          </div>
          <div class="metric-value">342</div>
        </div>
        <div class="metric-meta mt-auto pt-2">
          <span class="text-danger">+1.8%</span>
          <span>dari bulan lalu</span>
        </div>
      </article>
    </div>

    <div class="col-12 col-sm-6 col-xl-3">
      <article class="metric-card metric-warning h-100 d-flex flex-column justify-content-between">
        <div>
          <div class="metric-top">
            <span class="metric-label">Persentase Defect</span>
            <span class="metric-icon"><i class="bi bi-percent" aria-hidden="true"></i></span>
          </div>
          <div class="metric-value">2,74%</div>
        </div>
        <div class="metric-meta mt-auto pt-2">
          <span class="text-muted">Batas toleransi 3%</span>
        </div>
      </article>
    </div>

    <div class="col-12 col-sm-6 col-xl-3">
      <article class="metric-card metric-success h-100 d-flex flex-column justify-content-between">
        <div>
          <div class="metric-top">
            <span class="metric-label">Defect Dominan</span>
            <span class="metric-icon"><i class="bi bi-bar-chart-fill" aria-hidden="true"></i></span>
          </div>
          <div class="metric-value text-truncate" title="Sobek Jaring" style="font-size: 1.75rem;">Sobek Jaring</div>
        </div>
        <div class="metric-meta mt-auto pt-2">
          <span class="text-muted">35% dari total defect</span>
        </div>
      </article>
    </div>
  </section>

  {{-- ==================== PANEL GRAFIK ==================== --}}
  <section class="row g-3 mt-1">
    <div class="col-12 col-xl-6">
      <div class="panel h-100">
        <div class="panel-header">
          <div>
            <h2 class="h5 mb-1 section-title"><i class="bi bi-graph-up-arrow text-primary" aria-hidden="true"></i><span>Grafik Defect Berdasarkan Jenis Jaring</span></h2>
            <p class="text-muted mb-0">Distribusi jumlah defect per jenis jaring produksi.</p>
          </div>
        </div>
        <div class="chart-placeholder px-3 pb-3">
          <canvas id="chartJenisJaring"></canvas>
        </div>
      </div>
    </div>

    <div class="col-12 col-xl-6">
      <div class="panel h-100">
        <div class="panel-header">
          <div>
            <h2 class="h5 mb-1 section-title"><i class="bi bi-bar-chart-steps text-danger" aria-hidden="true"></i><span>Diagram Pareto</span></h2>
            <p class="text-muted mb-0">Prioritas jenis defect berdasarkan frekuensi kumulatif.</p>
          </div>
        </div>
        <div class="chart-placeholder px-3 pb-3">
          <canvas id="chartPareto"></canvas>
        </div>
      </div>
    </div>

    <div class="col-12 col-xl-6">
      <div class="panel h-100">
        <div class="panel-header">
          <div>
            <h2 class="h5 mb-1 section-title"><i class="bi bi-diagram-3 text-warning" aria-hidden="true"></i><span>Prioritas Risiko FMEA</span></h2>
            <p class="text-muted mb-0">Peringkat risiko berdasarkan nilai RPN tertinggi.</p>
          </div>
        </div>
        <div class="chart-placeholder px-3 pb-3">
          <canvas id="chartFMEA"></canvas>
        </div>
      </div>
    </div>

    <div class="col-12 col-xl-6">
      <div class="panel h-100">
        <div class="panel-header">
          <div>
            <h2 class="h5 mb-1 section-title"><i class="bi bi-sliders text-success" aria-hidden="true"></i><span>Hasil Optimasi Taguchi</span></h2>
            <p class="text-muted mb-0">Kombinasi parameter optimal hasil perhitungan rasio S/N.</p>
          </div>
        </div>
        <div class="chart-placeholder px-3 pb-3">
            <canvas id="chartTaguchi"></canvas>
        </div>
      </div>
    </div>
  </section>

  {{-- ==================== RINGKASAN ANALISIS ==================== --}}
  <section class="row g-3 mt-1" aria-label="Ringkasan Analisis">
    <div class="col-12 col-sm-6 col-xl-3">
      <div class="summary-card">
        <span class="summary-icon bg-danger-soft"><i class="bi bi-diagram-3" aria-hidden="true"></i></span>
        <div class="summary-copy">
          <p class="summary-title mb-1">Faktor Prioritas Hasil FMEA</p>
          <p class="summary-value">RPN Tertinggi: Setting Mesin</p>
        </div>
      </div>
    </div>

    <div class="col-12 col-sm-6 col-xl-3">
      <div class="summary-card">
        <span class="summary-icon bg-success-soft"><i class="bi bi-sliders" aria-hidden="true"></i></span>
        <div class="summary-copy">
          <p class="summary-title mb-1">Rekomendasi Taguchi</p>
          <p class="summary-value">Parameter Optimal Telah Diperoleh</p>
        </div>
      </div>
    </div>

    <div class="col-12 col-sm-6 col-xl-3">
      <div class="summary-card">
        <span class="summary-icon bg-warning-soft"><i class="bi bi-cash-stack" aria-hidden="true"></i></span>
        <div class="summary-copy">
          <p class="summary-title mb-1">Estimasi Kerugian Kualitas</p>
          <p class="summary-value">Rp24.350.000</p>
        </div>
      </div>
    </div>

    <div class="col-12 col-sm-6 col-xl-3">
      <div class="summary-card">
        <span class="summary-icon bg-primary-soft"><i class="bi bi-activity" aria-hidden="true"></i></span>
        <div class="summary-copy">
          <p class="summary-title mb-1">Status Produksi</p>
          <p class="summary-value"><span class="badge text-bg-danger">Perlu Perbaikan</span></p>
        </div>
      </div>
    </div>
  </section>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Shared Chart Options for styling
    const commonOptions = {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: false
            }
        }
    };

    // 1. Grafik Defect Berdasarkan Jenis Jaring (Bar Chart)
    const ctxJenisJaring = document.getElementById('chartJenisJaring').getContext('2d');
    new Chart(ctxJenisJaring, {
        type: 'bar',
        data: {
            labels: ['Jaring A', 'Jaring B', 'Jaring C', 'Jaring D', 'Jaring E'],
            datasets: [{
                label: 'Jumlah Defect',
                data: [42, 58, 72, 51, 35],
                backgroundColor: 'rgba(13, 110, 253, 0.8)', // Primary blue
                borderRadius: 4
            }]
        },
        options: {
            ...commonOptions,
            scales: {
                y: { beginAtZero: true }
            }
        }
    });

    // 2. Diagram Pareto (Combo: Bar + Line)
    const ctxPareto = document.getElementById('chartPareto').getContext('2d');
    new Chart(ctxPareto, {
        type: 'bar',
        data: {
            labels: ['Sobek', 'Bolong', 'Simpul', 'Kotor', 'Lainnya'],
            datasets: [
                {
                    type: 'line',
                    label: 'Kumulatif %',
                    data: [35, 65, 85, 95, 100],
                    borderColor: 'rgba(220, 53, 69, 1)', // Danger red
                    backgroundColor: 'rgba(220, 53, 69, 0.2)',
                    borderWidth: 2,
                    tension: 0.3,
                    fill: false,
                    yAxisID: 'y1'
                },
                {
                    type: 'bar',
                    label: 'Frekuensi',
                    data: [83, 66, 48, 30, 18],
                    backgroundColor: 'rgba(108, 117, 125, 0.7)', // Secondary gray
                    borderRadius: 4,
                    yAxisID: 'y'
                }
            ]
        },
        options: {
            ...commonOptions,
            scales: {
                y: { 
                    beginAtZero: true,
                    title: { display: true, text: 'Frekuensi' }
                },
                y1: { 
                    beginAtZero: true, 
                    position: 'right', 
                    max: 100,
                    title: { display: true, text: 'Kumulatif %' },
                    grid: { drawOnChartArea: false }
                }
            }
        }
    });

    // 3. Prioritas Risiko FMEA (Horizontal Bar)
    const ctxFMEA = document.getElementById('chartFMEA').getContext('2d');
    new Chart(ctxFMEA, {
        type: 'bar',
        data: {
            labels: ['Setting Mesin', 'Kualitas Benang', 'Kondisi Jarum', 'Faktor Operator'],
            datasets: [{
                label: 'Nilai RPN',
                data: [216, 168, 140, 96],
                backgroundColor: [
                    'rgba(220, 53, 69, 0.8)', // Danger
                    'rgba(255, 193, 7, 0.8)', // Warning
                    'rgba(255, 193, 7, 0.8)',
                    'rgba(108, 117, 125, 0.8)' // Secondary
                ],
                borderRadius: 4
            }]
        },
        options: {
            ...commonOptions,
            indexAxis: 'y', // Horizontal
            scales: {
                x: { beginAtZero: true }
            }
        }
    });

    // 4. Hasil Optimasi Taguchi (Line Chart for S/N Ratio)
    const ctxTaguchi = document.getElementById('chartTaguchi').getContext('2d');
    new Chart(ctxTaguchi, {
        type: 'line',
        data: {
            labels: ['Exp 1', 'Exp 2', 'Exp 3', 'Exp 4', 'Exp 5', 'Exp 6', 'Exp 7', 'Exp 8', 'Exp 9'],
            datasets: [{
                label: 'S/N Ratio (dB)',
                data: [28.5, 30.1, 29.4, 31.0, 32.4, 30.8, 29.9, 31.5, 32.0],
                borderColor: 'rgba(25, 135, 84, 1)', // Success green
                backgroundColor: 'rgba(25, 135, 84, 0.1)',
                borderWidth: 2,
                tension: 0.4,
                fill: true,
                pointBackgroundColor: 'rgba(25, 135, 84, 1)',
                pointRadius: 4,
                pointHoverRadius: 6
            }]
        },
        options: {
            ...commonOptions,
            scales: {
                y: { 
                    beginAtZero: false,
                    title: { display: true, text: 'S/N Ratio (dB)' }
                }
            }
        }
    });
});
</script>
@endpush
