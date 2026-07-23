@extends('produksi.layout')

@push('styles')
    <style>
        /* ====================================================
           Edit - Visual Enhancements (no structural changes)
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
        <div class="page-heading">
            <div class="page-heading-copy">
                <span class="page-icon">
                    <i class="bi bi-pencil-square"></i>
                </span>
                <div>
                    <p class="eyebrow mb-1">Produksi</p>
                    <h1 class="h3 mb-1">Edit Data Produksi</h1>
                    <p class="text-muted mb-0">Ubah data pesanan produksi jaring.</p>
                </div>
            </div>
        </div>

        {{-- Panel Form --}}
        <section class="panel">

            <div class="panel-header">
                <div>
                    <h2 class="h5 mb-1 section-title">
                        <i class="bi bi-ui-checks-grid"></i>
                        <span>Form Edit Data Produksi</span>
                    </h2>
                    <p class="text-muted mb-0">
                        Perbarui data produksi dengan mengubah informasi yang diperlukan.
                    </p>
                </div>
            </div>

            <form action="{{ route('produksi.update', $produksi->id) }}" method="POST" class="needs-validation" novalidate>
                @csrf
                @method('PUT')

                <div class="p-4">
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
                                    value="{{ old('jenis_jaring', $produksi->jenis_jaring) }}" required>
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
                                    value="{{ old('bulan_produksi', \Carbon\Carbon::parse($produksi->bulan_produksi)->format('Y-m')) }}" required>
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
                                    value="{{ old('jumlah_pesanan', $produksi->jumlah_pesanan) }}" required>
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
                                <option value="Aktif" {{ old('status', $produksi->status) === 'Aktif' ? 'selected' : '' }}>
                                    Aktif
                                </option>
                                <option value="Proses" {{ old('status', $produksi->status) === 'Proses' ? 'selected' : '' }}>
                                    Proses
                                </option>
                                <option value="Data Selesai" {{ old('status', $produksi->status) === 'Data Selesai' ? 'selected' : '' }}>
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
                            Update Data
                        </button>
                    </div>
                </div>

            </form>

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
