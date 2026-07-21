@extends('qc.layoutqc')

@push('styles')
    <style>
        .card-header {
            background-color: #f8f9fa;
            border-bottom: 1px solid #e9ecef;
        }
        .section-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: #495057;
            margin-bottom: 1rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid #e9ecef;
        }
    </style>
@endpush

@section('content')
<div class="page-heading mb-4">
    <div class="page-heading-copy">
        <span class="page-icon">
            <i class="bi bi-pencil-square"></i>
        </span>
        <div>
            <p class="eyebrow mb-1">Quality Control</p>
            <h1 class="h3 mb-1">Edit Pemeriksaan QC</h1>
            <p class="text-muted mb-0">Ubah data hasil pemeriksaan QC jaring.</p>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12 col-xl-10">
        
        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                <strong><i class="bi bi-exclamation-triangle-fill me-2"></i> Terdapat kesalahan:</strong>
                <ul class="mb-0 mt-2">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="card shadow-sm border-0">
            <div class="card-header py-3">
                <h5 class="mb-0 fw-bold"><i class="bi bi-file-earmark-text me-2 text-primary"></i> Form Edit Data Pemeriksaan</h5>
            </div>
            
            <form action="{{ route('qc.pemeriksaan.update', $pemeriksaan->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="card-body p-4">
                    
                    <h6 class="section-title"><i class="bi bi-box-seam me-2"></i> Informasi Produksi</h6>
                    
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Pilih Data Produksi <span class="text-danger">*</span></label>
                            <select name="id_produksi" id="id_produksi" class="form-select @error('id_produksi') is-invalid @enderror" required>
                                <option value="">-- Pilih Data Produksi --</option>
                                @foreach($produksi as $item)
                                    <option value="{{ $item->id }}" {{ old('id_produksi', $pemeriksaan->id_produksi) == $item->id ? 'selected' : '' }}>
                                        ID-{{ $item->id }} | {{ $item->jenis_jaring }} | {{ $item->bulan_produksi ? \Carbon\Carbon::parse($item->bulan_produksi)->translatedFormat('F Y') : '-' }}
                                    </option>
                                @endforeach
                            </select>
                            @error('id_produksi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Jenis Jaring</label>
                            <input type="text" id="jenis_jaring" class="form-control bg-light" readonly placeholder="Otomatis terisi...">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Bulan Produksi</label>
                            <input type="text" id="bulan_produksi" class="form-control bg-light" readonly placeholder="Otomatis terisi...">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Jumlah Pesanan (PCS)</label>
                            <input type="text" id="jumlah_pesanan" class="form-control bg-light" readonly placeholder="Otomatis terisi...">
                        </div>
                    </div>

                    <h6 class="section-title mt-5"><i class="bi bi-clipboard2-data me-2"></i> Hasil Pemeriksaan QC</h6>

                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Jumlah Cek (PCS) <span class="text-danger">*</span></label>
                            <input type="number" name="jumlah_cek" id="jumlah_cek" class="form-control @error('jumlah_cek') is-invalid @enderror" value="{{ old('jumlah_cek', $pemeriksaan->jumlah_cek) }}" required min="0">
                            <small class="text-muted">Tidak boleh melebihi Jumlah Pesanan.</small>
                            @error('jumlah_cek')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-success">Jumlah Baik (PCS) <span class="text-danger">*</span></label>
                            <input type="number" name="baik" id="baik" class="form-control @error('baik') is-invalid @enderror defect-input" value="{{ old('baik', $pemeriksaan->baik) }}" required min="0">
                            @error('baik')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-md-3 col-sm-6">
                            <label class="form-label fw-semibold">Rusak Ringan (RR)</label>
                            <input type="number" name="rr" id="rr" class="form-control defect-input" value="{{ old('rr', $pemeriksaan->rr) }}" required min="0">
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <label class="form-label fw-semibold">Parah Ringan (PR)</label>
                            <input type="number" name="pr" id="pr" class="form-control defect-input" value="{{ old('pr', $pemeriksaan->pr) }}" required min="0">
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <label class="form-label fw-semibold">RPS</label>
                            <input type="number" name="rps" id="rps" class="form-control defect-input" value="{{ old('rps', $pemeriksaan->rps) }}" required min="0">
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <label class="form-label fw-semibold">SUPER</label>
                            <input type="number" name="super" id="super" class="form-control defect-input" value="{{ old('super', $pemeriksaan->super) }}" required min="0">
                        </div>
                        <div class="col-md-4 col-sm-6">
                            <label class="form-label fw-semibold">Rusak Jalur (RJ)</label>
                            <input type="number" name="rj" id="rj" class="form-control defect-input" value="{{ old('rj', $pemeriksaan->rj) }}" required min="0">
                        </div>
                        <div class="col-md-4 col-sm-6">
                            <label class="form-label fw-semibold">Berbulu</label>
                            <input type="number" name="berbulu" id="berbulu" class="form-control defect-input" value="{{ old('berbulu', $pemeriksaan->berbulu) }}" required min="0">
                        </div>
                        <div class="col-md-4 col-sm-6">
                            <label class="form-label fw-semibold">Rusak Blok</label>
                            <input type="number" name="rusak_blok" id="rusak_blok" class="form-control defect-input" value="{{ old('rusak_blok', $pemeriksaan->rusak_blok) }}" required min="0">
                        </div>
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-md-12">
                            <label class="form-label fw-bold text-danger">Total Defect (Dihitung Otomatis)</label>
                            <input type="number" id="total_defect" class="form-control bg-white text-danger fw-bold" readonly value="0">
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold">Keterangan (Opsional)</label>
                        <textarea name="keterangan" class="form-control" rows="3" placeholder="Tambahkan catatan jika diperlukan...">{{ old('keterangan', $pemeriksaan->keterangan) }}</textarea>
                    </div>
                </div>
                
                <div class="card-footer bg-white p-4 text-end border-top">
                    <a href="{{ route('qc.pemeriksaan.index') }}" class="btn btn-secondary me-2">Batal</a>
                    <button type="submit" class="btn btn-primary" id="btn-submit">
                        <i class="bi bi-save me-1"></i> Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const selectProduksi = document.getElementById('id_produksi');
        const defectInputs = document.querySelectorAll('.defect-input');
        const jumlahCekInput = document.getElementById('jumlah_cek');
        
        // Month array for translation
        const months = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];

        // Format Date
        function formatDate(dateString) {
            if(!dateString) return '-';
            const date = new Date(dateString);
            return `${months[date.getMonth()]} ${date.getFullYear()}`;
        }

        // Fetch data produksi via AJAX
        selectProduksi.addEventListener('change', function() {
            const id = this.value;
            if (id) {
                fetch(`/qc/pemeriksaan-qc/get-produksi/${id}`)
                    .then(response => response.json())
                    .then(data => {
                        document.getElementById('jenis_jaring').value = data.jenis_jaring || '-';
                        document.getElementById('bulan_produksi').value = formatDate(data.bulan_produksi);
                        document.getElementById('jumlah_pesanan').value = data.jumlah_pesanan || '0';
                    })
                    .catch(error => console.error('Error:', error));
            } else {
                document.getElementById('jenis_jaring').value = '';
                document.getElementById('bulan_produksi').value = '';
                document.getElementById('jumlah_pesanan').value = '';
            }
        });

        // Trigger change on load if there's old data
        if(selectProduksi.value) {
            selectProduksi.dispatchEvent(new Event('change'));
        }

        // Calculate Totals
        function calculateTotals() {
            const baik = parseInt(document.getElementById('baik').value) || 0;
            const rr = parseInt(document.getElementById('rr').value) || 0;
            const pr = parseInt(document.getElementById('pr').value) || 0;
            const rps = parseInt(document.getElementById('rps').value) || 0;
            const superVal = parseInt(document.getElementById('super').value) || 0;
            const rj = parseInt(document.getElementById('rj').value) || 0;
            const berbulu = parseInt(document.getElementById('berbulu').value) || 0;
            const rusak_blok = parseInt(document.getElementById('rusak_blok').value) || 0;
            
            const jumlah_cek = parseInt(jumlahCekInput.value) || 0;

            const totalDefect = rr + pr + rps + superVal + rj + berbulu + rusak_blok;

            document.getElementById('total_defect').value = totalDefect;

            // Validasi jumlah baik <= jumlah cek & total defect <= jumlah cek
            const baikInput = document.getElementById('baik');
            const btnSubmit = document.getElementById('btn-submit');
            let errorDiv = document.getElementById('baik-js-error');
            let defectErrorDiv = document.getElementById('defect-js-error');
            
            let isValid = true;

            if (baik > jumlah_cek) {
                baikInput.classList.add('is-invalid');
                if (!errorDiv) {
                    errorDiv = document.createElement('div');
                    errorDiv.className = 'invalid-feedback d-block';
                    errorDiv.id = 'baik-js-error';
                    errorDiv.innerText = 'Jumlah Baik tidak boleh melebihi Jumlah Cek.';
                    baikInput.parentNode.appendChild(errorDiv);
                }
                isValid = false;
            } else {
                baikInput.classList.remove('is-invalid');
                if (errorDiv) {
                    errorDiv.remove();
                }
            }

            if (totalDefect > jumlah_cek) {
                document.getElementById('total_defect').classList.add('is-invalid');
                if (!defectErrorDiv) {
                    defectErrorDiv = document.createElement('div');
                    defectErrorDiv.className = 'invalid-feedback d-block text-danger mt-2';
                    defectErrorDiv.id = 'defect-js-error';
                    defectErrorDiv.innerText = 'Data Kerusakan (Total Defect) tidak boleh melebihi Jumlah Cek.';
                    document.getElementById('total_defect').parentNode.appendChild(defectErrorDiv);
                }
                isValid = false;
            } else {
                document.getElementById('total_defect').classList.remove('is-invalid');
                if (defectErrorDiv) {
                    defectErrorDiv.remove();
                }
            }

            btnSubmit.disabled = !isValid;
        }

        defectInputs.forEach(input => {
            input.addEventListener('input', calculateTotals);
        });
        
        jumlahCekInput.addEventListener('input', calculateTotals);

        // Initial calculation
        calculateTotals();
    });
</script>
@endpush
