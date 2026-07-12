@extends('produksi.layout')

@push('styles')
    {{-- Select2 CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        /* Custom Select2 styling to match Bootstrap 5 */
        .select2-container .select2-selection--single {
            height: 38px !important;
            border: 1px solid #dee2e6 !important;
            border-radius: 0.375rem !important;
        }
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 36px !important;
            color: #212529 !important;
            padding-left: 12px !important;
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 36px !important;
        }
        .select2-dropdown {
            border: 1px solid #dee2e6 !important;
            border-radius: 0.375rem !important;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
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
                    <h1 class="h3 mb-1 fw-bold">Tambah Pengaturan Mesin</h1>
                    <p class="text-muted mb-0">Tambahkan parameter operasional untuk run produksi jaring.</p>
                </div>
            </div>
        </div>

        {{-- Panel Form --}}
        <section class="panel border-0 shadow-sm bg-white rounded-3">

            <div class="panel-header p-4 border-bottom">
                <div>
                    <h2 class="h5 mb-1 fw-bold text-dark d-flex align-items-center gap-2">
                        <i class="bi bi-ui-checks-grid text-primary"></i>
                        <span>Form Tambah Pengaturan Mesin</span>
                    </h2>
                    <p class="text-muted mb-0 small">Pilih data produksi dan isi parameter operasional mesin.</p>
                </div>
            </div>

            <div class="p-4">
                <form action="{{ route('pengaturan-mesin.store') }}" method="POST" class="needs-validation" novalidate>
                    @csrf

                    <div class="row g-4">

                        {{-- Data Produksi Select2 --}}
                        <div class="col-md-12">
                            <label class="form-label fw-semibold" for="data_produksi_id">
                                Hubungkan ke Run Data Produksi
                            </label>
                            <select class="form-select select2 @error('data_produksi_id') is-invalid @enderror" 
                                    id="data_produksi_id" name="data_produksi_id" required style="width: 100%;">
                                <option value=""></option>
                                @foreach($dataproduksi as $dp)
                                    <option value="{{ $dp->id }}" {{ old('data_produksi_id') == $dp->id ? 'selected' : '' }}>
                                        {{ \Carbon\Carbon::parse($dp->tanggal_produksi)->format('d-m-Y') }} | {{ $dp->jenis_jaring }} | {{ $dp->shift_produksi }} ({{ $dp->mesin_produksi }})
                                    </option>
                                @endforeach
                            </select>
                            @error('data_produksi_id')
                                <div class="invalid-feedback d-block">
                                    {{ $message }}
                                </div>
                            @enderror
                            <div class="form-text text-muted small">Pencarian format: Tanggal Produksi | Jenis Jaring | Shift Produksi (Kode Mesin)</div>
                        </div>

                        {{-- Suhu Mesin --}}
                        <div class="col-md-4">
                            <label class="form-label fw-semibold" for="suhu_mesin">
                                Suhu Mesin (°C)
                            </label>
                            <input class="form-control @error('suhu_mesin') is-invalid @enderror" id="suhu_mesin"
                                name="suhu_mesin" type="number" step="0.1" min="0" placeholder="Contoh: 210.5"
                                value="{{ old('suhu_mesin') }}" required>
                            @error('suhu_mesin')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        {{-- Kecepatan Mesin --}}
                        <div class="col-md-4">
                            <label class="form-label fw-semibold" for="kecepatan_mesin">
                                Kecepatan Mesin (m/min)
                            </label>
                            <input class="form-control @error('kecepatan_mesin') is-invalid @enderror" id="kecepatan_mesin"
                                name="kecepatan_mesin" type="number" step="0.1" min="0" placeholder="Contoh: 45.0"
                                value="{{ old('kecepatan_mesin') }}" required>
                            @error('kecepatan_mesin')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        {{-- Tekanan Mesin --}}
                        <div class="col-md-4">
                            <label class="form-label fw-semibold" for="tekanan_mesin">
                                Tekanan Mesin (bar)
                            </label>
                            <input class="form-control @error('tekanan_mesin') is-invalid @enderror" id="tekanan_mesin"
                                name="tekanan_mesin" type="number" step="0.1" min="0" placeholder="Contoh: 12.0"
                                value="{{ old('tekanan_mesin') }}" required>
                            @error('tekanan_mesin')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        {{-- RPM Mesin --}}
                        <div class="col-md-4">
                            <label class="form-label fw-semibold" for="rpm_mesin">
                                RPM Mesin
                            </label>
                            <input class="form-control @error('rpm_mesin') is-invalid @enderror" id="rpm_mesin"
                                name="rpm_mesin" type="number" step="1" min="0" placeholder="Contoh: 1500"
                                value="{{ old('rpm_mesin') }}" required>
                            @error('rpm_mesin')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        {{-- Waktu Operasi --}}
                        <div class="col-md-4">
                            <label class="form-label fw-semibold" for="waktu_operasi">
                                Waktu Operasi (Menit)
                            </label>
                            <input class="form-control @error('waktu_operasi') is-invalid @enderror" id="waktu_operasi"
                                name="waktu_operasi" type="number" step="0.1" min="0" placeholder="Contoh: 480"
                                value="{{ old('waktu_operasi') }}" required>
                            @error('waktu_operasi')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        {{-- Status --}}
                        <div class="col-md-4">
                            <label class="form-label fw-semibold" for="status">
                                Status Parameter
                            </label>
                            <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                                <option value="Aktif" {{ old('status', 'Aktif') === 'Aktif' ? 'selected' : '' }}>Aktif</option>
                                <option value="Nonaktif" {{ old('status') === 'Nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        {{-- Keterangan --}}
                        <div class="col-md-12">
                            <label class="form-label fw-semibold" for="keterangan">
                                Keterangan / Catatan Operasi
                            </label>
                            <textarea class="form-control @error('keterangan') is-invalid @enderror" id="keterangan"
                                name="keterangan" rows="3" placeholder="Tambahkan catatan khusus mesin atau kestabilan parameter selama operasi...">{{ old('keterangan') }}</textarea>
                            @error('keterangan')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                    </div>

                    {{-- Tombol --}}
                    <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">
                        <a href="{{ route('pengaturan-mesin.index') }}" class="btn btn-secondary rounded-3">
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
                allowClear: true
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
