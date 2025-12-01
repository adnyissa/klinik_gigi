@extends('layouts.admin')

@section('title', 'Manajemen Rekam Medis')

@section('content')

{{-- NOTE: Pastikan Anda memiliki Bootstrap dan Font Awesome yang dimuat di layouts/admin.blade.php --}}
<link rel="stylesheet" href="{{ asset('assets2/css/style.css') }}">

<div class="container-fluid px-4">
    <div class="page-wrapper">
        
        {{-- HEADER --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="page-title mb-0"><i class="fas fa-stethoscope me-2"></i>Rekam Medis Pasien</h4>
                <p class="text-muted small mb-0">Manajemen riwayat medis, diagnosis, dan tindakan.</p>
            </div>
            
            <div class="d-flex gap-2">
                {{-- Live Search --}}
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0"><i class="fas fa-search text-muted"></i></span>
                    <input type="text" id="searchInput" class="form-control border-start-0 ps-0" placeholder="Cari Pasien / Dokter..." style="width: 200px;">
                </div>

                {{-- Tombol Tambah --}}
                <button class="btn btn-primary-modern shadow-sm text-nowrap" data-bs-toggle="modal" data-bs-target="#rmModal" id="btnAddRM">
                    <i class="fas fa-plus-circle me-2"></i> Buat Rekam Medis Baru
                </button>
            </div>
        </div>

        {{-- ALERT --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error) 
                        <li><i class="fas fa-exclamation-triangle me-2"></i>{{ $error }}</li> 
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- TABEL DATA --}}
        <div class="card-modern shadow-sm">
            <div class="table-responsive">
                <table class="table table-hover table-striped align-middle" id="tableRM">
                    <thead class="bg-light">
                        <tr>
                            <th width="15%">Tanggal</th>
                            <th width="20%">Pasien</th>
                            <th width="20%">Dokter</th>
                            <th width="20%">Diagnosis</th>
                            <th width="15%">Biaya Total</th>
                            <th width="10%" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($rekamMedis as $rm)
                        <tr class="data-row">
                            {{-- 1. TANGGAL --}}
                            <td>
                                <span class="badge bg-secondary-modern text-dark border p-2">
                                    {{ \Carbon\Carbon::parse($rm->created_at)->format('d M Y') }}
                                </span>
                            </td>

                            {{-- 2. PASIEN --}}
                            <td class="search-target">
                                <div class="fw-bold text-dark">{{ $rm->konsultasi->pasien->nama ?? 'N/A' }}</div>
                                <small class="text-muted" style="font-size: 11px;">ID Konsultasi: {{ $rm->konsultasi_id }}</small>
                            </td>
                            
                            {{-- 3. DOKTER --}}
                            <td class="search-target">
                                <div class="text-dark">{{ $rm->dokter->nama ?? 'N/A' }}</div>
                                <small class="text-muted" style="font-size: 11px;">{{ $rm->dokter->spesialisasi ?? '' }}</small>
                            </td>

                            {{-- 4. DIAGNOSIS --}}
                            <td>
                                <span class="fw-bold text-info">{{ $rm->diagnosis }}</span>
                            </td>

                            {{-- 5. BIAYA TOTAL --}}
                            <td>
                                <span class="fw-bold text-success">
                                    Rp. {{ number_format($rm->biaya_total, 0, ',', '.') }}
                                </span>
                            </td>

                            {{-- AKSI --}}
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-1">
                                    {{-- Tombol Detail --}}
                                    <button class="btn btn-sm btn-icon btn-light-info btn-view-detail" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#detailModal"
                                        data-diagnosis="{{ $rm->diagnosis }}"
                                        data-tindakan="{{ $rm->tindakan }}"
                                        data-biaya="{{ number_format($rm->biaya_total, 0, ',', '.') }}"
                                        data-dokter="{{ $rm->dokter->nama ?? 'N/A' }} ({{ $rm->dokter->spesialisasi ?? '' }})"
                                        data-pasien="{{ $rm->konsultasi->pasien->nama ?? 'N/A' }}"
                                        title="Lihat Detail">
                                        <i class="fas fa-eye"></i>
                                    </button>

                                    {{-- Tombol Edit --}}
                                    <button class="btn btn-sm btn-icon btn-light-primary btn-edit" 
                                        data-id="{{ $rm->rekam_medis_id }}" 
                                        data-konsultasi_id="{{ $rm->konsultasi_id }}" 
                                        data-dokter_id="{{ $rm->dokter_id }}" 
                                        data-kasir_id="{{ $rm->kasir_id }}" 
                                        data-diagnosis="{{ $rm->diagnosis }}" 
                                        data-tindakan="{{ $rm->tindakan }}"
                                        data-biaya_total="{{ $rm->biaya_total }}"
                                        data-bs-toggle="modal" 
                                        data-bs-target="#rmModal"
                                        title="Edit">
                                        <i class="fas fa-pencil-alt"></i>
                                    </button>
                                    
                                    {{-- Tombol Hapus --}}
                                    <form action="{{ route('admin.rm.destroy', $rm->rekam_medis_id) }}" method="POST" class="d-inline form-delete">
                                        @csrf @method('DELETE')
                                        <button type="button" class="btn btn-sm btn-icon btn-light-danger btn-delete-confirm" title="Hapus">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <div class="d-flex flex-column align-items-center justify-content-center">
                                    <i class="fas fa-file-medical display-4 text-muted opacity-25 mb-3"></i>
                                    <h6 class="text-muted">Belum Ada Rekam Medis Tercatat</h6>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                        
                        <tr id="noResultRow" class="d-none">
                            <td colspan="6" class="text-center py-4 text-muted">
                                <i class="fas fa-search me-2"></i> Data tidak ditemukan.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- MODAL FORM TAMBAH/EDIT --}}
<div class="modal fade" id="rmModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title fw-bold" id="modalTitle">
                    <i class="fas fa-plus-circle me-2"></i> Buat Rekam Medis Baru
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <form id="formRM" 
                action="{{ route('admin.rm.store') }}" 
                data-store-url="{{ route('admin.rm.store') }}"
                data-update-url="{{ route('admin.rm.update', ':id') }}" 
                method="POST">
                @csrf
                <div id="methodField"></div> 

                <div class="modal-body p-4">
                    <div class="row">
                        
                        {{-- 1. KONSULTASI (Pasien) --}}
                        <div class="col-md-6 mb-3">
                            <label class="form-label small fw-bold text-muted">Pilih Konsultasi/Pasien</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="fas fa-user-injured"></i></span>
                                <select name="konsultasi_id" id="konsultasi_id" class="form-select" required>
                                    <option value="">-- Pilih Konsultasi Pasien --</option>
                                    @foreach($konsultasis as $k)
                                        <option value="{{ $k->konsultasi_id }}">
                                            {{ $k->pasien_nama }} (ID: {{ $k->konsultasi_id }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        {{-- 2. DOKTER --}}
                        <div class="col-md-6 mb-3">
                            <label class="form-label small fw-bold text-muted">Dokter Penanggung Jawab</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="fas fa-user-md"></i></span>
                                <select name="dokter_id" id="dokter_id" class="form-select" required>
                                    <option value="">-- Pilih Dokter --</option>
                                    @foreach($dokters as $d)
                                        <option value="{{ $d->dokter_id }}">
                                            {{ $d->nama }} ({{ $d->spesialisasi }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        {{-- 3. DIAGNOSIS --}}
                        <div class="col-12 mb-3">
                            <label class="form-label small fw-bold text-muted">Diagnosis (Ringkasan Penyakit)</label>
                            <input type="text" name="diagnosis" id="diagnosis" class="form-control" placeholder="Contoh: Flu berat, Migrain, dll." required>
                        </div>
                        
                        {{-- 4. TINDAKAN --}}
                        <div class="col-12 mb-3">
                            <label class="form-label small fw-bold text-muted">Tindakan/Resep (Detail Pengobatan)</label>
                            <textarea name="tindakan" id="tindakan" rows="4" class="form-control" placeholder="Tuliskan resep obat, prosedur tindakan, dan saran untuk pasien..." required></textarea>
                        </div>

                        {{-- 5. BIAYA TOTAL --}}
                        <div class="col-md-6 mb-3">
                            <label class="form-label small fw-bold text-muted">Biaya Total (Rp)</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light">Rp</span>
                                <input type="number" name="biaya_total" id="biaya_total" class="form-control" step="1" min="0" placeholder="Contoh: 150000" required>
                            </div>
                        </div>
                        
                        {{-- 6. KASIR --}}
                        <div class="col-md-6 mb-3">
                            <label class="form-label small fw-bold text-muted">Petugas Kasir</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="fas fa-cash-register"></i></span>
                                <select name="kasir_id" id="kasir_id" class="form-select" required>
                                    <option value="">-- Pilih Petugas Kasir --</option>
                                    @foreach($kasirs as $k)
                                        <option value="{{ $k->kasir_id }}">
                                            {{ $k->nama }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary-modern px-4" id="btnSubmitRM">
                        <i class="fas fa-save me-2"></i> Simpan Rekam Medis
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- MODAL DETAIL (VIEW) --}}
<div class="modal fade" id="detailModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title fw-bold">
                    <i class="fas fa-file-medical me-2"></i> Detail Rekam Medis
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="mb-3">
                    <span class="small fw-bold text-muted">Pasien:</span>
                    <h5 id="detailPasien" class="text-dark fw-bold"></h5>
                </div>
                <div class="mb-4">
                    <span class="small fw-bold text-muted">Dokter:</span>
                    <p id="detailDokter" class="mb-0"></p>
                </div>

                <div class="card bg-light p-3 mb-4 border-info border-2">
                    <span class="small fw-bold text-muted">Diagnosis Utama:</span>
                    <h4 id="detailDiagnosis" class="text-info fw-bold mt-1"></h4>
                </div>

                <div class="mb-4">
                    <span class="small fw-bold text-muted">Tindakan & Resep:</span>
                    <p id="detailTindakan" class="text-dark border-start border-3 border-secondary ps-3" style="white-space: pre-wrap;"></p>
                </div>
                
                <div class="alert alert-success d-flex justify-content-between align-items-center mb-0 shadow-sm">
                    <span class="fw-bold">Biaya Total Administrasi:</span>
                    <h4 id="detailBiaya" class="fw-bolder mb-0"></h4>
                </div>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

{{-- Fungsionalitas JavaScript untuk Modal dan Live Search --}}
<script src="{{ asset('assets2/js/main.js') }}"></script>

@endsection