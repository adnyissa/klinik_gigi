@extends('layouts.dokter_user')

@section('title', 'Dashboard Dokter')

@push('styles')
    {{-- 1. Custom CSS File --}}
    <link href="{{ asset('assets2/css/style.css') }}" rel="stylesheet">

   
@endpush

@section('content')
<div class="container-fluid pt-4 px-4">
    
    <div class="row mb-4">
        <div class="col-12">
            {{-- Tambahkan class 'welcome-banner' yang didefinisikan di style atas --}}
            <div class="bg-white rounded-3 p-4 shadow-lg d-flex align-items-center justify-content-between welcome-banner">
                <div>
                    <h4 class="text-primary fw-bold mb-1">Selamat Bertugas, dr. {{ Auth::user()->name ?? 'Dokter' }}!</h4>
                    <p class="text-muted mb-0">Semoga hari ini menyenangkan. Anda memiliki <strong class="text-dark">{{ $pasienMenunggu ?? 0 }} pasien</strong> menunggu.</p>
                </div>
                <div class="d-none d-md-block text-end">
                    <h6 class="text-dark mb-0">{{ \Carbon\Carbon::now()->isoFormat('dddd, D MMMM Y') }}</h6>
                    <div class="mt-1">
                        {{-- Mengganti fa-user-md dengan fa-tooth untuk Dokter Gigi (membutuhkan Font Awesome 6) --}}
                        <span class="badge bg-primary rounded-pill"><i class="fa-solid fa-tooth me-1"></i>Dokter Gigi</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <hr>

    <div class="row g-4 mb-4">
        {{-- Card 1: Pasien Hari Ini (Border Netral) --}}
        <div class="col-sm-6 col-xl-3">
            <div class="card-clean h-100 bg-white p-3 rounded shadow-sm border">
                <h5 class="text-muted small">PASIEN HARI INI</h5>
                <h2 class="mb-0 fw-bold text-dark">{{ $pasienHariIni ?? 0 }}</h2>
                <p class="text-muted small mb-0">Total Terdaftar</p>
            </div>
        </div>
        {{-- Card 2: Menunggu (Border Warning) --}}
        <div class="col-sm-6 col-xl-3">
            <div class="card-clean h-100 bg-white p-3 rounded shadow-sm border border-warning">
                <h5 class="text-muted small">MENUNGGU</h5>
                <h2 class="text-warning mb-0 fw-bold">{{ $pasienMenunggu ?? 0 }}</h2>
                <p class="text-muted small mb-0">Perlu Tindakan</p>
            </div>
        </div>
        {{-- Card 3: Selesai (Border Success) --}}
        <div class="col-sm-6 col-xl-3">
            <div class="card-clean h-100 bg-white p-3 rounded shadow-sm border border-success">
                <h5 class="text-muted small">SELESAI</h5>
                <h2 class="text-success mb-0 fw-bold">{{ $pasienSelesai ?? 0 }}</h2>
                <p class="text-muted small mb-0">Sudah Pulang</p>
            </div>
        </div>
        {{-- Card 4: Jadwal Besok (Border Info) --}}
        <div class="col-sm-6 col-xl-3">
            <div class="card-clean h-100 bg-white p-3 rounded shadow-sm border border-info">
                <h5 class="text-muted small">JADWAL BESOK</h5>
                <h3 class="fw-bold text-info mb-0">{{ $jadwalBesok ?? 0 }}</h3>
                <p class="text-muted small mb-0">Reservasi Masuk</p>
            </div>
        </div>
    </div>
    
    <hr>

    {{-- Grafik & Tabel Antrian --}}
    <div class="row g-4">
        <div class="col-sm-12 col-xl-7">
            <div class="bg-white rounded-3 p-4 shadow-sm h-100">
                <div class="d-flex align-items-center justify-content-between mb-4">
                    <h5 class="mb-0 fw-bold text-dark">Statistik Pasien Mingguan</h5>
                    <select class="form-select form-select-sm w-auto border-0 bg-light text-muted">
                        <option>Minggu Ini</option>
                        <option>Bulan Ini</option>
                    </select>
                </div>
                <canvas id="kunjungan-chart" height="150"></canvas>
            </div>
        </div>

        <div class="col-sm-12 col-xl-5">
            <div class="bg-white rounded-3 p-4 shadow-sm h-100">
                <div class="d-flex align-items-center justify-content-between mb-4">
                    <h5 class="mb-0 fw-bold text-dark">Antrian Saat Ini</h5>
                    <a href="{{ route('dokter.periksa') }}" class="btn btn-sm btn-outline-success rounded-pill px-3">Lihat Semua</a>
                </div>
                
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="bg-light text-muted small">
                            <tr>
                                <th class="border-0 rounded-start">No</th>
                                <th class="border-0">Nama Pasien</th>
                                <th class="border-0 rounded-end text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($antrianPasien->take(5) as $antrian)
                            <tr>
                                <td><span class="badge bg-secondary">{{ $antrian->nomor_antrian ?? $loop->iteration }}</span></td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img src="https://ui-avatars.com/api/?name={{ $antrian->pasien->nama ?? 'Pasien' }}" class="rounded-circle me-2" style="width: 35px; height: 35px;">
                                        <div>
                                            <h6 class="mb-0 text-dark small fw-bold">{{ $antrian->pasien->nama ?? 'Nama Tidak Ditemukan' }}</h6>
                                            <small class="text-muted" style="font-size: 11px;">{{ $antrian->keluhan_awal ?? 'Pemeriksaan Rutin' }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-end">
                                    <button class="btn btn-sm btn-primary rounded-pill px-3">
                                        <i class="fa fa-stethoscope me-1"></i> Periksa
                                    </button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="text-center text-muted">Tidak ada antrian saat ini</td>
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

@push('scripts')
    {{-- Script Grafik Chart.js --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Grafik Chart.js
            const ctx = document.getElementById('kunjungan-chart');
            if (ctx) {
                const chart = new Chart(ctx.getContext('2d'), {
                    type: 'line',
                    data: {
                        labels: {!! json_encode($labels ?? ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab']) !!},
                        datasets: [{
                            label: 'Jumlah Pasien',
                            data: {!! json_encode($data ?? [0, 0, 0, 0, 0, 0]) !!},
                            borderColor: '#28a745',
                            backgroundColor: 'rgba(40, 167, 69, 0.2)',
                            fill: true,
                            tension: 0.4
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: { 
                            legend: { display: false } 
                        },
                        scales: { 
                            y: { beginAtZero: true } 
                        }
                    }
                });
            }

            // Notifikasi jika ada pasien menunggu
            var pasienMenunggu = parseInt("{{ $pasienMenunggu ?? 0 }}");
            if (pasienMenunggu > 0) {
                console.log(`Ada ${pasienMenunggu} pasien menunggu. Waktunya bekerja!`);
            }
        });
    </script>
@endpush