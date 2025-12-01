@extends('layouts.admin')

@section('title', 'Dashboard Admin')

@section('content')

<div class="row mb-4">
    <div class="col-12">
        <div class="bg-white rounded-3 p-4 shadow-sm d-flex align-items-center justify-content-between border-start border-4 border-info">
            <div>
                <h4 class="text-primary fw-bold mb-1">Selamat Datang, {{ Auth::user()->name }}!</h4>
                <p class="text-muted mb-0">Ini adalah ringkasan aktivitas klinik hari ini.</p>
            </div>
            <div class="d-none d-md-block text-end">
                <h6 class="text-dark mb-0">{{ \Carbon\Carbon::now()->isoFormat('dddd, D MMMM Y') }}</h6>
                <small class="text-muted">Status Klinik: <span class="badge bg-success bg-opacity-75 rounded-pill">Buka</span></small>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-sm-6 col-xl-3">
        <div class="card-clean h-100">
            <h5>Total Pasien</h5>
            <h2>{{ $totalPasien }}</h2>
            <p class="text-muted small mb-0">Orang Terdaftar</p>
            <i class="fa-solid fa-hospital-user card-icon-bg"></i>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="card-clean h-100">
            <h5>Total Dokter</h5>
            <h2>{{ $totalDokter }}</h2>
            <p class="text-muted small mb-0">Dokter Aktif</p>
            <i class="fa-solid fa-user-doctor card-icon-bg"></i>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="card-clean h-100">
            <h5>Janji Temu</h5>
            <h2>{{ $janjiHariIni }}</h2>
            <p class="text-muted small mb-0">Antrian Hari Ini</p>
            <i class="fa-solid fa-calendar-check card-icon-bg"></i>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="card-clean h-100">
            <h5>Pendapatan</h5>
            <h3 class="fw-bold text-primary mb-0">Rp {{ number_format($pendapatanHariIni, 0, ',', '.') }}</h3>
            <p class="text-muted small mb-0">Pemasukan Hari Ini</p>
            <i class="fa-solid fa-wallet card-icon-bg"></i>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-sm-12 col-xl-7">
        <div class="bg-white rounded-3 p-4 shadow-sm h-100">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <h5 class="mb-0 fw-bold text-dark">Grafik Kunjungan</h5>
                <select class="form-select form-select-sm w-auto border-0 bg-light text-muted">
                    <option>Bulan Ini</option>
                </select>
            </div>
            <canvas id="kunjungan-chart" height="150"></canvas>
        </div>
    </div>

    <div class="col-sm-12 col-xl-5">
        <div class="bg-white rounded-3 p-4 shadow-sm h-100">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <h5 class="mb-0 fw-bold text-dark">Pasien Baru</h5>
                <a href="#" class="btn btn-sm btn-outline-primary rounded-pill px-3">Lihat Semua</a>
            </div>
            
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="bg-light text-muted small">
                        <tr>
                            <th class="border-0 rounded-start">Nama Pasien</th>
                            <th class="border-0">Daftar</th>
                            <th class="border-0 rounded-end text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pasienTerbaru as $p)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <img src="{{ asset('assets2/img/user.jpg') }}" class="rounded-circle me-2" style="width: 35px; height: 35px;">
                                    <div>
                                        <h6 class="mb-0 text-dark small fw-bold">{{ $p->nama }}</h6>
                                        <small class="text-muted" style="font-size: 11px;">{{ $p->nik }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark fw-normal">{{ $p->created_at->format('d M') }}</span>
                            </td>
                            <td class="text-end">
                                <a href="{{ route('admin.pasien.index') }}" class="btn btn-sm btn-light text-primary" title="Lihat Detail Pasien">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="text-center py-4 text-muted">
                                Belum ada data pasien terbaru.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="{{ asset('assets2/js/main.js') }}"></script>
<script src="{{ asset('assets2/js/custom.js') }}"></script>

@endsection