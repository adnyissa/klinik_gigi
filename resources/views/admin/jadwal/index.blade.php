@extends('layouts.admin')

@section('title', 'Jadwal Praktik Dokter')

@section('content')

<link rel="stylesheet" href="{{ asset('assets2/css/style.css') }}">

<div class="container-fluid px-4">
    <div class="page-wrapper">
        
        {{-- HEADER --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="page-title mb-0"><i class="fas fa-calendar-alt me-2"></i>Jadwal Praktik</h4>
                <p class="text-muted small mb-0">Atur hari dan jam praktik dokter.</p>
            </div>
            
            <div class="d-flex gap-2">
                {{-- Live Search --}}
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0"><i class="fas fa-search text-muted"></i></span>
                    <input type="text" id="searchInput" class="form-control border-start-0 ps-0" placeholder="Cari Dokter / Hari..." style="width: 200px;">
                </div>

                {{-- Tombol Tambah --}}
                <button class="btn btn-primary-modern shadow-sm text-nowrap" data-bs-toggle="modal" data-bs-target="#jadwalModal" id="btnAddJadwal">
                    <i class="fas fa-plus-circle me-2"></i> Tambah Jadwal
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
                <table class="table table-hover table-striped align-middle" id="tableJadwal">
                    <thead class="bg-light">
                        <tr>
                            <th width="25%">Dokter</th>
                            <th width="15%">Hari</th>
                            <th width="25%">Jam Praktik</th>
                            <th width="15%">Status</th>
                            <th width="10%" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($jadwals as $j)
                        <tr class="data-row">
                            {{-- 1. DOKTER --}}
                            <td>
                                <div class="d-flex align-items-center">
                                        <i class="fas fa-user-md text-info"></i>
                                    </div>
                                    <div>
                                        <div class="fw-bold text-dark">{{ $j->dokter->nama }}</div>
                                        <small class="text-muted" style="font-size: 11px;">{{ $j->dokter->spesialisasi }}</small>
                                    </div>
                                </div>
                            </td>
                            
                            {{-- 2. HARI --}}
                            <td>
                                <span class="fw-bold text-dark">{{ $j->hari }}</span>
                            </td>

                            {{-- 3. JAM PRAKTIK --}}
                            <td>
                                <span class="badge bg-light text-dark border">
                                    <i class="far fa-clock me-1"></i> 
                                    {{ \Carbon\Carbon::parse($j->jam_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($j->jam_selesai)->format('H:i') }}
                                </span>
                            </td>

                            {{-- 4. STATUS --}}
                            <td>
                                @if($j->status == 'Aktif')
                                        <i class="fas fa-check-circle me-1"></i> Aktif
                                    </span>
                                @else
                                        <i class="fas fa-ban me-1"></i> Libur
                                    </span>
                                @endif
                            </td>

                            {{-- AKSI --}}
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-1">
                                    <button class="btn btn-sm btn-icon btn-light-primary btn-edit" 
                                        data-id="{{ $j->jadwal_id }}" 
                                        data-dokter_id="{{ $j->dokter_id }}" 
                                        data-hari="{{ $j->hari }}" 
                                        data-jam_mulai="{{ $j->jam_mulai }}" 
                                        data-jam_selesai="{{ $j->jam_selesai }}" 
                                        data-status="{{ $j->status }}" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#jadwalModal"
                                        title="Edit">
                                        <i class="fas fa-pencil-alt"></i>
                                    </button>
                                    
                                    <form action="{{ route('admin.jadwal.destroy', $j->jadwal_id) }}" method="POST" class="d-inline">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-icon btn-light-danger" onclick="return confirm('Hapus jadwal ini?')" title="Hapus">
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
                                    <i class="fas fa-calendar-times display-4 text-muted opacity-25 mb-3"></i>
                                    <h6 class="text-muted">Belum Ada Jadwal Praktik</h6>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                        
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
<div class="modal fade" id="jadwalModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title fw-bold" id="modalTitle">
                    <i class="fas fa-plus-circle me-2"></i> Tambah Jadwal
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <form id="formJadwal" action="{{ route('admin.jadwal.store') }}" data-update-url="{{ route('admin.jadwal.update', ':id') }}" method="POST">
                @csrf
                <div id="methodField"></div> 

                <div class="modal-body p-4">
                    {{-- CLIENT SIDE ERROR ALERT for Time --}}
                    <div id="jamErrorAlert" class="alert alert-danger d-none" role="alert">
                        <!-- Error message will be inserted here by JS -->
                    </div>

                    <div class="row">
                        
                        {{-- Pilih Dokter --}}
                        <div class="col-12 mb-3">
                            <label class="form-label small fw-bold text-muted">Pilih Dokter</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="fas fa-user-md"></i></span>
                                <select name="dokter_id" id="dokter_id" class="form-select" required>
                                    <option value="">-- Pilih Dokter --</option>
                                    @foreach($dokters as $dokter)
                                        <option value="{{ $dokter->dokter_id }}">{{ $dokter->nama }} ({{ $dokter->spesialisasi }})</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        {{-- Hari --}}
                        <div class="col-12 mb-3">
                            <label class="form-label small fw-bold text-muted">Hari Praktik</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="fas fa-calendar-day"></i></span>
                                <select name="hari" id="hari" class="form-select" required>
                                    <option value="">-- Pilih Hari --</option>
                                    @foreach(['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'] as $hari)
                                        <option value="{{ $hari }}">{{ $hari }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        {{-- Jam --}}
                        <div class="col-6 mb-3">
                            <label class="form-label small fw-bold text-muted">Jam Mulai</label>
                            <input type="time" name="jam_mulai" id="jam_mulai" class="form-control" required>
                        </div>
                        <div class="col-6 mb-3">
                            <label class="form-label small fw-bold text-muted">Jam Selesai</label>
                            <input type="time" name="jam_selesai" id="jam_selesai" class="form-control" required>
                        </div>

                        {{-- Status --}}
                        <div class="col-12 mb-3">
                            <label class="form-label small fw-bold text-muted">Status</label>
                            <div class="d-flex gap-4 mt-1">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="status" id="statusAktif" value="Aktif" checked>
                                    <label class="form-check-label text-success fw-bold" for="statusAktif">
                                        <i class="fas fa-check-circle me-1"></i> Aktif
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="status" id="statusLibur" value="Libur">
                                    <label class="form-check-label text-danger fw-bold" for="statusLibur">
                                        <i class="fas fa-ban me-1"></i> Libur
                                    </label>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary-modern px-4">
                        <i class="fas fa-save me-2"></i> Simpan Jadwal
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="{{ asset('assets2/js/main.js') }}"></script>

@endsection