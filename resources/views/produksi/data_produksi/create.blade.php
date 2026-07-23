@extends('produksi.layout')

@push('styles')
    <style>
        /* ====================================================
           Create - Visual Enhancements (no structural changes)
        ==================================================== */
        .form-control, .form-select {
            border-radius: 0.5rem !important;
            height: 42px;
            font-size: 0.9rem;
            transition: border-color 0.2s, box-shadow 0.2s;
        }
        textarea.form-control {
            height: auto !important;
        }
        .form-control:focus, .form-select:focus {
            box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.15) !important;
        }
        .form-label {
            font-size: 0.82rem;
            letter-spacing: 0.04em;
            text-transform: uppercase;
            color: #6c757d;
            margin-bottom: 6px;
        }
        .input-group-text {
            border-radius: 0.5rem 0 0 0.5rem !important;
            background: #f8f9fa;
            border-color: #dee2e6;
            color: #6c757d;
        }
        .form-section-label {
            font-size: 0.7rem;
            font-weight: 700;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            color: #0d6efd;
            border-bottom: 2px solid #e9ecef;
            padding-bottom: 6px;
            margin-bottom: 4px;
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid px-3 px-lg-4 py-4">

        {{-- Heading --}}
        <div class="page-heading mb-4">
            <div class="page-heading-copy d-flex align-items-center gap-3">
                <span class="page-icon bg-primary bg-opacity-10 text-primary p-3 rounded-3">
                    <i class="bi bi-plus-circle fs-3"></i>
                </span>
                <div>
                    <p class="eyebrow mb-1">Produksi</p>
                    <h1 class="h3 mb-1 fw-bold">Tambah Data Produksi</h1>
                    <p class="text-muted mb-0 small">Tambahkan data pesanan produksi jaring baru.</p>
                </div>
            </div>
        </div>

        {{-- Panel Form --}}
        <section class="panel border-0 shadow-sm bg-white rounded-4">

            <div class="panel-header p-4 border-bottom d-flex align-items-center gap-2">
                <i class="bi bi-ui-checks-grid text-primary fs-5"></i>
                <div>
                    <h2 class="h5 mb-0 fw-bold text-dark">Form Tambah Data Produksi</h2>
                    <p class="text-muted mb-0 small">Isi semua field yang diperlukan untuk menambahkan data produksi baru.</p>
                </div>
            </div>

            <div class="p-4">
                <form action="{{ route('produksi.store') }}" method="POST" class="needs-validation" novalidate>
                    @csrf

                    <div class="row g-4">

                        {{-- === Bagian: Informasi Produksi === --}}
                        <div class="col-12">
                            <div class="form-section-label">
                                <i class="bi bi-info-circle me-1"></i> Informasi Produksi
                            </div>
                        </div>

                        {{-- Jenis Jaring --}}
                        <div class="col-md-6">
                            <label class="form-label" for="jenis_jaring">
                                Jenis Jaring
                            </label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-grid-3x3-gap"></i></span>
                                <input class="form-control @error('jenis_jaring') is-invalid @enderror"
                                    id="jenis_jaring" name="jenis_jaring" type="text"
                                    placeholder="Masukkan jenis jaring"
                                    value="{{ old('jenis_jaring') }}" required>
                                @error('jenis_jaring')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- Bulan Produksi --}}
                        <div class="col-md-6">
                            <label class="form-label" for="bulan_produksi">
                                Bulan Produksi
                            </label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-calendar-month"></i></span>
                                <input class="form-control @error('bulan_produksi') is-invalid @enderror"
                                    id="bulan_produksi" name="bulan_produksi" type="month"
                                    value="{{ old('bulan_produksi') }}" required>
                                @error('bulan_produksi')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-text text-muted small mt-1">
                                <i class="bi bi-info-circle me-1"></i>
                                Pilih bulan dan tahun produksi
                            </div>
                        </div>

                        {{-- === Bagian: Detail Pesanan === --}}
                        <div class="col-12 mt-2">
                            <div class="form-section-label">
                                <i class="bi bi-box-seam me-1"></i> Detail Pesanan
                            </div>
                        </div>

                        {{-- Jumlah Pesanan --}}
                        <div class="col-md-6">
                            <label class="form-label" for="jumlah_pesanan">
                                Jumlah Pesanan
                            </label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-123"></i></span>
                                <input class="form-control @error('jumlah_pesanan') is-invalid @enderror"
                                    id="jumlah_pesanan" name="jumlah_pesanan" type="number" min="1"
                                    placeholder="Contoh: 5000"
                                    value="{{ old('jumlah_pesanan') }}" required>
                                <span class="input-group-text" style="border-radius: 0 0.5rem 0.5rem 0 !important;">pcs</span>
                                @error('jumlah_pesanan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- Status --}}
                        <div class="col-md-6">
                            <label class="form-label" for="status">
                                Status Produksi
                            </label>
                            <select class="form-select @error('status') is-invalid @enderror"
                                id="status" name="status" required>
                                <option value="">Pilih status...</option>
                                <option value="Aktif" {{ old('status') === 'Aktif' ? 'selected' : '' }}>
                                    Aktif
                                </option>
                                <option value="Proses" {{ old('status') === 'Proses' ? 'selected' : '' }}>
                                    Proses
                                </option>
                                <option value="Data Selesai" {{ old('status') === 'Data Selesai' ? 'selected' : '' }}>
                                    Data Selesai
                                </option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                    </div>

                    {{-- Tombol --}}
                    <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">
                        <a href="{{ route('produksi.index') }}" class="btn btn-outline-secondary rounded-3 px-4">
                            <i class="bi bi-x-circle me-1"></i>
                            Batal
                        </a>
                        <button type="submit" class="btn btn-primary rounded-3 px-4">
                            <i class="bi bi-check-circle me-1"></i>
                            Simpan Data
                        </button>
                    </div>

                </form>
            </div>

        </section>

    </div>
@endsection

@push('scripts')
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
@endpush
