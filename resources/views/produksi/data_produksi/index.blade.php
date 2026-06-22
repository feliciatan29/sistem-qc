@extends('produksi.layout')

@section('title', 'Data Produksi')

@section('header', 'Data Produksi')

@section('content')

<div class="container-fluid px-3 px-lg-4 py-4">

    <div class="page-heading">
        <div class="page-heading-copy">
            <span class="page-icon">
                <i class="bi bi-table"></i>
            </span>
            <div>
                <p class="eyebrow mb-1">Produksi</p>
                <h1 class="h3 mb-1">Data Produksi</h1>
                <p class="text-muted mb-0">
                    Data pesanan produksi jaring.
                </p>
            </div>
        </div>
    </div>

    <section class="panel">

        <div class="panel-header">
            <div>
                <h2 class="h5 mb-1 section-title">
                    <i class="bi bi-table"></i>
                    <span>Daftar Produksi</span>
                </h2>
            </div>

            <input
                class="form-control form-control-sm table-search"
                type="search"
                placeholder="Cari Data Produksi"
                data-table-search="produksiTable">
        </div>

        <div class="table-responsive">

            <table class="table align-middle mb-0"
                   id="produksiTable"
                   data-searchable-table>

                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Jenis Jaring</th>
                        <th>Bulan Produksi</th>
                        <th>Jumlah Pesanan</th>
                        <th>Status</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($produksi as $item)

                    <tr>
                        <td>{{ $item->id }}</td>

                        <td>
                            <span class="fw-semibold">
                                {{ $item->jenis_jaring }}
                            </span>
                        </td>

                        <td>{{ $item->bulan_produksi }}</td>

                        <td>{{ number_format($item->jumlah_pesanan) }}</td>

                        <td>
                            @if($item->status == 'Aktif')
                                <span class="badge text-bg-success">
                                    {{ $item->status }}
                                </span>

                            @elseif($item->status == 'Proses')
                                <span class="badge text-bg-warning">
                                    {{ $item->status }}
                                </span>

                            @else
                                <span class="badge text-bg-secondary">
                                    {{ $item->status }}
                                </span>
                            @endif
                        </td>

                    </tr>

                    @empty

                    <tr>
                        <td colspan="5" class="text-center">
                            Data Produksi Belum Tersedia
                        </td>
                    </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </section>

</div>

@endsection
