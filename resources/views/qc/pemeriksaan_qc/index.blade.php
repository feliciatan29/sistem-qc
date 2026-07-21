@extends('qc.layoutqc')

@push('styles')
    <style>
        .table thead th {
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            color: #6c757d;
            background: #f8f9fa;
            border-bottom: 2px solid #e9ecef;
            padding: 12px 16px;
            white-space: nowrap;
        }
        .table tbody td {
            padding: 12px 16px;
            vertical-align: middle;
            border-bottom: 1px solid #f0f0f0;
            font-size: 0.9rem;
            color: #343a40;
        }
        .table tbody tr:hover {
            background-color: #f8fbff;
            transition: background-color 0.15s;
        }
        .badge {
            font-size: 0.75rem;
            padding: 5px 12px;
            border-radius: 50px;
            font-weight: 600;
        }
        .table-search {
            border-radius: 0.5rem !important;
            font-size: 0.875rem;
            height: 38px;
        }
        .jenis-jaring-badge {
            background: #eef2ff;
            color: #4338ca;
            padding: 3px 10px;
            border-radius: 6px;
            font-size: 0.8rem;
            font-weight: 600;
        }
        .jumlah-badge {
            background: #f0fdf4;
            color: #166534;
            padding: 3px 10px;
            border-radius: 6px;
            font-size: 0.8rem;
            font-weight: 600;
            font-family: 'Courier New', monospace;
        }
        .keterangan-card {
            border-radius: 12px;
            border: 1px solid #e2e8f0;
            background: #fff;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        }
        .keterangan-card h5 {
            font-size: 1rem;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 1rem;
            border-bottom: 1px solid #f1f5f9;
            padding-bottom: 0.5rem;
        }
        .keterangan-list li {
            font-size: 0.85rem;
            color: #475569;
            margin-bottom: 0.4rem;
        }
        .keterangan-list li strong {
            color: #0f172a;
            display: inline-block;
            width: 85px;
        }
    </style>
@endpush

@section('content')
<div class="page-heading mb-4">
    <div class="page-heading-copy">
        <span class="page-icon">
            <i class="bi bi-clipboard2-check"></i>
        </span>
        <div>
            <p class="eyebrow mb-1">Quality Control</p>
            <h1 class="h3 mb-1">Data Pemeriksaan QC</h1>
            <p class="text-muted mb-0">Manajemen data hasil pemeriksaan kualitas produksi jaring.</p>
        </div>
    </div>
</div>

<section class="panel mb-4">
    <div class="panel-header d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <h2 class="h5 mb-1 section-title">
                <i class="bi bi-table"></i>
                <span>Daftar Pemeriksaan</span>
            </h2>
        </div>
        <div class="d-flex align-items-center flex-wrap gap-2">
            <form action="{{ route('qc.pemeriksaan.index') }}" method="GET" class="d-flex gap-2 align-items-center">
                <select name="bulan" class="form-select table-search" style="width: 130px;">
                    <option value="">Semua Bulan</option>
                    @for($m = 1; $m <= 12; $m++)
                        <option value="{{ sprintf('%02d', $m) }}" {{ request('bulan') == sprintf('%02d', $m) ? 'selected' : '' }}>
                            {{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
                        </option>
                    @endfor
                </select>
                <select name="tahun" class="form-select table-search" style="width: 120px;">
                    <option value="">Semua Tahun</option>
                    @php $currentYear = date('Y'); @endphp
                    @for($y = $currentYear; $y >= $currentYear - 5; $y--)
                        <option value="{{ $y }}" {{ request('tahun') == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>
                <button type="submit" class="btn btn-secondary d-flex align-items-center shadow-sm" style="height: 38px;">
                    <i class="bi bi-filter"></i>
                </button>
            </form>
            <input class="form-control table-search" style="width:200px;" type="search" placeholder="🔍 Cari Data..." data-table-search="qcTable">
            <a href="{{ route('qc.pemeriksaan.create') }}" class="btn btn-primary d-flex align-items-center shadow-sm" style="height: 38px;">
                <i class="bi bi-plus-circle me-1"></i> Tambah
            </a>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show m-3" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="table-responsive">
        <table class="table align-middle mb-0" id="qcTable" data-searchable-table>
            <thead>
                <tr>
                    <th width="50">No</th>
                    <th>Bulan Produksi</th>
                    <th>Jenis Jaring</th>
                    <th class="text-center">Pesanan (PCS)</th>
                    <th class="text-center">Cek (PCS)</th>
                    <th class="text-center">Sisa (Tidak Dicek)</th>
                    <th class="text-center">Baik</th>
                    <th class="text-center">RR</th>
                    <th class="text-center">PR</th>
                    <th class="text-center">RPS</th>
                    <th class="text-center">SUPER</th>
                    <th class="text-center">RJ</th>
                    <th class="text-center">Berbulu</th>
                    <th class="text-center">Rusak Blok</th>
                    <th class="text-center">Total Defect</th>
                    <th class="text-center">% Reject</th>
                    <th class="text-center" width="160">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pemeriksaan as $index => $item)
                    <tr>
                        <td class="text-muted fw-semibold">{{ $pemeriksaan->firstItem() + $index }}</td>
                        <td class="fw-semibold text-dark">{{ $item->bulan_produksi ? \Carbon\Carbon::parse($item->bulan_produksi)->translatedFormat('F Y') : '-' }}</td>
                        <td><span class="jenis-jaring-badge">{{ $item->jenis_jaring }}</span></td>
                        <td class="text-center"><span class="jumlah-badge">{{ number_format((int)$item->jumlah_pesanan) }}</span></td>
                        <td class="text-center fw-bold">{{ number_format($item->jumlah_cek) }}</td>
                        <td class="text-center text-muted">{{ number_format((int)$item->jumlah_pesanan - $item->jumlah_cek) }}</td>
                        <td class="text-center text-success fw-bold">{{ $item->baik }}</td>
                        <td class="text-center">{{ $item->rr }}</td>
                        <td class="text-center">{{ $item->pr }}</td>
                        <td class="text-center">{{ $item->rps }}</td>
                        <td class="text-center">{{ $item->super }}</td>
                        <td class="text-center">{{ $item->rj }}</td>
                        <td class="text-center">{{ $item->berbulu }}</td>
                        <td class="text-center">{{ $item->rusak_blok }}</td>
                        <td class="text-center text-danger fw-bold">{{ $item->total_defect }}</td>
                        <td class="text-center text-danger fw-bold">{{ $item->persentase_reject == floor($item->persentase_reject) ? number_format($item->persentase_reject, 0) : number_format($item->persentase_reject, 2) }}%</td>
                        <td>
                            <div class="d-flex gap-2 justify-content-center align-items-center">
                                <a href="{{ route('qc.pemeriksaan.show', $item->id) }}" class="btn btn-info btn-sm text-white d-inline-flex align-items-center justify-content-center" style="width: 32px; height: 32px;" title="Detail">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('qc.pemeriksaan.edit', $item->id) }}" class="btn btn-warning btn-sm d-inline-flex align-items-center justify-content-center" style="width: 32px; height: 32px;" title="Edit">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                                <form action="{{ route('qc.pemeriksaan.destroy', $item->id) }}" method="POST" class="m-0 p-0" onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm d-inline-flex align-items-center justify-content-center" style="width: 32px; height: 32px;" title="Hapus">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="15" class="text-center py-5">
                            <div class="text-muted">
                                <i class="bi bi-inbox fs-2 d-block mb-2"></i>
                                Data Pemeriksaan QC Belum Tersedia
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr class="table-light fw-bold">
                    <td colspan="4" class="text-end">Total Keseluruhan:</td>
                    <td class="text-center">{{ number_format($totalCek) }}</td>
                    <td></td>
                    <td class="text-center text-success">{{ number_format($totalBaik) }}</td>
                    <td colspan="7"></td>
                    <td class="text-center text-danger">{{ number_format($totalDefect) }}</td>
                    <td colspan="2"></td>
                </tr>
            </tfoot>
        </table>
    </div>

    @if ($pemeriksaan->hasPages())
        <div class="d-flex justify-content-end px-3 py-3 border-top">
            {{ $pemeriksaan->links() }}
        </div>
    @endif
</section>

{{-- Keterangan Kategori Defect --}}
<div class="row">
    <div class="col-12 col-md-8 col-lg-6">
        <div class="keterangan-card p-4">
            <h5><i class="bi bi-info-circle me-2 text-primary"></i> Keterangan Kategori Defect</h5>
            <ul class="list-unstyled keterangan-list mb-0">
                <li><strong>RR</strong> : Rusak Ringan</li>
                <li><strong>PR</strong> : Parah Ringan</li>
                <li><strong>RPS</strong> : Rusak Parah Sekali</li>
                <li><strong>RJ</strong> : Rusak Jalur</li>
                <li><strong>SUPER</strong> : Ketebalan benang tidak sesuai kontrak</li>
                <li><strong>Berbulu</strong> : Serat halus penyusun jaring terurai</li>
                <li><strong>Rusak Blok</strong>: Mata jaring rusak</li>
            </ul>
        </div>
    </div>
    
    <div class="col-12 col-md-12 col-lg-6 mt-4 mt-lg-0">
        <div class="alert alert-info shadow-sm h-100" role="alert" style="border-radius: 12px; border-left: 5px solid #0ea5e9; background-color: #f0f9ff; color: #0369a1;">
            <div class="d-flex h-100 align-items-center">
                <div class="me-3">
                    <i class="bi bi-info-circle fs-1"></i>
                </div>
                <div>
                    <h5 class="alert-heading fw-bold mb-2 text-dark">Catatan Perhitungan Defect</h5>
                    <p class="mb-0 text-dark" style="font-size: 0.9rem; line-height: 1.6;">
                        <strong>Total Defect</strong> adalah jumlah temuan cacat pada pola jaring saat diperiksa, <strong>bukan</strong> jumlah keseluruhan jaring yang dibuang/ditolak. <br><br>
                        Karena satu lembar jaring bisa memiliki lebih dari satu titik cacat, maka angka ini menjumlahkan seluruh titik cacat yang ditemukan. Semakin banyak titik cacat yang ada, semakin tinggi pula nilai Total Defect-nya.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.querySelector('[data-table-search="qcTable"]');
        if (searchInput) {
            searchInput.addEventListener('keyup', function() {
                const searchTerm = this.value.toLowerCase();
                const tableRows = document.querySelectorAll('#qcTable tbody tr');
                
                tableRows.forEach(row => {
                    const text = row.textContent.toLowerCase();
                    row.style.display = text.includes(searchTerm) ? '' : 'none';
                });
            });
        }
    });
</script>
@endpush
