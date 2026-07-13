@extends('produksi.layout')

@push('styles')
    {{-- Select2 CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        /* ====================================================
           Custom Select2 - Match Bootstrap 5 style
        ==================================================== */
        .select2-container .select2-selection--single {
            height: 42px !important;
            border: 1px solid #dee2e6 !important;
            border-radius: 0.5rem !important;
            transition: border-color 0.2s, box-shadow 0.2s;
        }
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 40px !important;
            color: #212529 !important;
            padding-left: 14px !important;
            font-size: 0.9rem;
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 40px !important;
        }
        .select2-container--default.select2-container--focus .select2-selection--single {
            border-color: #0d6efd !important;
            box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.15) !important;
        }
        .select2-dropdown {
            border: 1px solid #dee2e6 !important;
            border-radius: 0.5rem !important;
            box-shadow: 0 0.5rem 1.5rem rgba(0, 0, 0, 0.12) !important;
            font-size: 0.9rem;
        }
        .select2-container--default .select2-results__option--highlighted[aria-selected] {
            background-color: #0d6efd !important;
        }
        .select2-search--dropdown .select2-search__field {
            border-radius: 0.375rem !important;
            border: 1px solid #dee2e6 !important;
            padding: 6px 10px !important;
        }

        /* ====================================================
           Form enhancements
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

        /* ====================================================
           Section divider label
        ==================================================== */
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
                    <h1 class="h3 mb-1 fw-bold">Tambah Pengaturan Mesin</h1>
                    <p class="text-muted mb-0 small">Tambahkan parameter operasional untuk run produksi jaring.</p>
                </div>
            </div>
        </div>

        {{-- Panel Form --}}
        <section class="panel border-0 shadow-sm bg-white rounded-4">

            <div class="panel-header p-4 border-bottom d-flex align-items-center gap-2">
                <i class="bi bi-ui-checks-grid text-primary fs-5"></i>
                <div>
                    <h2 class="h5 mb-0 fw-bold text-dark">Form Tambah Pengaturan Mesin</h2>
                    <p class="text-muted mb-0 small">Pilih data produksi dan isi parameter operasional mesin.</p>
                </div>
            </div>

            <div class="p-4">
                <form action="{{ route('pengaturan-mesin.store') }}" method="POST" class="needs-validation" novalidate>
                    @csrf

                    <div class="row g-4">

                        {{-- === Bagian: Referensi Produksi === --}}
                        <div class="col-12">
                            <div class="form-section-label">
                                <i class="bi bi-link-45deg me-1"></i> Referensi Data Produksi
                            </div>
                        </div>

                        {{-- Data Produksi Select2 --}}
                        <div class="col-md-12">
                            <label class="form-label" for="data_produksi_id">
                                Hubungkan ke Run Data Produksi
                            </label>
                            <select class="form-select select2 @error('data_produksi_id') is-invalid @enderror"
                                    id="data_produksi_id" name="data_produksi_id" required style="width: 100%;">
                                <option value=""></option>
                                @foreach($dataproduksi as $dp)
                                    <option value="{{ $dp->id }}" {{ old('data_produksi_id') == $dp->id ? 'selected' : '' }}>
                                        {{ \Carbon\Carbon::parse($dp->bulan_produksi)->translatedFormat('F Y') }}
                                        | {{ $dp->jenis_jaring }}
                                        | {{ $dp->jumlah_pesanan }} pcs
                                        | {{ ucfirst($dp->status) }}
                                    </option>
                                @endforeach
                            </select>
                            @error('data_produksi_id')
                                <div class="invalid-feedback d-block">
                                    {{ $message }}
                                </div>
                            @enderror
                            <div class="form-text text-muted small mt-1">
                                <i class="bi bi-info-circle me-1"></i>
                                Format: Bulan Produksi | Jenis Jaring | Jumlah Pesanan | Status
                            </div>
                        </div>

                        {{-- === Bagian: Parameter Operasional === --}}
                        <div class="col-12 mt-2">
                            <div class="form-section-label">
                                <i class="bi bi-sliders me-1"></i> Parameter Operasional Mesin
                            </div>
                        </div>

                        {{-- Suhu Mesin --}}
                        <div class="col-md-4">
                            <label class="form-label" for="suhu_mesin">
                                Suhu Mesin
                            </label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-thermometer-half"></i></span>
                                <input class="form-control @error('suhu_mesin') is-invalid @enderror" id="suhu_mesin"
                                    name="suhu_mesin" type="number" step="0.1" min="0" placeholder="Contoh: 210.5"
                                    value="{{ old('suhu_mesin') }}" required>
                                <span class="input-group-text" style="border-radius: 0 0.5rem 0.5rem 0 !important;">°C</span>
                                @error('suhu_mesin')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- Kecepatan Mesin --}}
                        <div class="col-md-4">
                            <label class="form-label" for="kecepatan_mesin">
                                Kecepatan Mesin
                            </label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-speedometer2"></i></span>
                                <input class="form-control @error('kecepatan_mesin') is-invalid @enderror" id="kecepatan_mesin"
                                    name="kecepatan_mesin" type="number" step="0.1" min="0" placeholder="Contoh: 45.0"
                                    value="{{ old('kecepatan_mesin') }}" required>
                                <span class="input-group-text" style="border-radius: 0 0.5rem 0.5rem 0 !important;">m/min</span>
                                @error('kecepatan_mesin')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- Tekanan Mesin --}}
                        <div class="col-md-4">
                            <label class="form-label" for="tekanan_mesin">
                                Tekanan Mesin
                            </label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-arrow-down-circle"></i></span>
                                <input class="form-control @error('tekanan_mesin') is-invalid @enderror" id="tekanan_mesin"
                                    name="tekanan_mesin" type="number" step="0.1" min="0" placeholder="Contoh: 12.0"
                                    value="{{ old('tekanan_mesin') }}" required>
                                <span class="input-group-text" style="border-radius: 0 0.5rem 0.5rem 0 !important;">bar</span>
                                @error('tekanan_mesin')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- RPM Mesin --}}
                        <div class="col-md-4">
                            <label class="form-label" for="rpm_mesin">
                                RPM Mesin
                            </label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-arrow-repeat"></i></span>
                                <input class="form-control @error('rpm_mesin') is-invalid @enderror" id="rpm_mesin"
                                    name="rpm_mesin" type="number" step="1" min="0" placeholder="Contoh: 1500"
                                    value="{{ old('rpm_mesin') }}" required>
                                <span class="input-group-text" style="border-radius: 0 0.5rem 0.5rem 0 !important;">RPM</span>
                                @error('rpm_mesin')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- Waktu Operasi --}}
                        <div class="col-md-4">
                            <label class="form-label" for="waktu_operasi">
                                Waktu Operasi
                            </label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-clock"></i></span>
                                <input class="form-control @error('waktu_operasi') is-invalid @enderror" id="waktu_operasi"
                                    name="waktu_operasi" type="number" step="0.1" min="0" placeholder="Contoh: 480"
                                    value="{{ old('waktu_operasi') }}" required>
                                <span class="input-group-text" style="border-radius: 0 0.5rem 0.5rem 0 !important;">Menit</span>
                                @error('waktu_operasi')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- Status --}}
                        <div class="col-md-4">
                            <label class="form-label" for="status">
                                Status Parameter
                            </label>
                            <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                                <option value="Aktif" {{ old('status', 'Aktif') === 'Aktif' ? 'selected' : '' }}>Aktif</option>
                                <option value="Nonaktif" {{ old('status') === 'Nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- === Bagian: Catatan === --}}
                        <div class="col-12 mt-2">
                            <div class="form-section-label">
                                <i class="bi bi-journal-text me-1"></i> Catatan
                            </div>
                        </div>

                        {{-- Keterangan --}}
                        <div class="col-md-12">
                            <label class="form-label" for="keterangan">
                                Keterangan / Catatan Operasi
                            </label>
                            <textarea class="form-control @error('keterangan') is-invalid @enderror" id="keterangan"
                                name="keterangan" rows="3"
                                placeholder="Tambahkan catatan khusus mesin atau kestabilan parameter selama operasi...">{{ old('keterangan') }}</textarea>
                            @error('keterangan')
                                <div class="invalid-feedback">{{ $message }}</div>
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
                            Simpan Data
                        </button>
                    </div>

                </form>
            </div>

        </section>

    </div>
@endsection

@push('scripts')
    {{-- jQuery & Select2 JS --}}
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.select2').select2({
                placeholder: "Pilih Run Data Produksi...",
                allowClear: true,
                language: {
                    noResults: function() { return "Data tidak ditemukan"; },
                    searching: function() { return "Mencari..."; }
                }
            });
        });

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
