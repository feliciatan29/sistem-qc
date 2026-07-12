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
      <article class="metric-card metric-primary">
        <div class="metric-top">
          <span class="metric-label">Total Data Produksi</span>
          <span class="metric-icon"><i class="bi bi-box-seam" aria-hidden="true"></i></span>
        </div>
        <div class="metric-value">12.480</div>
        <div class="metric-meta">
          <span class="text-success">+4.2%</span>
          <span>dari bulan lalu</span>
        </div>
      </article>
    </div>

    <div class="col-12 col-sm-6 col-xl-3">
      <article class="metric-card metric-danger">
        <div class="metric-top">
          <span class="metric-label">Total Data Defect</span>
          <span class="metric-icon"><i class="bi bi-exclamation-circle" aria-hidden="true"></i></span>
        </div>
        <div class="metric-value">342</div>
        <div class="metric-meta">
          <span class="text-danger">+1.8%</span>
          <span>dari bulan lalu</span>
        </div>
      </article>
    </div>

    <div class="col-12 col-sm-6 col-xl-3">
      <article class="metric-card metric-warning">
        <div class="metric-top">
          <span class="metric-label">Persentase Defect</span>
          <span class="metric-icon"><i class="bi bi-percent" aria-hidden="true"></i></span>
        </div>
        <div class="metric-value">2,74%</div>
        <div class="metric-meta">
          <span class="text-muted">Batas toleransi 3%</span>
        </div>
      </article>
    </div>

    <div class="col-12 col-sm-6 col-xl-3">
      <article class="metric-card metric-success">
        <div class="metric-top">
          <span class="metric-label">Defect Dominan</span>
          <span class="metric-icon"><i class="bi bi-bar-chart-fill" aria-hidden="true"></i></span>
        </div>
        <div class="metric-value">Sobek Jaring</div>
        <div class="metric-meta">
          <span class="text-muted">35% dari total defect</span>
        </div>
      </article>
    </div>
  </section>

  {{-- ==================== PANEL GRAFIK ==================== --}}
  <section class="row g-3 mt-1">
    <div class="col-12 col-xl-6">
      <div class="panel">
        <div class="panel-header">
          <div>
            <h2 class="h5 mb-1 section-title"><i class="bi bi-graph-up-arrow" aria-hidden="true"></i><span>Grafik Defect Berdasarkan Jenis Jaring</span></h2>
            <p class="text-muted mb-0">Distribusi jumlah defect per jenis jaring produksi.</p>
          </div>
        </div>

        {{-- TODO: Ganti dengan Chart.js, contoh: new Chart(document.getElementById('chartJenisJaring'), {...}) --}}
        <div class="chart-placeholder">
          <div class="chart-bars" aria-label="Grafik dummy defect berdasarkan jenis jaring">
            <div class="chart-column bar-42"><span></span><small>Jaring A</small></div>
            <div class="chart-column bar-58"><span></span><small>Jaring B</small></div>
            <div class="chart-column bar-72"><span></span><small>Jaring C</small></div>
            <div class="chart-column bar-51"><span></span><small>Jaring D</small></div>
            <div class="chart-column bar-35"><span></span><small>Jaring E</small></div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-12 col-xl-6">
      <div class="panel">
        <div class="panel-header">
          <div>
            <h2 class="h5 mb-1 section-title"><i class="bi bi-bar-chart-steps" aria-hidden="true"></i><span>Diagram Pareto</span></h2>
            <p class="text-muted mb-0">Prioritas jenis defect berdasarkan frekuensi kumulatif.</p>
          </div>
        </div>

        {{-- TODO: Ganti dengan Chart.js tipe combo (bar + line) untuk diagram pareto --}}
        <div class="chart-placeholder">
          <div class="chart-bars" aria-label="Grafik dummy diagram pareto">
            <div class="chart-column bar-83"><span></span><small>Sobek</small></div>
            <div class="chart-column bar-66"><span></span><small>Bolong</small></div>
            <div class="chart-column bar-48"><span></span><small>Simpul</small></div>
            <div class="chart-column bar-30"><span></span><small>Kotor</small></div>
            <div class="chart-column bar-18"><span></span><small>Lainnya</small></div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-12 col-xl-6">
      <div class="panel h-100">
        <div class="panel-header">
          <div>
            <h2 class="h5 mb-1 section-title"><i class="bi bi-diagram-3" aria-hidden="true"></i><span>Prioritas Risiko FMEA</span></h2>
            <p class="text-muted mb-0">Peringkat risiko berdasarkan nilai RPN tertinggi.</p>
          </div>
        </div>

        {{-- TODO: Ganti dengan tabel/Chart.js horizontal bar untuk nilai RPN --}}
        <div class="chart-placeholder">
          <ul class="fmea-list">
            <li><span>Setting Mesin</span> <span class="badge text-bg-danger">RPN 216</span></li>
            <li><span>Kualitas Benang</span> <span class="badge text-bg-warning">RPN 168</span></li>
            <li><span>Kondisi Jarum</span> <span class="badge text-bg-warning">RPN 140</span></li>
            <li><span>Faktor Operator</span> <span class="badge text-bg-secondary">RPN 96</span></li>
          </ul>
        </div>
      </div>
    </div>

    <div class="col-12 col-xl-6">
      <div class="panel h-100">
        <div class="panel-header">
          <div>
            <h2 class="h5 mb-1 section-title"><i class="bi bi-sliders" aria-hidden="true"></i><span>Hasil Optimasi Taguchi</span></h2>
            <p class="text-muted mb-0">Kombinasi parameter optimal hasil perhitungan rasio S/N.</p>
          </div>
        </div>

        {{-- TODO: Ganti dengan Chart.js line/bar untuk visualisasi rasio S/N --}}
        <div class="chart-placeholder">
          <ul class="taguchi-list">
            <li><span>Kecepatan Mesin</span> <span class="badge text-bg-success">Level 2</span></li>
            <li><span>Tegangan Benang</span> <span class="badge text-bg-success">Level 1</span></li>
            <li><span>Suhu Ruang Produksi</span> <span class="badge text-bg-success">Level 3</span></li>
            <li><span>Rasio S/N Optimal</span> <span class="badge text-bg-primary">32,4 dB</span></li>
          </ul>
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
