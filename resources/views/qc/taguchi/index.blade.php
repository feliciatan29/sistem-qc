@extends('qc.layoutqc')

@push('styles')
    <style>
        .table-taguchi thead th {
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            background: #f8fafc;
            color: #475569;
            vertical-align: middle;
            text-align: center;
        }
        .table-taguchi tbody td {
            vertical-align: middle;
            font-size: 0.9rem;
            text-align: center;
        }
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
        .optimum-badge {
            background-color: #198754;
            color: white;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 0.8rem;
            font-weight: bold;
        }
        @media print {
            body * {
                visibility: hidden;
            }
            #print-area, #print-area * {
                visibility: visible;
            }
            #print-area {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
                margin: 0;
                padding: 0;
            }
            .no-print {
                display: none !important;
            }
            canvas {
                max-width: 100% !important;
            }
        }
    </style>
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endpush

@section('content')
<div class="page-heading mb-4">
    <div class="page-heading-copy">
        <span class="page-icon">
            <i class="bi bi-sliders"></i>
        </span>
        <div>
            <p class="eyebrow mb-1">Quality Control</p>
            <h1 class="h3 mb-1">Optimasi Taguchi L9 (S/N Ratio)</h1>
            <p class="text-muted mb-0">Analisis parameter mesin menggunakan matriks Orthogonal L9 dan ANOVA (Smaller is Better).</p>
        </div>
    </div>
</div>

<!-- Filter Card -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <form action="{{ route('qc.taguchi.index') }}" method="GET" class="row g-3 align-items-end">
            <div class="col-md-3">
                <label for="jenis_jaring" class="form-label fw-bold">Jenis Jaring</label>
                <select name="jenis_jaring" id="jenis_jaring" class="form-select" required>
                    <option value="">-- Pilih Jenis --</option>
                    <option value="Mono" {{ request('jenis_jaring') == 'Mono' ? 'selected' : '' }}>Monofilament</option>
                    <option value="Multi" {{ request('jenis_jaring') == 'Multi' ? 'selected' : '' }}>Multifilament</option>
                </select>
            </div>
            <div class="col-md-3">
                <label for="bulan" class="form-label fw-bold">Bulan</label>
                <select name="bulan" id="bulan" class="form-select">
                    <option value="">-- Semua Bulan --</option>
                    @for($i=1; $i<=12; $i++)
                        <option value="{{ $i }}" {{ request('bulan') == $i ? 'selected' : '' }}>{{ date('F', mktime(0, 0, 0, $i, 10)) }}</option>
                    @endfor
                </select>
            </div>
            <div class="col-md-3">
                <label for="tahun" class="form-label fw-bold">Tahun</label>
                <select name="tahun" id="tahun" class="form-select">
                    <option value="">-- Semua Tahun --</option>
                    @foreach($availableYears as $y)
                        <option value="{{ $y }}" {{ request('tahun') == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="bi bi-gear-fill me-2"></i> Proses Optimasi
                </button>
            </div>
        </form>
    </div>
</div>

@if(isset($error_taguchi))
    <div class="alert alert-warning border-0 shadow-sm mb-4 d-flex align-items-center">
        <i class="bi bi-exclamation-triangle-fill fs-4 text-warning me-3"></i> 
        <div>
            <strong>Peringatan Sistem!</strong><br>
            {{ $error_taguchi }}
        </div>
    </div>
@elseif(request('jenis_jaring'))
    
    @if(isset($info_taguchi))
    <div class="alert alert-warning border-0 shadow-sm mb-4 d-flex align-items-center">
        <i class="bi bi-exclamation-triangle-fill fs-4 text-warning me-3"></i> 
        <div>
            <strong>Peringatan Sistem!</strong><br>
            {{ $info_taguchi }}
        </div>
    </div>
    @endif

    <div class="alert alert-info border-0 shadow-sm mb-4">
        <h5 class="alert-heading fw-bold"><i class="bi bi-info-circle-fill me-2"></i>Informasi Respon Taguchi</h5>
        Sistem secara otomatis telah memproses Data Pemeriksaan QC untuk periode <strong>{{ $periodeText ?? 'terpilih' }}</strong>. Seluruh analisis Taguchi L9 di bawah ini menggunakan data <strong>Total Defect</strong> sebagai target optimasi (Smaller is Better).
    </div>

    <div class="text-end mb-3">
        <button class="btn btn-success" onclick="exportToExcel()">
            <i class="bi bi-file-earmark-excel me-1"></i> Export Excel
        </button>
    </div>

<div id="print-area">
    <div class="d-none d-print-block mb-4 text-center">
        <h3>Hasil Analisis Optimasi Taguchi L9</h3>
        <p>Data Periode: {{ $periodeText ?? 'Terpilih' }}</p>
        <hr>
    </div>

    <!-- 1. FAKTOR & LEVEL -->
    <div class="card border-0 shadow-sm mb-4 border-top border-primary border-3">
        <div class="card-header bg-white p-3">
            <h5 class="mb-0 fw-bold">1. Penentuan Faktor dan Level</h5>
        </div>
        <div class="table-responsive">
            <table class="table table-bordered table-taguchi mb-0">
                <thead>
                    <tr>
                        <th rowspan="2">Simbol</th>
                        <th rowspan="2">Faktor Pengaturan Mesin</th>
                        <th colspan="3">Level Parameter</th>
                    </tr>
                    <tr>
                        <th>Level 1</th>
                        <th>Level 2</th>
                        <th>Level 3</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="fw-bold">A</td>
                        <td class="text-start ps-4">Ukuran Jaring</td>
                        <td>{{ $levels['A'][0] ?? '-' }}</td>
                        <td>{{ $levels['A'][1] ?? '-' }}</td>
                        <td>{{ $levels['A'][2] ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="fw-bold">B</td>
                        <td class="text-start ps-4">MD Jaring</td>
                        <td>{{ $levels['B'][0] ?? '-' }}</td>
                        <td>{{ $levels['B'][1] ?? '-' }}</td>
                        <td>{{ $levels['B'][2] ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="fw-bold">C</td>
                        <td class="text-start ps-4">RPM Mesin</td>
                        <td>{{ $levels['C'][0] ?? '-' }}</td>
                        <td>{{ $levels['C'][1] ?? '-' }}</td>
                        <td>{{ $levels['C'][2] ?? '-' }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- 2. ORTHOGONAL ARRAY L9 -->
    <div class="card border-0 shadow-sm mb-4 border-top border-indigo border-3">
        <div class="card-header bg-white p-3">
            <h5 class="mb-0 fw-bold">2. Matriks Orthogonal Array L9 & S/N Ratio</h5>
        </div>
        <div class="table-responsive">
            <table class="table table-bordered table-taguchi mb-0 table-striped">
                <thead>
                    <tr>
                        <th rowspan="2">Eksperimen</th>
                        <th colspan="3">Kombinasi Faktor (Level)</th>
                        <th colspan="3">Nilai Aktual Parameter</th>
                        <th colspan="{{ $n_qc_per_exp }}">Data Defect (Y)</th>
                        <th rowspan="2">Mean (Y)</th>
                        <th rowspan="2">S/N Ratio (dB)</th>
                    </tr>
                    <tr>
                        <th>A</th><th>B</th><th>C</th>
                        <th>A</th><th>B</th><th>C</th>
                        @for($i = 1; $i <= $n_qc_per_exp; $i++)
                            <th>T{{ $i }}</th>
                        @endfor
                    </tr>
                </thead>
                <tbody>
                    @foreach($l9_matrix as $row)
                        <tr>
                            <td class="fw-bold">{{ $row['exp'] }}</td>
                            <td>{{ $row['A_lvl'] }}</td>
                            <td>{{ $row['B_lvl'] }}</td>
                            <td>{{ $row['C_lvl'] }}</td>
                            
                            <td class="text-secondary">{{ $row['A_val'] }}</td>
                            <td class="text-secondary">{{ $row['B_val'] }}</td>
                            <td class="text-secondary">{{ $row['C_val'] }}</td>
                            
                            @foreach($row['trials'] as $trial)
                                <td class="fw-semibold text-danger">{{ $trial }}</td>
                            @endforeach
                            @for($i = count($row['trials']); $i < $n_qc_per_exp; $i++)
                                <td>-</td>
                            @endfor
                            <td class="fw-bold text-success">{{ number_format($row['mean_y'], 3) }}</td>
                            <td class="fw-bold text-primary">{{ number_format($row['sn'], 3) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="row">
        <!-- 3. RESPONSE TABLE -->
        <div class="col-lg-6 mb-4">
            <div class="card border-0 shadow-sm h-100 border-top border-success border-3">
                <div class="card-header bg-white p-3">
                    <h5 class="mb-0 fw-bold">3. Response Table S/N Ratio</h5>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered table-taguchi mb-0">
                        <thead>
                            <tr>
                                <th>Level</th>
                                <th>Faktor A</th>
                                <th>Faktor B</th>
                                <th>Faktor C</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Level 1</td>
                                <td>{!! $optimum['A'] == 1 ? '<span class="optimum-badge">'.number_format($responseTable['A'][1], 3).'</span>' : number_format($responseTable['A'][1], 3) !!}</td>
                                <td>{!! $optimum['B'] == 1 ? '<span class="optimum-badge">'.number_format($responseTable['B'][1], 3).'</span>' : number_format($responseTable['B'][1], 3) !!}</td>
                                <td>{!! $optimum['C'] == 1 ? '<span class="optimum-badge">'.number_format($responseTable['C'][1], 3).'</span>' : number_format($responseTable['C'][1], 3) !!}</td>
                            </tr>
                            <tr>
                                <td>Level 2</td>
                                <td>{!! $optimum['A'] == 2 ? '<span class="optimum-badge">'.number_format($responseTable['A'][2], 3).'</span>' : number_format($responseTable['A'][2], 3) !!}</td>
                                <td>{!! $optimum['B'] == 2 ? '<span class="optimum-badge">'.number_format($responseTable['B'][2], 3).'</span>' : number_format($responseTable['B'][2], 3) !!}</td>
                                <td>{!! $optimum['C'] == 2 ? '<span class="optimum-badge">'.number_format($responseTable['C'][2], 3).'</span>' : number_format($responseTable['C'][2], 3) !!}</td>
                            </tr>
                            <tr>
                                <td>Level 3</td>
                                <td>{!! $optimum['A'] == 3 ? '<span class="optimum-badge">'.number_format($responseTable['A'][3], 3).'</span>' : number_format($responseTable['A'][3], 3) !!}</td>
                                <td>{!! $optimum['B'] == 3 ? '<span class="optimum-badge">'.number_format($responseTable['B'][3], 3).'</span>' : number_format($responseTable['B'][3], 3) !!}</td>
                                <td>{!! $optimum['C'] == 3 ? '<span class="optimum-badge">'.number_format($responseTable['C'][3], 3).'</span>' : number_format($responseTable['C'][3], 3) !!}</td>
                            </tr>
                            <tr class="table-light fw-bold">
                                <td>Delta</td>
                                <td>{{ number_format($deltas['A'], 3) }}</td>
                                <td>{{ number_format($deltas['B'], 3) }}</td>
                                <td>{{ number_format($deltas['C'], 3) }}</td>
                            </tr>
                            <tr class="table-light fw-bold text-danger">
                                <td>Rank</td>
                                <td>{{ $ranks['A'] }}</td>
                                <td>{{ $ranks['B'] }}</td>
                                <td>{{ $ranks['C'] }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="card-footer bg-white text-center p-3">
                    <p class="mb-0 fw-bold">Kondisi Optimum (Max S/N): <span class="text-success fs-5">A{{ $optimum['A'] }} - B{{ $optimum['B'] }} - C{{ $optimum['C'] }}</span></p>
                </div>
            </div>
        </div>

        <!-- 3B. RESPONSE TABLE MEAN -->
        <div class="col-lg-6 mb-4">
            <div class="card border-0 shadow-sm h-100 border-top border-info border-3">
                <div class="card-header bg-white p-3">
                    <h5 class="mb-0 fw-bold">3B. Response Table Mean</h5>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered table-taguchi mb-0">
                        <thead>
                            <tr>
                                <th>Level</th>
                                <th>Faktor A</th>
                                <th>Faktor B</th>
                                <th>Faktor C</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Level 1</td>
                                <td>{{ number_format($responseTableMean['A'][1], 3) }}</td>
                                <td>{{ number_format($responseTableMean['B'][1], 3) }}</td>
                                <td>{{ number_format($responseTableMean['C'][1], 3) }}</td>
                            </tr>
                            <tr>
                                <td>Level 2</td>
                                <td>{{ number_format($responseTableMean['A'][2], 3) }}</td>
                                <td>{{ number_format($responseTableMean['B'][2], 3) }}</td>
                                <td>{{ number_format($responseTableMean['C'][2], 3) }}</td>
                            </tr>
                            <tr>
                                <td>Level 3</td>
                                <td>{{ number_format($responseTableMean['A'][3], 3) }}</td>
                                <td>{{ number_format($responseTableMean['B'][3], 3) }}</td>
                                <td>{{ number_format($responseTableMean['C'][3], 3) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- 4. GRAFIK S/N -->
        <div class="col-lg-12 mb-4">
            <div class="card border-0 shadow-sm h-100 border-top border-warning border-3">
                <div class="card-header bg-white p-3">
                    <h5 class="mb-0 fw-bold">4. Main Effects Plot for S/N Ratios (MATLAB Style)</h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-md-4">
                            <h6 class="fw-bold text-secondary mb-3">Faktor A (Ukuran Jaring)</h6>
                            <div style="height: 300px;">
                                <canvas id="chartA"></canvas>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <h6 class="fw-bold text-secondary mb-3">Faktor B (MD Jaring)</h6>
                            <div style="height: 300px;">
                                <canvas id="chartB"></canvas>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <h6 class="fw-bold text-secondary mb-3">Faktor C (RPM Mesin)</h6>
                            <div style="height: 300px;">
                                <canvas id="chartC"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 5. ANOVA -->
    <div class="card border-0 shadow-sm mb-4 border-top border-danger border-3">
        <div class="card-header bg-white p-3">
            <h5 class="mb-0 fw-bold">5. Analisis Varian (ANOVA) S/N Ratio</h5>
        </div>
        <div class="table-responsive">
            <table class="table table-bordered table-taguchi mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="text-start ps-4">Faktor</th>
                        <th>DF</th>
                        <th>Adj SS</th>
                        <th>Adj MS</th>
                        <th>F-Value</th>
                        <th>P-Value</th>
                        <th>Contribution (%)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach(['A', 'B', 'C'] as $f)
                        <tr>
                            <td class="text-start ps-4 fw-bold">Faktor {{ $f }}</td>
                            <td>{{ $anova[$f]['df'] }}</td>
                            <td>{{ number_format($anova[$f]['ss'], 4) }}</td>
                            <td>{{ number_format($anova[$f]['ms'], 4) }}</td>
                            <td>{{ number_format($anova[$f]['f'], 2) }}</td>
                            <td>{{ is_numeric($anova[$f]['p']) ? number_format($anova[$f]['p'], 3) : $anova[$f]['p'] }}</td>
                            <td class="fw-bold text-primary">{{ number_format($anova[$f]['cont'], 2) }}%</td>
                        </tr>
                    @endforeach
                    <tr class="text-secondary">
                        <td class="text-start ps-4">Error</td>
                        <td>{{ $anova['Error']['df'] }}</td>
                        <td>{{ number_format($anova['Error']['ss'], 4) }}</td>
                        <td>{{ number_format($anova['Error']['ms'], 4) }}</td>
                        <td>-</td>
                        <td>-</td>
                        <td>{{ number_format($anova['Error']['cont'], 2) }}%</td>
                    </tr>
                    <tr class="fw-bold table-light text-dark">
                        <td class="text-start ps-4">Total</td>
                        <td>{{ $anova['Total']['df'] }}</td>
                        <td>{{ number_format($anova['Total']['ss'], 4) }}</td>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                        <td>100%</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

@push('scripts')
<script>
document.addEventListener("DOMContentLoaded", function() {
    // Data values for each factor
    const dataA = [{{ $responseTable['A'][1] }}, {{ $responseTable['A'][2] }}, {{ $responseTable['A'][3] }}];
    const dataB = [{{ $responseTable['B'][1] }}, {{ $responseTable['B'][2] }}, {{ $responseTable['B'][3] }}];
    const dataC = [{{ $responseTable['C'][1] }}, {{ $responseTable['C'][2] }}, {{ $responseTable['C'][3] }}];
    
    // Labels representing level values
    const labelsA = ['{{ $levels["A"][0] }}', '{{ $levels["A"][1] }}', '{{ $levels["A"][2] }}'];
    const labelsB = ['{{ $levels["B"][0] }}', '{{ $levels["B"][1] }}', '{{ $levels["B"][2] }}'];
    const labelsC = ['{{ $levels["C"][0] }}', '{{ $levels["C"][1] }}', '{{ $levels["C"][2] }}'];
    
    const grandMean = {{ $avg_total_sn ?? 0 }};
    
    // Calculate global min and max for uniform Y-axis across subplots
    const allData = [...dataA, ...dataB, ...dataC, grandMean];
    // Adding slight padding
    const yMin = Math.min(...allData) - 0.5; 
    const yMax = Math.max(...allData) + 0.5;
    
    const commonOptions = {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { display: false },
            tooltip: {
                callbacks: {
                    label: function(context) {
                        return context.raw.toFixed(3) + ' dB';
                    }
                }
            }
        },
        scales: {
            x: {
                grid: { display: true, color: '#e2e8f0' } // Light gray grid
            },
            y: {
                min: yMin,
                max: yMax,
                grid: { display: true, color: '#e2e8f0' },
                title: { display: true, text: 'Mean of S/N Ratio' }
            }
        }
    };
    
    function createChart(ctxId, data, color, labels) {
        return new Chart(document.getElementById(ctxId).getContext('2d'), {
            type: 'line',
            data: {
                labels: labels,
                datasets: [
                    {
                        // Main Line Data
                        data: data,
                        borderColor: color,
                        backgroundColor: color,
                        tension: 0, // 0 tension makes straight MATLAB-like lines
                        fill: false,
                        pointRadius: 6,
                        pointHoverRadius: 8,
                        pointStyle: 'circle'
                    },
                    {
                        // Grand Mean Line (simulated as another dataset)
                        data: [grandMean, grandMean, grandMean],
                        borderColor: '#94a3b8',
                        borderDash: [5, 5],
                        borderWidth: 2,
                        pointRadius: 0,
                        pointHoverRadius: 0,
                        tension: 0,
                        fill: false
                    }
                ]
            },
            options: commonOptions
        });
    }

    createChart('chartA', dataA, '#0d6efd', labelsA);
    createChart('chartB', dataB, '#198754', labelsB);
    createChart('chartC', dataC, '#dc3545', labelsC);
});
</script>

<script>
function exportToExcel() {
    let html = "<html xmlns:o='urn:schemas-microsoft-com:office:office' xmlns:x='urn:schemas-microsoft-com:office:excel' xmlns='http://www.w3.org/TR/REC-html40'><head><meta charset='utf-8'></head><body>";
    
    html += "<h2>Hasil Analisis Optimasi Taguchi L9</h2>";
    html += "<p>Data Periode: {{ $periodeText ?? 'Terpilih' }}</p><br>";

    // Get all tables in print area
    const tables = document.querySelectorAll('#print-area table');
    const tableTitles = [
        "1. Penentuan Faktor dan Level",
        "2. Orthogonal Array L9 & Respon (Total Defect)",
        "3. Response Table for Signal to Noise Ratios (Smaller is better)"
    ];

    tables.forEach((table, index) => {
        html += "<h3>" + (tableTitles[index] || "Tabel " + (index+1)) + "</h3>";
        html += table.outerHTML;
        html += "<br><br>";
    });
    html += "</body></html>";
    
    let blob = new Blob([html], { type: 'application/vnd.ms-excel' });
    let url = URL.createObjectURL(blob);
    let a = document.createElement('a');
    a.href = url;
    a.download = 'Analisis_Taguchi_L9.xls';
    document.body.appendChild(a);
    a.click();
    document.body.removeChild(a);
}
</script>
</div>
@endpush
@endif
@endsection
