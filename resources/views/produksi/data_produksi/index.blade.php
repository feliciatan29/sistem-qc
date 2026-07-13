@extends('produksi.layout')

@section('content')

    <div class="container-fluid px-3 px-lg-4 py-4">

        {{-- Alert Success --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show m-3" role="alert">

            <i class="bi bi-check-circle-fill me-2"></i>

                {{ session('success') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert">
                </button>

            </div>
        @endif

        {{-- Heading --}}
        <div class="page-heading">

            <div class="page-heading-copy">

                <span class="page-icon">
                    <i class="bi bi-table"></i>
                </span>

                <div>

                    <p class="eyebrow mb-1">
                        Produksi
                    </p>

                    <h1 class="h3 mb-1">
                        Data Produksi
                    </h1>

                    <p class="text-muted mb-0">
                        Data pesanan produksi jaring.
                    </p>

            </div>

        </div>

        </div>

        {{-- Panel --}}
        <section class="panel">

        {{-- Header --}}
        <div class="panel-header d-flex justify-content-between align-items-center flex-wrap gap-3">

                <div>

                    <h2 class="h5 mb-1 section-title">

                        <i class="bi bi-table"></i>

                        <span>Daftar Produksi</span>

                    </h2>

                </div>

            <div class="d-flex align-items-center flex-wrap gap-2">

                <input
                    class="form-control table-search"
                    style="width:260px;"
                    type="search"
                    placeholder="🔍 Cari Data Produksi..."
                        data-table-search="produksiTable">

                <a href="{{ route('produksi.create') }}"
                   class="btn btn-primary d-flex align-items-center shadow-sm">

                    <i class="bi bi-plus-circle me-2"></i>

                        Tambah Data

                    </a>

                </div>

            </div>

        {{-- Table --}}
            <div class="table-responsive">

            <table
                class="table align-middle mb-0"
                id="produksiTable"
                data-searchable-table>

                    <thead>

                        <tr>

                        <th width="60">
                            ID
                        </th>

                        <th>
                            Jenis Jaring
                        </th>

                        <th>
                            Bulan Produksi
                        </th>

                        <th>
                            Jumlah Pesanan
                        </th>

                        <th class="text-center" width="120">
                            Status
                        </th>

                        <th class="text-center" width="180">
                            Aksi
                        </th>

                        </tr>

                    </thead>

                    <tbody>

                        @forelse($produksi as $item)

                            <tr>

                                <td>

                                    {{ $item->id }}

                                </td>

                                <td>

                                    <span class="fw-semibold">

                                        {{ $item->jenis_jaring }}

                                    </span>

                                </td>

                                <td>

                                    {{ $item->bulan_produksi }}

                                </td>

                                <td>

                            {{ number_format($item->jumlah_pesanan) }}

                        </td>

                        <td class="text-center">

                            @if($item->status == 'Aktif')

                                <span class="badge bg-success px-3 py-2">

                                            {{ $item->status }}

                                        </span>

                                    @elseif($item->status == 'Proses')

                                <span class="badge bg-warning text-dark px-3 py-2">

                                            {{ $item->status }}

                                        </span>

                                    @else

                                <span class="badge bg-secondary px-3 py-2">

                                            {{ $item->status }}

                                        </span>

                                    @endif

                                </td>

                        <td class="text-center">

                            <div class="d-flex justify-content-center align-items-center gap-2">

                                <a
                                    href="{{ route('produksi.edit', $item->id) }}"
                                    class="btn btn-warning btn-sm d-flex justify-content-center align-items-center shadow-sm"
                                    title="Edit Data"
                                    style="width:38px; height:38px;">

                                            <i class="bi bi-pencil-square"></i>

                                        </a>

                                <form
                                    action="{{ route('produksi.destroy', $item->id) }}"
                                    method="POST"
                                    class="m-0 d-inline"
                                            onsubmit="return confirm('Yakin ingin menghapus data produksi ini?')">

                                            @csrf
                                            @method('DELETE')

                                    <button
                                        type="submit"
                                        class="btn btn-danger btn-sm d-flex justify-content-center align-items-center shadow-sm"
                                        title="Hapus Data"
                                        style="width:38px; height:38px;">

                                                <i class="bi bi-trash"></i>

                                            </button>

                                        </form>

                                    </div>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="6" class="text-center py-5">

                                    <span class="text-muted">

                                        Data Produksi Belum Tersedia

                                    </span>

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </section>

    </div>

@endsection
