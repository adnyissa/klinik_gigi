@extends('layouts.dokter_user')

@section('title', 'Jadwal Dokter')

@push('styles')
<link href="{{ asset('assets2/css/main.css') }}" rel="stylesheet">
@endpush

@section('content')
<div class="container-fluid pt-4 px-4">

    {{-- Welcome Banner --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="bg-white rounded-3 p-4 shadow-sm d-flex justify-content-between border-start border-4 border-success">
                <div>
                    <h4 class="text-success fw-bold mb-1">Selamat Bertugas, dr. {{ Auth::user()->name }}!</h4>
                    <p class="text-muted mb-0">Semoga hari ini berjalan lancar.</p>
                </div>
                <div class="text-end d-none d-md-block">
                    <h6 class="text-dark mb-0">{{ \Carbon\Carbon::now()->isoFormat('dddd, D MMMM Y') }}</h6>
                    <span class="badge bg-success">Aktif</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Statistik --}}
    <div class="row g-4 mb-4">
        <div class="col-sm-6 col-xl-3">
            <div class="card-clean h-100">
                <h5>Pasien Hari Ini</h5>
                <h2>{{ $pasienHariIni }}</h2>
                <p class="text-muted small mb-0">Total Terdaftar</p>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card-clean h-100">
                <h5>Menunggu</h5>
                <h2 class="text-warning">{{ $pasienMenunggu }}</h2>
                <p class="text-muted small mb-0">Belum Diperiksa</p>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card-clean h-100">
                <h5>Selesai</h5>
                <h2 class="text-success">{{ $pasienSelesai }}</h2>
                <p class="text-muted small mb-0">Sudah Diperiksa</p>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card-clean h-100">
                <h5>Jadwal Besok</h5>
                <h3 class="fw-bold text-primary mb-0">{{ $jadwalBesok }}</h3>
                <p class="text-muted small mb-0">Pasien Booking</p>
            </div>
        </div>
    </div>

    {{-- Tabel Jadwal Dokter --}}
    <div class="row g-4 mb-4">
        <div class="col-12">
            <div class="bg-white rounded-3 p-4 shadow-sm">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <h5 class="fw-bold mb-0">Jadwal Saya</h5>
                    <button class="btn btn-sm btn-success rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#tambahJadwalModal">
                        <i class="fa-solid fa-plus me-1"></i> Tambah Jadwal
                    </button>
                </div>
                <table class="table table-hover">
                    <thead class="bg-light text-muted">
                        <tr>
                            <th>No</th>
                            <th>Hari</th>
                            <th>Jam Mulai</th>
                            <th>Jam Selesai</th>
                            <th>Status</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($jadwalDokter as $jadwal)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $jadwal->hari }}</td>
                            <td>{{ $jadwal->jam_mulai }}</td>
                            <td>{{ $jadwal->jam_selesai }}</td>
                            <td>
                                @if($jadwal->aktif)
                                    <span class="badge bg-success">Aktif</span>
                                @else
                                    <span class="badge bg-secondary">Non-Aktif</span>
                                @endif
                            </td>
                            <td class="text-end">
                                <form action="{{ route('dokter.jadwal.status', $jadwal->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button class="btn btn-sm btn-warning rounded-pill" type="submit">Ubah Status</button>
                                </form>
                                <form action="{{ route('dokter.jadwal.destroy', $jadwal->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger rounded-pill" onclick="return confirm('Yakin ingin dihapus?')">Hapus</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted">Belum ada jadwal.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Tabel Antrian --}}
    <div class="row g-4 mb-4">
        <div class="col-12">
            <div class="bg-white rounded-3 p-4 shadow-sm">
                <h5 class="fw-bold mb-3">Antrian Hari Ini</h5>
                <table class="table table-hover">
                    <thead class="bg-light text-muted">
                        <tr>
                            <th>No</th>
                            <th>Nama Pasien</th>
                            <th>Jam Kunjungan</th>
                            <th>Status</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($antrianPasien as $antrian)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $antrian->pasien->nama ?? 'Nama Tidak Ditemukan' }}</td>
                            <td>
                                <span class="fw-semibold">
                                    {{ \Carbon\Carbon::parse($antrian->jam_kunjungan)->format('H:i') }}
                                </span> WIB
                            </td>
                            <td>{{ ucfirst($antrian->status) }}</td>
                            <td class="text-end">
                                <a href="#" class="btn btn-sm btn-primary rounded-pill">Periksa</a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted">Belum ada antrian hari ini.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

{{-- Modal Tambah Jadwal --}}
<div class="modal fade" id="tambahJadwalModal" tabindex="-1" aria-labelledby="tambahJadwalModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="tambahJadwalModalLabel">
                    <i class="fa-solid fa-plus me-2"></i>Tambah Jadwal Baru
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('dokter.jadwal.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="hari" class="form-label">Hari <span class="text-danger">*</span></label>
                        <select class="form-select" id="hari" name="hari" required>
                            <option value="">Pilih Hari</option>
                            <option value="Senin">Senin</option>
                            <option value="Selasa">Selasa</option>
                            <option value="Rabu">Rabu</option>
                            <option value="Kamis">Kamis</option>
                            <option value="Jumat">Jumat</option>
                            <option value="Sabtu">Sabtu</option>
                            <option value="Minggu">Minggu</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="jam_mulai" class="form-label">Jam Mulai <span class="text-danger">*</span></label>
                        <input type="time" class="form-control" id="jam_mulai" name="jam_mulai" required>
                    </div>
                    <div class="mb-3">
                        <label for="jam_selesai" class="form-label">Jam Selesai <span class="text-danger">*</span></label>
                        <input type="time" class="form-control" id="jam_selesai" name="jam_selesai" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">
                        <i class="fa-solid fa-save me-1"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show position-fixed top-0 end-0 m-3" role="alert" style="z-index: 9999;">
    <i class="fa-solid fa-check-circle me-2"></i>{{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

@endsection
