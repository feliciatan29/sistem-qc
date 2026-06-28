@extends('produksi.layout')

@section('content')

<div class="container-fluid px-3 px-lg-4 py-4">

    <div class="page-heading">
        <div class="page-heading-copy">
            <span class="page-icon">
                <i class="bi bi-gear-fill"></i>
            </span>
            <div>
                <p class="eyebrow mb-1">Produksi</p>
                <h1 class="h3 mb-1">Pengaturan Mesin</h1>
                <p class="text-muted mb-0">
                    Data konfigurasi mesin produksi jaring.
                </p>
            </div>
        </div>
    </div>

    <section class="panel">

        <div class="panel-header d-flex justify-content-between align-items-center flex-wrap gap-2">

            <div>
                <h2 class="h5 mb-1 section-title">
                    <i class="bi bi-sliders"></i>
                    <span>Daftar Pengaturan Mesin</span>
                </h2>
            </div>

            <div class="d-flex gap-2">

                <input
                    class="form-control form-control-sm table-search"
                    type="search"
                    placeholder="Cari Pengaturan Mesin"
                    data-table-search="pengaturanTable">

                <a href="{{ route('pengaturan-mesin.create') }}"
                   class="btn btn-primary btn-sm">
                    <i class="bi bi-plus-circle"></i>
                    Tambah Data
                </a>

            </div>

        </div>

        @if(session('success'))
            <div class="alert alert-success m-3">
                {{ session('success') }}
            </div>
        @endif

        <div class="table-responsive">

            <table class="table align-middle mb-0"
                   id="pengaturanTable"
                   data-searchable-table>

                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Kode Mesin</th>
                        <th>Jenis Jaring</th>
                        <th>Ukuran Jaring</th>
                        <th>MD Jaring</th>
                        <th>RPM Jaring</th>
                        <th>Status</th>
                        <th width="150">Aksi</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($pengaturan as $item)

                    <tr>

                        <td>{{ $item->id }}</td>

                        <td>
                            <span class="fw-semibold">
                                {{ $item->kode_mesin }}
                            </span>
                        </td>

                        <td>{{ $item->jenis_jaring }}</td>

                        <td>{{ $item->ukuran_jaring }}</td>

                        <td>{{ $item->MD_jaring }}</td>

                        <td>{{ $item->RPM_jaring }}</td>

                        <td>

                            @if($item->status == 'Aktif')

                                <span class="badge bg-success">
                                    {{ $item->status }}
                                </span>

                            @elseif($item->status == 'Nonaktif')

                                <span class="badge bg-danger">
                                    {{ $item->status }}
                                </span>

                            @else

                                <span class="badge bg-secondary">
                                    {{ $item->status }}
                                </span>

                            @endif

                        </td>

                        <td>

                            <div class="d-flex gap-1">

                                <a href="{{ route('pengaturan-mesin.edit',$item->id) }}"
                                   class="btn btn-warning btn-sm">
                                    <i class="bi bi-pencil-square"></i>
                                </a>

                                <form action="{{ route('pengaturan-mesin.destroy',$item->id) }}"
                                      method="POST"
                                      onsubmit="return confirm('Yakin ingin menghapus data ini?')">

                                    @csrf
                                    @method('DELETE')

                                    <button type="submit"
                                            class="btn btn-danger btn-sm">
                                        <i class="bi bi-trash"></i>
                                    </button>

                                </form>

                            </div>

                        </td>

                    </tr>

                    @empty

                    <tr>
                        <td colspan="8" class="text-center py-4">
                            Data Pengaturan Mesin Belum Tersedia
                        </td>
                    </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </section>

</div>

@endsection
