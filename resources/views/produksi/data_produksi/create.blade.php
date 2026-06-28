@extends('produksi.layout')

@section('content')
    <div class="container-fluid px-3 px-lg-4 py-4">

        {{-- Heading --}}
        <div class="page-heading">
            <div class="page-heading-copy">
                <span class="page-icon">
                    <i class="bi bi-plus-circle"></i>
                </span>

                <div>
                    <p class="eyebrow mb-1">
                        Produksi
                    </p>

                    <h1 class="h3 mb-1">
                        Tambah Data Produksi
                    </h1>

                    <p class="text-muted mb-0">
                        Tambahkan data pesanan produksi jaring baru.
                    </p>
                </div>
            </div>
        </div>

        {{-- Panel Form --}}
        <section class="panel">

            <div class="panel-header">
                <div>
                    <h2 class="h5 mb-1 section-title">
                        <i class="bi bi-ui-checks-grid"></i>
                        <span>Form Tambah Data Produksi</span>
                    </h2>

                    <p class="text-muted mb-0">
                        Isi semua field yang diperlukan untuk menambahkan data produksi baru.
                    </p>
                </div>
            </div>

            <form action="{{ route('produksi.store') }}" method="POST" class="needs-validation" novalidate>
                @csrf

                <div class="row g-3">

                    {{-- Jenis Jaring --}}
                    <div class="col-md-6">
                        <label class="form-label" for="jenis_jaring">
                            Jenis Jaring
                        </label>

                        <input class="form-control @error('jenis_jaring') is-invalid @enderror" id="jenis_jaring"
                            name="jenis_jaring" type="text" placeholder="Masukkan jenis jaring"
                            value="{{ old('jenis_jaring') }}" required>

                        @error('jenis_jaring')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    {{-- Bulan Produksi --}}
                    <div class="col-md-6">
                        <label class="form-label" for="bulan_produksi">
                            Bulan Produksi
                        </label>

                        <input class="form-control @error('bulan_produksi') is-invalid @enderror" id="bulan_produksi"
                            name="bulan_produksi" type="month" value="{{ old('bulan_produksi') }}" required>

                        @error('bulan_produksi')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    {{-- Jumlah Pesanan --}}
                    <div class="col-md-6">
                        <label class="form-label" for="jumlah_pesanan">
                            Jumlah Pesanan
                        </label>

                        <input class="form-control @error('jumlah_pesanan') is-invalid @enderror" id="jumlah_pesanan"
                            name="jumlah_pesanan" type="number" min="1" placeholder="Masukkan jumlah pesanan"
                            value="{{ old('jumlah_pesanan') }}" required>

                        @error('jumlah_pesanan')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    {{-- Status --}}
                    <div class="col-md-6">
                        <label class="form-label" for="status">
                            Status
                        </label>

                        <select class="form-select @error('status') is-invalid @enderror" id="status" name="status"
                            required>
                            <option value="">
                                Pilih status
                            </option>

                            <option value="Aktif" {{ old('status') === 'Aktif' ? 'selected' : '' }}>
                                Aktif
                            </option>

                            <option value="Proses" {{ old('status') === 'Proses' ? 'selected' : '' }}>
                                Proses
                            </option>

                            <option value="Nonaktif" {{ old('status') === 'Nonaktif' ? 'selected' : '' }}>
                                Nonaktif
                            </option>
                        </select>

                        @error('status')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                </div>

                {{-- Tombol --}}
                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a href="{{ route('produksi.index') }}" class="btn btn-secondary">
                        <i class="bi bi-x-circle me-1"></i>
                        Batal
                    </a>

                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle me-1"></i>
                        Simpan
                    </button>
                </div>

            </form>

        </section>

    </div>

    <script>
        // Bootstrap validation script
        (function() {
            'use strict'
            window.addEventListener('load', function() {
                var forms = document.querySelectorAll('.needs-validation')
                Array.prototype.slice.call(forms).forEach(function(form) {
                    form.addEventListener('submit', function(event) {
                        if (!form.checkValidity()) {
                            event.preventDefault()
                            event.stopPropagation()
                        }
                        form.classList.add('was-validated')
                    }, false)
                })
            }, false)
        }())
    </script>
@endsection
