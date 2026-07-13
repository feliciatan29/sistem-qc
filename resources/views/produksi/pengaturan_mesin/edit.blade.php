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
                    <p class="eyebrow mb-1">
                        Produksi
                    </p>

                    <h1 class="h3 mb-1">
                        Edit Data Pengaturan Mesin
                    </h1>

                    <p class="text-muted mb-0">
                        Ubah konfigurasi mesin produksi jaring.
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
                        <span>Form Edit Data Pengaturan Mesin</span>
                    </h2>

                    <p class="text-muted mb-0">
                        Perbarui konfigurasi mesin dengan mengubah informasi yang diperlukan.
                    </p>
                </div>
            </div>

            <form action="{{ route('pengaturan-mesin.update', ['pengaturan_mesin' => $pengaturan->id]) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="p-4">
                    <div class="row g-4">

                        {{-- === Identitas Mesin === --}}
                        <div class="col-12">
                            <div class="form-section-label">
                                <i class="bi bi-cpu me-1"></i> Identitas Mesin
                            </div>
                        </div>

                        {{-- Kode Mesin --}}
                        <div class="col-md-6">
                            <label class="form-label" for="kode_mesin">
                                Kode Mesin
                            </label>

                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-upc-scan"></i></span>
                                <input class="form-control @error('kode_mesin') is-invalid @enderror" id="kode_mesin"
                                    name="kode_mesin" type="text" placeholder="Masukkan kode mesin"
                                    value="{{ old('kode_mesin', $pengaturan->kode_mesin) }}" required>
                                @error('kode_mesin')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        {{-- Jenis Jaring --}}
                        <div class="col-md-6">
                            <label class="form-label" for="jenis_jaring">
                                Jenis Jaring
                            </label>

                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-grid-3x3"></i></span>
                                <input class="form-control @error('jenis_jaring') is-invalid @enderror" id="jenis_jaring"
                                    name="jenis_jaring" type="text" placeholder="Masukkan jenis jaring"
                                    value="{{ old('jenis_jaring', $pengaturan->jenis_jaring) }}" required>
                                @error('jenis_jaring')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        {{-- === Spesifikasi Jaring === --}}
                        <div class="col-12 mt-2">
                            <div class="form-section-label">
                                <i class="bi bi-rulers me-1"></i> Spesifikasi Jaring
                            </div>
                        </div>

                        {{-- Ukuran Jaring --}}
                        <div class="col-md-4">
                            <label class="form-label" for="ukuran_jaring">
                                Ukuran Jaring
                            </label>

                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-aspect-ratio"></i></span>
                                <input class="form-control @error('ukuran_jaring') is-invalid @enderror" id="ukuran_jaring"
                                    name="ukuran_jaring" type="text" placeholder="Masukkan ukuran jaring"
                                    value="{{ old('ukuran_jaring', $pengaturan->ukuran_jaring) }}" required>
                                @error('ukuran_jaring')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        {{-- MD Jaring --}}
                        <div class="col-md-4">
                            <label class="form-label" for="MD_jaring">
                                MD Jaring
                            </label>

                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-arrows-expand"></i></span>
                                <input class="form-control @error('MD_jaring') is-invalid @enderror" id="MD_jaring" name="MD_jaring"
                                    type="number" step="0.01" min="0" placeholder="Masukkan MD jaring"
                                    value="{{ old('MD_jaring', $pengaturan->MD_jaring) }}" required>
                                @error('MD_jaring')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        {{-- RPM Jaring --}}
                        <div class="col-md-4">
                            <label class="form-label" for="RPM_jaring">
                                RPM Jaring
                            </label>

                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-arrow-repeat"></i></span>
                                <input class="form-control @error('RPM_jaring') is-invalid @enderror" id="RPM_jaring"
                                    name="RPM_jaring" type="number" step="0.01" min="0" placeholder="Masukkan RPM jaring"
                                    value="{{ old('RPM_jaring', $pengaturan->RPM_jaring) }}" required>
                                <span class="input-group-text" style="border-radius: 0 0.5rem 0.5rem 0 !important;">RPM</span>
                                @error('RPM_jaring')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        {{-- === Status === --}}
                        <div class="col-12 mt-2">
                            <div class="form-section-label">
                                <i class="bi bi-toggle-on me-1"></i> Status
                            </div>
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

                                <option value="Aktif" {{ old('status', $pengaturan->status) === 'Aktif' ? 'selected' : '' }}>
                                    Aktif
                                </option>

                                <option value="Nonaktif"
                                    {{ old('status', $pengaturan->status) === 'Nonaktif' ? 'selected' : '' }}>
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
                    <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">
                        <a href="{{ route('pengaturan-mesin.index') }}" class="btn btn-outline-secondary rounded-3 px-4">
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
