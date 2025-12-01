@extends('layouts.admin')

@section('title', 'Dashboard Dokter')

@push('styles')
    <link href="{{ asset('assets2/css/custom_dokter.css') }}" rel="stylesheet">
@endpush

@section('content')
<div class="container-fluid pt-4 px-4">
    
    <!-- Welcome Banner -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="bg-white rounded-3 p-4 shadow-sm d-flex align-items-center justify-content-between border-start border-4 border-primary">
                <div>
                    <h4 class="text-primary fw-bold mb-1">Selamat Bertugas, dr. {{ Auth::user()->name ?? 'Dokter' }}!</h4>
                    <p class="text-muted mb-0">Semoga hari ini menyenangkan. Anda memiliki <strong class="text-dark">{{ $pasienMenunggu ?? 0 }} pasien</strong> menunggu.</p>
                </div>
                <div class="d-none d-md-block text-end">
                    <h6 class="text-dark mb-0">{{ \Carbon\Carbon::now()->isoFormat('dddd, D MMMM Y') }}</h6>
                    <div class="mt-1">
                        <span class="badge bg-primary bg-opacity-75 rounded-pill"><i class="fa fa-user-md me-1"></i>Dokter Gigi</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistik Cards (Data Real dari Controller) -->
    <div class="row g-4 mb-4">
        <div class="col-sm-6 col-xl-3">
            <div class="card-clean h-100 bg-white p-3 rounded shadow-sm border">
                <h5 class="text-muted small">Pasien Hari Ini</h5>
                <h2 class="mb-0 fw-bold">{{ $pasienHariIni ?? 0 }}</h2>
                <p class="text-muted small mb-0">Total Terdaftar</p>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card-clean h-100 bg-white p-3 rounded shadow-sm border border-warning">
                <h5 class="text-muted small">Menunggu</h5>
                <h2 class="text-warning mb-0 fw-bold">{{ $pasienMenunggu ?? 0 }}</h2>
                <p class="text-muted small mb-0">Perlu Tindakan</p>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card-clean h-100 bg-white p-3 rounded shadow-sm border border-success">
                <h5 class="text-muted small">Selesai</h5>
                <h2 class="text-success mb-0 fw-bold">{{ $pasienSelesai ?? 0 }}</h2>
                <p class="text-muted small mb-0">Sudah Pulang</p>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card-clean h-100 bg-white p-3 rounded shadow-sm border">
                <h5 class="text-muted small">Jadwal Besok</h5>
                <h3 class="fw-bold text-info mb-0">{{ $jadwalBesok ?? 0 }}</h3>
                <p class="text-muted small mb-0">Reservasi Masuk</p>
            </div>
        </div>
    </div>

    <!-- Tabel Antrian (Data Real dari Database) -->
    <div class="row g-4">
        <div class="col-12">
            <div class="bg-white rounded-3 p-4 shadow-sm h-100 d-flex flex-column">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <h5 class="mb-0 fw-bold text-dark">Daftar Antrian Hari Ini</h5>
                </div>
                
                <div class="table-responsive flex-grow-1">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light text-muted small sticky-top">
                            <tr>
                                <th class="ps-3">No</th>
                                <th>Nama Pasien</th>
                                <th>Jam Rencana</th>
                                <th>Status</th>
                                <th class="text-end pe-3">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($antrianPasien as $antrian)
                            <tr>
                                <td class="ps-3">
                                    <!-- Menggunakan loop iteration jika kolom nomor_antrian tidak ada -->
                                    <span class="badge bg-light text-dark border">{{ $antrian->nomor_antrian ?? $loop->iteration }}</span>
                                </td>
                                <td>
                                    <!-- Mengambil nama dari relasi Pasien -->
                                    <h6 class="mb-0 text-dark small fw-bold">{{ $antrian->pasien->nama ?? 'Nama Tidak Ditemukan' }}</h6>
                                    <small class="text-muted" style="font-size: 11px;">
                                        Keluhan: {{ $antrian->keluhan_awal ?? '-' }}
                                    </small>
                                </td>
                                <td>
                                    <!-- Format Jam dari tgl_kunjungan -->
                                    {{ \Carbon\Carbon::parse($antrian->tgl_kunjungan)->format('H:i') }} WIB
                                </td>
                                <td>
                                    <!-- Logika Warna Status -->
                                    @php
                                        // Normalisasi status ke huruf kecil untuk pengecekan
                                        $status = strtolower($antrian->status);
                                    @endphp

                                    @if($status == 'menunggu')
                                        <span class="badge bg-warning text-dark">Menunggu</span>
                                    @elseif($status == 'diperiksa')
                                        <span class="badge bg-primary">Diperiksa</span>
                                    @elseif($status == 'selesai')
                                        <span class="badge bg-success">Selesai</span>
                                    @elseif($status == 'batal')
                                        <span class="badge bg-danger">Batal</span>
                                    @else
                                        <span class="badge bg-secondary">{{ $antrian->status }}</span>
                                    @endif
                                </td>
                                <td class="text-end pe-3">
                                    <!-- Tombol Aksi (Bisa diarahkan ke route periksa nanti) -->
                                    @if($status == 'menunggu' || $status == 'diperiksa')
                                        <a href="#" class="btn btn-sm btn-primary rounded-pill px-3">Periksa</a>
                                    @else
                                        <button class="btn btn-sm btn-light text-muted" disabled>Detail</button>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">
                                    <div class="d-flex flex-column align-items-center">
                                        <i class="fa fa-clipboard-list fa-3x mb-3 text-light"></i>
                                        <p class="mb-0">Belum ada pasien dalam antrian hari ini.</p>
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
</div>
@endsection