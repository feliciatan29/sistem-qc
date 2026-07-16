@extends('produksi.layout')

@push('styles')
    <style>
        /* ====================================================
                   Index - Visual Enhancements (no structural changes)
                ==================================================== */
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

        .table tbody tr:last-child td {
            border-bottom: none;
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

        .btn-sm {
            border-radius: 8px !important;
            padding: 5px 10px;
            font-size: 0.8rem;
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
            letter-spacing: 0.02em;
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

        .bulan-text {
            font-weight: 600;
            color: #1e293b;
        }

        .bulan-subtext {
            font-size: 0.75rem;
            color: #94a3b8;
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid px-3 px-lg-4 py-4">

        {{-- Heading --}}
        <div class="page-heading">
            <div class="page-heading-copy">
                <span class="page-icon">
                    <i class="bi bi-table"></i>
                </span>
                <div>
                    <p class="eyebrow mb-1">Produksi</p>
                    <h1 class="h3 mb-1">Data Produksi</h1>
                    <p class="text-muted mb-0">Data pesanan produksi jaring.</p>
                </div>
            </div>
        </div>

        {{-- Panel --}}
        <section class="panel">

            {{-- Header Panel --}}
            <div class="panel-header d-flex justify-content-between align-items-center flex-wrap gap-3">

                <div>
                    <h2 class="h5 mb-1 section-title">
                        <i class="bi bi-table"></i>
                        <span>Daftar Data Produksi</span>
                    </h2>
                </div>

                <div class="d-flex align-items-center flex-wrap gap-2">
                    <input class="form-control table-search" style="width:260px;" type="search"
                        placeholder="🔍 Cari Data Produksi..." data-table-search="produksiTable">

                    <a href="{{ route('produksi.create') }}" class="btn btn-primary d-flex align-items-center shadow-sm">
                        <i class="bi bi-plus-circle me-2"></i>
                        Tambah Data
                    </a>
                </div>

            </div>

            {{-- Alert --}}
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show m-3" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            {{-- Table --}}
            <div class="table-responsive">
                <table class="table align-middle mb-0" id="produksiTable" data-searchable-table>

                    <thead>
                        <tr>
                            <th width="60">#</th>
                            <th>Jenis Jaring</th>
                            <th>Bulan Produksi</th>
                            <th>Jumlah Pesanan</th>
                            <th class="text-center" width="120">Status</th>
                            <th class="text-center" width="160">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($produksi as $item)
                            <tr>

                                <td class="text-muted fw-semibold" style="font-size:0.8rem;">
                                    {{ $item->id }}
                                </td>

                                <td>
                                    <span class="jenis-jaring-badge">
                                        {{ $item->jenis_jaring }}
                                    </span>
                                </td>

                                <td>
                                    <span class="bulan-text">
                                        {{ \Carbon\Carbon::parse($item->bulan_produksi)->translatedFormat('F Y') }}
                                    </span>
                                </td>

                                <td>
                                    <span class="jumlah-badge">
                                        {{ number_format($item->jumlah_pesanan) }} pcs
                                    </span>
                                </td>

                                <td class="text-center">
                                    @if ($item->status == 'Aktif')
                                        <span class="badge bg-success">
                                            <i class="bi bi-check-circle-fill me-1"></i>Aktif
                                        </span>
                                    @elseif($item->status == 'Proses')
                                        <span class="badge bg-warning text-dark">
                                            <i class="bi bi-arrow-repeat me-1"></i>Proses
                                        </span>
                                    @else
                                        <span class="badge bg-danger">
                                            <i class="bi bi-x-circle-fill me-1"></i>Nonaktif
                                        </span>
                                    @endif
                                </td>

                                <td>
                                    <div class="d-flex gap-2 justify-content-center align-items-center">
                                        <a href="{{ route('produksi.edit', $item->id) }}" class="btn btn-warning btn-sm d-inline-flex align-items-center justify-content-center" style="width: 32px; height: 32px;"
                                            title="Edit">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>

                                        <form action="{{ route('produksi.destroy', $item->id) }}" method="POST" class="m-0 p-0"
                                            onsubmit="return confirm('Yakin ingin menghapus data produksi ini?')">
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
                                <td colspan="6" class="text-center py-5">
                                    <div class="text-muted">
                                        <i class="bi bi-inbox fs-2 d-block mb-2"></i>
                                        Data Produksi Belum Tersedia
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>

                </table>
            </div>

            {{-- Pagination --}}
            @if ($produksi->hasPages())
                <div class="d-flex justify-content-end px-3 py-3 border-top">
                    {{ $produksi->links() }}
                </div>
            @endif

        </section>

    </div>
@endsection
