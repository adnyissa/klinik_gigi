@extends('layouts.admin')

@section('title', 'Manajemen Kasir')

@section('content')

<link rel="stylesheet" href="{{ asset('assets2/css/style.css') }}">

<div class="container-fluid px-4">
    <div class="page-wrapper">
        
        {{-- HEADER PAGE --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="page-title mb-0"><i class="fas fa-cash-register me-2"></i>Data Kasir</h4>
                <p class="text-muted small mb-0">Kelola akun kasir dan pembagian shift kerja.</p>
            </div>
            
            {{-- Group Search & Tambah --}}
            <div class="d-flex align-items-center gap-2">
                {{-- Live Search --}}
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0"><i class="fas fa-search text-muted"></i></span>
                    <input type="text" id="searchInput" class="form-control border-start-0 ps-0" placeholder="Cari Kasir..." style="width: 200px;">
                </div>

                {{-- Tombol Tambah --}}
                <button class="btn btn-primary-modern shadow-sm text-nowrap" data-bs-toggle="modal" data-bs-target="#kasirModal" id="btnAddKasir">
                    <i class="fas fa-plus-circle me-2"></i> Tambah
                </button>
            </div>
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

        {{-- TABEL DATA --}}
        <div class="card-modern shadow-sm">
            <div class="table-responsive">
                <table class="table table-hover table-striped align-middle" id="tableKasir">
                    <thead class="bg-light">
                        <tr>
                            <th width="30%">Nama Kasir</th>
                            <th width="25%">Kontak</th>
                            <th width="15%">Shift Kerja</th>
                            <th width="20%">Alamat</th>
                            <th width="10%" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($kasirs as $k)
                        <tr class="data-row">
                            {{-- 1. NAMA (Ambil dari relasi User) --}}
                            <td>
                                <div class="d-flex align-items-center">
                                        <i class="fas fa-user-tie text-warning"></i>
                                    </div>
                                    <div>
                                        <div class="fw-bold text-dark">{{ $k->user->name }}</div>
                                        <small class="text-muted" style="font-size: 11px;">ID: KSR-{{ $k->kasir_id }}</small>
                                    </div>
                                </div>
                            </td>
                            
                            {{-- 2. KONTAK (Email User & HP Kasir) --}}
                            <td>
                                <div class="d-flex flex-column small">
                                    <span class="text-dark mb-1">
                                        <i class="fas fa-envelope me-2 text-secondary"></i>{{ $k->user->email }}
                                    </span>
                                    <span class="text-muted">
                                        <i class="fas fa-phone me-2 text-success"></i>{{ $k->no_hp }}
                                    </span>
                                </div>
                            </td>

                            {{-- 3. SHIFT KERJA --}}
                            <td>
                                @php
                                    $badgeColor = match($k->shift_kerja) {
                                        'Pagi' => 'bg-warning text-dark',
                                        'Siang' => 'bg-info text-dark',
                                        'Malam' => 'bg-dark text-white',
                                        default => 'bg-secondary text-white'
                                    };
                                    $icon = match($k->shift_kerja) {
                                        'Pagi' => 'fa-sun',
                                        'Siang' => 'fa-cloud-sun',
                                        'Malam' => 'fa-moon',
                                        default => 'fa-clock'
                                    };
                                @endphp
                                <span class="badge {{ $badgeColor }}">
                                    <i class="fas {{ $icon }} me-1"></i> {{ $k->shift_kerja }}
                                </span>
                            </td>

                            {{-- 4. ALAMAT --}}
                            <td>
                                <span class="text-muted small text-truncate d-inline-block" style="max-width: 200px;" title="{{ $k->alamat }}">
                                    <i class="fas fa-map-marker-alt me-1 text-danger"></i> {{ $k->alamat }}
                                </span>
                            </td>

                            {{-- AKSI --}}
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-1">
                                    <button class="btn btn-sm btn-icon btn-light-primary btn-edit" 
                                        data-id="{{ $k->kasir_id }}" 
                                        data-nama="{{ $k->user->name }}" 
                                        data-email="{{ $k->user->email }}" 
                                        data-hp="{{ $k->no_hp }}" 
                                        data-shift="{{ $k->shift_kerja }}" 
                                        data-alamat="{{ $k->alamat }}" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#kasirModal"
                                        title="Edit">
                                        <i class="fas fa-pencil-alt"></i>
                                    </button>
                                    
                                    <form action="{{ route('admin.kasir.destroy', $k->kasir_id) }}" method="POST" class="d-inline">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-icon btn-light-danger" onclick="return confirm('Hapus Akun Kasir {{ $k->user->name }}?')" title="Hapus">
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
                                    <i class="fas fa-cash-register display-4 text-muted opacity-25 mb-3"></i>
                                    <h6 class="text-muted">Belum Ada Data Kasir</h6>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                        
                        {{-- Not Found Row --}}
                        <tr id="noResultRow" class="d-none">
                            <td colspan="5" class="text-center py-4 text-muted">
                                <i class="fas fa-search me-2"></i> Data tidak ditemukan.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- MODAL FORM --}}
<div class="modal fade" id="kasirModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title fw-bold" id="modalTitle">
                    <i class="fas fa-user-plus me-2"></i> Tambah Kasir Baru
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            {{-- Route harus disesuaikan di web.php --}}
            <form id="formKasir" action="{{ route('admin.kasir.store') }}" data-update-url="{{ route('admin.kasir.update', ':id') }}" method="POST">
                @csrf
                <div id="methodField"></div> 

                <div class="modal-body p-4">
                    <div class="row">
                        <div class="col-12 mb-3">
                            <h6 class="text-primary fw-bold border-bottom pb-2"><i class="fas fa-user-circle me-2"></i>Informasi Akun (Login)</h6>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label small fw-bold text-muted">Nama Lengkap <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="fas fa-user"></i></span>
                                <input type="text" name="nama" id="nama" class="form-control" required>
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label small fw-bold text-muted">Email <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="fas fa-envelope"></i></span>
                                <input type="email" name="email" id="email" class="form-control" required>
                            </div>
                        </div>

                        <div class="col-md-12 mb-3">
                            <label class="form-label small fw-bold text-muted">Password</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="fas fa-lock"></i></span>
                                <input type="password" name="password" id="password" class="form-control" placeholder="Password Akun">
                            </div>
                            <small id="passHelp" class="text-muted d-none" style="font-size: 11px;">*Kosongkan jika tidak ingin mengganti password.</small>
                        </div>

                        <div class="col-12 mt-2 mb-3">
                            <h6 class="text-primary fw-bold border-bottom pb-2"><i class="fas fa-address-card me-2"></i>Detail Kasir</h6>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label small fw-bold text-muted">Nomor HP / WA <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="fas fa-phone"></i></span>
                                <input type="number" name="no_hp" id="no_hp" class="form-control" placeholder="08..." required>
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label small fw-bold text-muted">Shift Kerja <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="fas fa-clock"></i></span>
                                <select name="shift_kerja" id="shift_kerja" class="form-select" required>
                                    <option value="">-- Pilih Shift --</option>
                                    <option value="Pagi">Pagi (07:00 - 14:00)</option>
                                    <option value="Siang">Siang (14:00 - 21:00)</option>
                                    <option value="Malam">Malam (21:00 - Tutup)</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-12 mb-3">
                            <label class="form-label small fw-bold text-muted">Alamat Lengkap</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="fas fa-map-marker-alt"></i></span>
                                <textarea name="alamat" id="alamat" class="form-control" rows="2" placeholder="Alamat domisili..." required></textarea>
                            </div>
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

<script src="{{ asset('assets2/js/main.js') }}"></script>

@endsection