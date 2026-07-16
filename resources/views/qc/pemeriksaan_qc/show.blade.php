@extends('qc.layoutqc')

@push('styles')
    <style>
        .detail-card {
            border-radius: 12px;
            border: 1px solid #e2e8f0;
            background: #fff;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
            margin-bottom: 1.5rem;
        }
        .detail-header {
            background-color: #f8f9fa;
            border-bottom: 1px solid #e9ecef;
            padding: 1rem 1.5rem;
            border-radius: 12px 12px 0 0;
            font-weight: 700;
            color: #1e293b;
        }
        .detail-body {
            padding: 1.5rem;
        }
        .info-row {
            display: flex;
            margin-bottom: 0.75rem;
            border-bottom: 1px dashed #f1f5f9;
            padding-bottom: 0.5rem;
        }
        .info-label {
            width: 40%;
            font-weight: 600;
            color: #64748b;
        }
        .info-value {
            width: 60%;
            color: #0f172a;
            font-weight: 500;
        }
        .defect-box {
            text-align: center;
            padding: 1rem;
            border-radius: 8px;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
        }
        .defect-val {
            font-size: 1.5rem;
            font-weight: 700;
            color: #ef4444;
        }
        .defect-label {
            font-size: 0.8rem;
            font-weight: 600;
            color: #64748b;
            text-transform: uppercase;
        }
        .percentage-card {
            background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
            color: white;
            border-radius: 12px;
            padding: 2rem;
            text-align: center;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        .percentage-val {
            font-size: 3rem;
            font-weight: 800;
            margin: 1rem 0;
            color: #38bdf8;
        }
    </style>
@endpush

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
    <div class="page-heading mb-0">
        <div class="page-heading-copy">
            <span class="page-icon">
                <i class="bi bi-info-circle"></i>
            </span>
            <div>
                <p class="eyebrow mb-1">Quality Control</p>
                <h1 class="h3 mb-1">Detail Pemeriksaan QC</h1>
                <p class="text-muted mb-0">Rincian hasil pemeriksaan kualitas produksi jaring.</p>
            </div>
        </div>
    </div>
    <div class="ms-auto mt-3 mt-md-0">
        <a href="{{ route('qc.pemeriksaan.index') }}" class="btn btn-secondary shadow-sm">
            <i class="bi bi-arrow-left me-1"></i> Kembali
        </a>
        <a href="{{ route('qc.pemeriksaan.edit', $pemeriksaan->id) }}" class="btn btn-warning ms-2 shadow-sm">
            <i class="bi bi-pencil-square me-1"></i> Edit Data
        </a>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="detail-card">
            <div class="detail-header">
                <i class="bi bi-box-seam me-2 text-primary"></i> Informasi Produksi
            </div>
            <div class="detail-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="info-row">
                            <div class="info-label">ID Produksi</div>
                            <div class="info-value">#{{ $pemeriksaan->id_produksi }}</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Jenis Jaring</div>
                            <div class="info-value"><span class="badge bg-primary">{{ $pemeriksaan->jenis_jaring }}</span></div>
                        </div>
                        <div class="info-row border-0">
                            <div class="info-label">Bulan Produksi</div>
                            <div class="info-value">{{ $pemeriksaan->bulan_produksi ? \Carbon\Carbon::parse($pemeriksaan->bulan_produksi)->translatedFormat('F Y') : '-' }}</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-row">
                            <div class="info-label">Jumlah Pesanan</div>
                            <div class="info-value">{{ number_format((int)$pemeriksaan->jumlah_pesanan) }} PCS</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Jumlah Cek</div>
                            <div class="info-value">{{ number_format($pemeriksaan->jumlah_cek) }} PCS</div>
                        </div>
                        <div class="info-row border-0">
                            <div class="info-label">Jumlah Baik</div>
                            <div class="info-value text-success fw-bold">{{ number_format($pemeriksaan->baik) }} PCS</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="detail-card">
            <div class="detail-header">
                <i class="bi bi-exclamation-triangle me-2 text-danger"></i> Rincian Defect (Cacat)
            </div>
            <div class="detail-body">
                <div class="row g-3">
                    <div class="col-4 col-md-3">
                        <div class="defect-box shadow-sm">
                            <div class="defect-val">{{ $pemeriksaan->rr }}</div>
                            <div class="defect-label">Rusak Ringan</div>
                        </div>
                    </div>
                    <div class="col-4 col-md-3">
                        <div class="defect-box shadow-sm">
                            <div class="defect-val">{{ $pemeriksaan->pr }}</div>
                            <div class="defect-label">Parah Ringan</div>
                        </div>
                    </div>
                    <div class="col-4 col-md-3">
                        <div class="defect-box shadow-sm">
                            <div class="defect-val">{{ $pemeriksaan->rps }}</div>
                            <div class="defect-label">RPS</div>
                        </div>
                    </div>
                    <div class="col-4 col-md-3">
                        <div class="defect-box shadow-sm">
                            <div class="defect-val">{{ $pemeriksaan->super }}</div>
                            <div class="defect-label">SUPER</div>
                        </div>
                    </div>
                    <div class="col-4 col-md-4">
                        <div class="defect-box shadow-sm">
                            <div class="defect-val">{{ $pemeriksaan->rj }}</div>
                            <div class="defect-label">Rusak Jalur</div>
                        </div>
                    </div>
                    <div class="col-4 col-md-4">
                        <div class="defect-box shadow-sm">
                            <div class="defect-val">{{ $pemeriksaan->berbulu }}</div>
                            <div class="defect-label">Berbulu</div>
                        </div>
                    </div>
                    <div class="col-4 col-md-4">
                        <div class="defect-box shadow-sm">
                            <div class="defect-val">{{ $pemeriksaan->rusak_blok }}</div>
                            <div class="defect-label">Rusak Blok</div>
                        </div>
                    </div>
                </div>
                
                @if($pemeriksaan->keterangan)
                <div class="mt-4 p-3 bg-light rounded border">
                    <h6 class="fw-bold mb-2">Keterangan / Catatan:</h6>
                    <p class="mb-0 text-muted">{{ $pemeriksaan->keterangan }}</p>
                </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-lg-4 mb-4">
        <div class="percentage-card shadow">
            <h5 class="text-white-50 text-uppercase tracking-wider">Persentase Defect</h5>
            
            @php
                $percentage = $pemeriksaan->jumlah_cek > 0 
                    ? (($pemeriksaan->jumlah_cek - $pemeriksaan->baik) / $pemeriksaan->jumlah_cek) * 100 
                    : 0;
            @endphp
            
            <div class="percentage-val">{{ number_format($percentage, 2) }}%</div>
            
            <div class="mt-3 text-start bg-white bg-opacity-10 p-3 rounded">
                <div class="d-flex justify-content-between mb-2">
                    <span>Total Defect:</span>
                    <span class="fw-bold text-white">{{ $pemeriksaan->total_defect }} PCS</span>
                </div>
                <div class="d-flex justify-content-between">
                    <span>Total Cek:</span>
                    <span class="fw-bold text-white">{{ $pemeriksaan->jumlah_cek }} PCS</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
