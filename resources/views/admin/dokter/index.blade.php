@extends('layouts.admin')

@section('title', 'Manajemen Dokter')

@section('content')

{{-- Load CSS --}}
<link rel="stylesheet" href="{{ asset('assets2/css/style.css') }}">

<div class="container-fluid px-4">
    <div class="page-wrapper">
        
        {{-- HEADER PAGE --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="page-title mb-0"><i class="fas fa-user-md me-2"></i>Data Dokter</h4>
                <p class="text-muted small mb-0">Kelola data dokter, spesialisasi, dan jadwal praktik.</p>
            </div>
            {{-- Tombol Tambah --}}
            <button class="btn btn-primary-modern shadow-sm" data-bs-toggle="modal" data-bs-target="#dokterModal" id="btnAddDokter">
                <i class="fas fa-plus-circle me-2"></i> Tambah Dokter
            </button>
        </div>

        {{-- NOTIFIKASI --}}
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

        {{-- TABEL DATA DOKTER --}}
        <div class="card-modern shadow-sm">
            <div class="table-responsive">
                <table class="table table-hover table-striped align-middle" id="tableDokter">
                    <thead class="bg-light">
                        <tr>
                            <th>Nama Dokter</th>
                            <th>No. SIP</th>
                            <th>Spesialisasi</th>
                            <th>Kontak (Email & HP)</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- Pastikan controller mengirim variable $dokters --}}
                        @forelse ($dokters as $d)
                        <tr>
                            {{-- 1. NAMA DOKTER --}}
                            <td>
                                <div class="d-flex align-items-center">
                                        <i class="fas fa-user-md text-primary"></i>
                                    </div>
                                    <div class="fw-bold text-dark">{{ $d->nama }}</div>
                                </div>
                            </td>
                            
                            {{-- 2. NO SIP --}}
                            <td>
                                <span class="badge bg-light text-dark border font-monospace">
                                    <i class="fas fa-id-badge me-1 text-muted"></i> {{ $d->no_sip }}
                                </span>
                            </td>

                            {{-- 3. SPESIALISASI --}}
                            <td>
                                <span class="d-flex flex-column small">
                                    <i class="fas fa-stethoscope me-1"></i> {{ $d->spesialisasi }}
                                </span>
                            </td>

                            {{-- 4. KONTAK (Email & HP Digabung Biar Rapi) --}}
                            <td>
                                <div class="d-flex flex-column small">
                                    <span class="text-dark mb-1">
                                        <i class="fas fa-envelope me-2 text-warning"></i> {{ $d->email }}
                                    </span>
                                    <span class="text-muted">
                                        <i class="fas fa-phone-alt me-2 text-success"></i> {{ $d->nomor_telepon }}
                                    </span>
                                </div>
                            </td>

                            {{-- AKSI --}}
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-1">
                                    {{-- Tombol Edit --}}
                                    <button class="btn btn-sm btn-icon btn-light-primary btn-edit" 
                                        data-id="{{ $d->dokter_id }}" 
                                        data-nama="{{ $d->nama }}" 
                                        data-sip="{{ $d->no_sip }}" 
                                        data-spesialisasi="{{ $d->spesialisasi }}" 
                                        data-hp="{{ $d->nomor_telepon }}" 
                                        data-email="{{ $d->email }}" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#dokterModal"
                                        title="Edit">
                                        <i class="fas fa-pencil-alt"></i>
                                    </button>
                                    
                                    {{-- Tombol Delete --}}
                                    <form action="{{ route('admin.dokter.destroy', $d->dokter_id) }}" method="POST" class="d-inline">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-icon btn-light-danger" onclick="return confirm('Hapus Data Dokter {{ $d->nama }}? User terkait juga akan dihapus.')" title="Hapus">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-5">
                                <div class="d-flex flex-column align-items-center justify-content-center">
                                    <i class="fas fa-user-nurse display-4 text-muted opacity-25 mb-3"></i>
                                    <h6 class="text-muted">Belum Ada Data Dokter</h6>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- MODAL FORM DOKTER --}}
<div class="modal fade" id="dokterModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title fw-bold" id="modalTitle">
                    <i class="fas fa-user-md me-2"></i> Tambah Dokter Baru
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            {{-- Form Action dinamis --}}
            <form id="formDokter" action="{{ route('admin.dokter.store') }}" data-update-url="{{ route('admin.dokter.update', ':id') }}" method="POST">
                @csrf
                <div id="methodField"></div> 

                <div class="modal-body p-4">
                    <div class="row">
                        
                        {{-- 1. NAMA DOKTER --}}
                        <div class="col-md-6 mb-3">
                            <label class="form-label small fw-bold text-muted">Nama Dokter <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="fas fa-user-md"></i></span>
                                <input type="text" name="nama" id="nama" class="form-control" placeholder="Contoh: Drg. Budi Santoso" required>
                            </div>
                        </div>

                        {{-- 2. NO SIP --}}
                        <div class="col-md-6 mb-3">
                            <label class="form-label small fw-bold text-muted">No. SIP (Surat Izin Praktik) <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="fas fa-id-badge"></i></span>
                                <input type="text" name="no_sip" id="no_sip" class="form-control" placeholder="Nomor SIP Resmi" required>
                            </div>
                        </div>

                        {{-- 3. SPESIALISASI --}}
                        <div class="col-md-12 mb-3">
                            <label class="form-label small fw-bold text-muted">Spesialisasi <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="fas fa-stethoscope"></i></span>
                                <select name="spesialisasi" id="spesialisasi" class="form-select" required>
                                    <option value="">-- Pilih Spesialisasi --</option>
                                    <option value="Dokter Gigi Umum">Dokter Gigi Umum</option>
                                    <option value="Bedah Mulut">Bedah Mulut</option>
                                    <option value="Konservasi Gigi">Konservasi Gigi (Endodontis)</option>
                                    <option value="Ortodonti">Ortodonti (Kawat Gigi)</option>
                                    <option value="Periodonti">Periodonti (Gusi)</option>
                                    <option value="Pedodonsia">Pedodonsia (Gigi Anak)</option>
                                </select>
                            </div>
                        </div>

                        {{-- Divider --}}
                        <div class="col-12 mt-2 mb-3">
                            <hr class="border-secondary opacity-25">
                            <h6 class="text-primary fw-bold mb-0"><i class="fas fa-address-book me-2"></i>Informasi Kontak & Akun</h6>
                        </div>

                        {{-- 4. NOMOR TELEPON --}}
                        <div class="col-md-6 mb-3">
                            <label class="form-label small fw-bold text-muted">Nomor Telepon <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="fas fa-phone"></i></span>
                                <input type="number" name="nomor_telepon" id="nomor_telepon" class="form-control" placeholder="0812..." required>
                            </div>
                        </div>

                        {{-- 5. EMAIL --}}
                        <div class="col-md-6 mb-3">
                            <label class="form-label small fw-bold text-muted">Email (Untuk Login) <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="fas fa-envelope"></i></span>
                                <input type="email" name="email" id="email" class="form-control" placeholder="dokter@klinik.com" required>
                            </div>
                        </div>

                        {{-- 6. PASSWORD (DITAMBAHKAN AGAR TIDAK ERROR REQUIRED) --}}
                        <div class="col-md-6 mb-3">
                            <label class="form-label small fw-bold text-muted">Password <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="fas fa-lock"></i></span>
                                <input type="password" name="password" id="password" class="form-control" placeholder="Password Akun">
                            </div>
                            <small class="text-muted" style="font-size: 11px;">*Wajib diisi untuk dokter baru.</small>
                        </div>

                    </div>
                </div>

                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary-modern px-4">
                        <i class="fas fa-save me-2"></i> Simpan Data
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Load Scripts --}}
<script src="{{ asset('assets2/js/main.js') }}"></script>

@endsection