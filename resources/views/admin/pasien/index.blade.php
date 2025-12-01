@extends('layouts.admin')

@section('title', 'Manajemen Pasien')

@section('content')

{{-- Load CSS --}}
<link rel="stylesheet" href="{{ asset('assets2/css/style.css') }}">

<div class="container-fluid px-4">
    <div class="page-wrapper">
        
        {{-- HEADER PAGE --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="page-title mb-0"><i class="fas fa-users-medical me-2"></i>Data Pasien</h4>
                <p class="text-muted small mb-0">Kelola data rekam medis dan identitas pasien.</p>
            </div>
            
            {{-- Tombol Tambah --}}
            <button class="btn btn-primary-modern shadow-sm" data-bs-toggle="modal" data-bs-target="#pasienModal" id="btnAddPasien">
                <i class="fas fa-plus-circle me-2"></i> Tambah Pasien
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

        {{-- TABEL DATA (TAMPILAN RAPI & MIRIP DOKTER) --}}
        <div class="card-modern shadow-sm">
            <div class="table-responsive">
                <table class="table table-hover table-striped align-middle" id="tablePasien">
                    <thead class="bg-light">
                        <tr>
                            <th width="25%">Nama Pasien</th>
                            <th width="15%">NIK</th>
                            <th width="20%">Biodata</th> {{-- Gabungan JK & Umur --}}
                            <th width="30%">Kontak & Alamat</th> {{-- Gabungan HP & Alamat --}}
                            <th width="10%" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($pasiens as $p)
                        <tr>
                            {{-- 1. NAMA (Pakai Avatar) --}}
                            <td>
                                <div class="d-flex align-items-center">
                                    {{-- Avatar Bulat --}}
                                        <i class="fas fa-user-injured text-success"></i>
                                    </div>
                                    <div>
                                        <div class="fw-bold text-dark">{{ $p->nama }}</div>
                                   
                                    </div>
                                </div>
                            </td>
                            
                            {{-- 2. NIK --}}
                            <td>
                                <span class="badge bg-light text-dark border font-monospace">
                                    <i class="fas fa-id-card me-1 text-muted"></i> {{ $p->nik }}
                                </span>
                            </td>

                            {{-- 3. BIODATA (Gabungan JK & Tgl Lahir) --}}
                            <td>
                                <div class="d-flex flex-column gap-1">
                                    {{-- Jenis Kelamin --}}
                                    @if($p->jenis_kelamin == 'Laki-laki')
                                        <span class="badge bg-primary text-white" style="width: fit-content">
                                            <i class="fas fa-mars me-1"></i> Laki-laki
                                        </span>
                                    @else
                                        {{-- Warna Pink Custom untuk Perempuan --}}
                                        <span class="badge text-white" style="width: fit-content; background-color: #ec407a;">
                                            <i class="fas fa-venus me-1"></i> Perempuan
                                        </span>
                                    @endif
                                    
                                    {{-- Umur / Tanggal Lahir --}}
                                    <span class="text-muted small">
                                        
                                        <span class="text-muted small" style="font-size: 10px;">({{ date('d/m/Y', strtotime($p->tanggal_lahir)) }})</span>
                                    </span>
                                </div>
                            </td>

                            {{-- 4. KONTAK & ALAMAT (DIGABUNG) --}}
                            <td>
                                <div class="d-flex flex-column small">
                                    {{-- No HP --}}
                                    <div class="mb-1">
                                        @if($p->nomor_telepon)
                                            <span class="text-dark font-monospace fw-bold">
                                                <i class="fas fa-phone-alt me-2 text-success"></i>{{ $p->nomor_telepon }}
                                            </span>
                                        @else
                                            <span class="text-muted"><i class="fas fa-phone-slash me-2"></i>-</span>
                                        @endif
                                    </div>
                                    
                                    {{-- Alamat --}}
                                    <div class="text-muted text-truncate" style="max-width: 250px; line-height: 1.2;" title="{{ $p->alamat }}">
                                        <i class="fas fa-map-marker-alt me-2 text-danger"></i>{{ $p->alamat ?? '-' }}
                                    </div>
                                </div>
                            </td>

                            {{-- AKSI --}}
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-1">
                                    {{-- Tombol Edit --}}
                                    <button class="btn btn-sm btn-icon btn-light-primary btn-edit" 
                                        data-id="{{ $p->pasien_id }}" 
                                        data-nama="{{ $p->nama }}" 
                                        data-nik="{{ $p->nik }}"
                                        data-nomor_telepon="{{ $p->nomor_telepon }}" 
                                        {{-- Format tanggal Y-m-d untuk Form --}}
                                        data-tanggal_lahir="{{ \Carbon\Carbon::parse($p->tanggal_lahir)->format('Y-m-d') }}"
                                        data-jenis_kelamin="{{ $p->jenis_kelamin }}"
                                        data-alamat="{{ $p->alamat }}"
                                        data-bs-toggle="modal" 
                                        data-bs-target="#pasienModal"
                                        title="Edit">
                                        <i class="fas fa-pencil-alt"></i>
                                    </button>
                                    
                                    {{-- Tombol Delete --}}
                                    <form action="{{ route('admin.pasien.destroy', $p->pasien_id) }}" method="POST" class="d-inline">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-icon btn-light-danger" onclick="return confirm('Hapus pasien {{ $p->nama }}?')" title="Hapus">
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
                                    <i class="fas fa-folder-open display-4 text-muted opacity-25 mb-3"></i>
                                    <h6 class="text-muted">Belum Ada Pasien</h6>
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

{{-- MODAL FORM --}}
<div class="modal fade" id="pasienModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title fw-bold" id="modalTitle">
                    <i class="fas fa-user-plus me-2"></i> Tambah Pasien Baru
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <form id="formPasien" action="{{ route('admin.pasien.store') }}" data-update-url="{{ route('admin.pasien.update', ':id') }}" method="POST">
                @csrf
                <div id="methodField"></div> 

                <div class="modal-body p-4">
                    <div class="row">
                        
                        {{-- 1. NAMA --}}
                        <div class="col-md-6 mb-3">
                            <label class="form-label small fw-bold text-muted">Nama Lengkap <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="fas fa-user"></i></span>
                                <input type="text" name="nama" id="nama" class="form-control" placeholder="Nama sesuai KTP" required>
                            </div>
                        </div>

                        {{-- 2. NIK --}}
                        <div class="col-md-6 mb-3">
                            <label class="form-label small fw-bold text-muted">NIK (KTP) <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="fas fa-id-card"></i></span>
                                <input type="number" name="nik" id="nik" class="form-control" placeholder="16 Digit Angka" required>
                            </div>
                        </div>

                        {{-- 3. NOMOR TELEPON --}}
                        <div class="col-md-6 mb-3">
                            <label class="form-label small fw-bold text-muted">Nomor Telepon / WA</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="fas fa-phone"></i></span>
                                <input type="number" name="nomor_telepon" id="nomor_telepon" class="form-control" placeholder="0812...">
                            </div>
                        </div>

                        {{-- 4. TANGGAL LAHIR --}}
                        <div class="col-md-6 mb-3">
                            <label class="form-label small fw-bold text-muted">Tanggal Lahir <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="fas fa-calendar-alt"></i></span>
                                <input type="date" name="tanggal_lahir" id="tanggal_lahir" class="form-control" required>
                            </div>
                        </div>

                        {{-- 5. JENIS KELAMIN --}}
                        <div class="col-md-6 mb-3">
                            <label class="form-label small fw-bold text-muted">Jenis Kelamin <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="fas fa-venus-mars"></i></span>
                                <select name="jenis_kelamin" id="jenis_kelamin" class="form-select" required>
                                    <option value="">-- Pilih --</option>
                                    <option value="Laki-laki">Laki-laki</option>
                                    <option value="Perempuan">Perempuan</option>
                                </select>
                            </div>
                        </div>

                        {{-- 6. ALAMAT --}}
                        <div class="col-md-12 mb-3">
                            <label class="form-label small fw-bold text-muted">Alamat Lengkap</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="fas fa-map-marker-alt"></i></span>
                                <textarea name="alamat" id="alamat" class="form-control" rows="2" placeholder="Jalan, RT/RW, Kecamatan..."></textarea>
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
{{-- PERBAIKAN: Gunakan Script Khusus Pasien --}}
<script src="{{ asset('assets2/js/pasien-script.js') }}"></script>

@endsection