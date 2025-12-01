@extends('layouts.admin')

@section('title', 'Dashboard Dokter')

@push('styles')
    <!-- Custom CSS Dokter -->
    <link href="{{ asset('assets2/css/custom_dokter.css') }}" rel="stylesheet">
@endpush

@section('content')
<div class="container-fluid pt-4 px-4">
    
    <!-- Welcome Banner & Status -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="bg-white rounded-3 p-4 shadow-sm d-flex align-items-center justify-content-between border-start border-4 border-success">
                <div>
                    <h4 class="text-success fw-bold mb-1">Selamat Bertugas, dr. {{ Auth::user()->name ?? 'Fulan' }}!</h4>
                    <p class="text-muted mb-0">Semoga hari ini menyenangkan. Anda memiliki <strong class="text-dark">{{ $pasienMenunggu ?? '4' }} pasien</strong> menunggu.</p>
                </div>
                <div class="d-none d-md-block text-end">
                    <h6 class="text-dark mb-0">{{ \Carbon\Carbon::now()->isoFormat('dddd, D MMMM Y') }}</h6>
                    <div class="mt-1">
                        <span class="badge bg-success bg-opacity-75 rounded-pill"><i class="fa fa-check-circle me-1"></i>Poli Gigi Umum</span>
                        <span class="badge bg-info bg-opacity-75 rounded-pill text-dark"><i class="fa fa-clock me-1"></i>Shift Pagi</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Highlight Pasien Sedang Diperiksa -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm bg-success text-white overflow-hidden" style="border-radius: 15px;">
                <div class="card-body p-4 position-relative">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <div class="d-flex align-items-center mb-2">
                                <span class="badge bg-white text-success me-2 px-3 py-2 rounded-pill fw-bold">SEDANG DIPERIKSA</span>
                                <small class="text-white-50"><i class="fa fa-clock me-1"></i> Mulai 10:15 WIB</small>
                            </div>
                            <h3 class="fw-bold mb-1 text-white">Tn. Ahmad Fauzi (A-012)</h3>
                            <p class="text-white-50 mb-0">Keluhan: Sakit gigi geraham bawah kanan, nyeri saat makan.</p>
                        </div>
                        <div class="col-md-4 text-md-end mt-3 mt-md-0">
                            <button class="btn btn-light text-success fw-bold rounded-pill px-4 py-2 shadow-sm me-2"><i class="fa fa-file-medical me-2"></i>Rekam Medis</button>
                            <button class="btn btn-outline-light rounded-pill px-4 py-2"><i class="fa fa-check me-2"></i>Selesai</button>
                        </div>
                    </div>
                    <!-- Background Decoration -->
                    <i class="fa fa-user-injured position-absolute" style="right: 20px; bottom: -20px; font-size: 150px; opacity: 0.1;"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistik Cards -->
    <div class="row g-4 mb-4">
        <div class="col-sm-6 col-xl-3">
            <div class="card-clean h-100">
                <h5>Pasien Hari Ini</h5>
                <div class="d-flex align-items-baseline">
                    <h2 class="mb-0">{{ $pasienHariIni ?? '12' }}</h2>
                    <span class="ms-2 text-success small"><i class="fa fa-arrow-up"></i> 10%</span>
                </div>
                <p class="text-muted small mb-0">Total Terdaftar</p>
                <i class="fa-solid fa-hospital-user card-icon-bg"></i>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card-clean h-100">
                <h5>Menunggu</h5>
                <h2 class="text-warning mb-0">{{ $pasienMenunggu ?? '4' }}</h2>
                <p class="text-muted small mb-0">Perlu Tindakan</p>
                <i class="fa-solid fa-hourglass-half card-icon-bg text-warning"></i>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card-clean h-100">
                <h5>Selesai</h5>
                <h2 class="text-success mb-0">{{ $pasienSelesai ?? '8' }}</h2>
                <p class="text-muted small mb-0">Sudah Pulang</p>
                <i class="fa-solid fa-circle-check card-icon-bg text-success"></i>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card-clean h-100">
                <h5>Jadwal Besok</h5>
                <h3 class="fw-bold text-primary mb-0">{{ $jadwalBesok ?? '5' }}</h3>
                <p class="text-muted small mb-0">Reservasi Masuk</p>
                <i class="fa-solid fa-calendar-day card-icon-bg text-primary"></i>
            </div>
        </div>
    </div>

    <!-- Grafik & Tabel Antrian -->
    <div class="row g-4">
        <!-- Grafik Kunjungan -->
        <div class="col-sm-12 col-xl-7">
            <div class="bg-white rounded-3 p-4 shadow-sm h-100">
                <div class="d-flex align-items-center justify-content-between mb-4">
                    <h5 class="mb-0 fw-bold text-dark">Tren Kunjungan Pasien</h5>
                    <div class="btn-group btn-group-sm">
                        <button type="button" class="btn btn-outline-secondary active">Mingguan</button>
                        <button type="button" class="btn btn-outline-secondary">Bulanan</button>
                    </div>
                </div>
                <canvas id="kunjungan-chart" height="150"></canvas>
            </div>
        </div>

        <!-- Tabel Antrian -->
        <div class="col-sm-12 col-xl-5">
            <div class="bg-white rounded-3 p-4 shadow-sm h-100 d-flex flex-column">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <h5 class="mb-0 fw-bold text-dark">Antrian Berikutnya</h5>
                    <a href="#" class="text-success small text-decoration-none fw-bold">Lihat Semua <i class="fa fa-arrow-right"></i></a>
                </div>
                
                <div class="table-responsive flex-grow-1">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light text-muted small sticky-top">
                            <tr>
                                <th class="border-0 rounded-start ps-3">No</th>
                                <th class="border-0">Pasien</th>
                                <th class="border-0">Status</th>
                                <th class="border-0 rounded-end text-end pe-3">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Dummy Data Antrian -->
                            @forelse($antrianPasien ?? [] as $antrian)
                            <tr>
                                <td class="ps-3"><span class="badge bg-light text-dark border">{{ $antrian->nomor_antrian }}</span></td>
                                <td>
                                    <h6 class="mb-0 text-dark small fw-bold">{{ $antrian->name }}</h6>
                                    <small class="text-muted" style="font-size: 11px;">10:30 WIB</small>
                                </td>
                                <td><span class="badge bg-warning bg-opacity-10 text-warning">Menunggu</span></td>
                                <td class="text-end pe-3">
                                    <button class="btn btn-sm btn-primary rounded-circle" title="Panggil"><i class="fa fa-bullhorn"></i></button>
                                </td>
                            </tr>
                            @empty
                            <!-- Data Statis 1 -->
                            <tr>
                                <td class="ps-3"><span class="badge bg-light text-dark border">A-013</span></td>
                                <td>
                                    <h6 class="mb-0 text-dark small fw-bold">Budi Santoso</h6>
                                    <small class="text-muted" style="font-size: 11px;">10:45 WIB</small>
                                </td>
                                <td><span class="badge bg-warning bg-opacity-10 text-warning">Menunggu</span></td>
                                <td class="text-end pe-3">
                                    <button class="btn btn-sm btn-primary rounded-circle" title="Panggil"><i class="fa fa-bullhorn"></i></button>
                                </td>
                            </tr>
                            <!-- Data Statis 2 -->
                            <tr>
                                <td class="ps-3"><span class="badge bg-light text-dark border">A-014</span></td>
                                <td>
                                    <h6 class="mb-0 text-dark small fw-bold">Siti Aminah</h6>
                                    <small class="text-muted" style="font-size: 11px;">11:00 WIB</small>
                                </td>
                                <td><span class="badge bg-secondary bg-opacity-10 text-secondary">Belum Hadir</span></td>
                                <td class="text-end pe-3">
                                    <button class="btn btn-sm btn-light text-muted rounded-circle" title="Skip"><i class="fa fa-step-forward"></i></button>
                                </td>
                            </tr>
                            <!-- Data Statis 3 -->
                            <tr>
                                <td class="ps-3"><span class="badge bg-light text-dark border">A-015</span></td>
                                <td>
                                    <h6 class="mb-0 text-dark small fw-bold">Doni Kurniawan</h6>
                                    <small class="text-muted" style="font-size: 11px;">11:15 WIB</small>
                                </td>
                                <td><span class="badge bg-secondary bg-opacity-10 text-secondary">Belum Hadir</span></td>
                                <td class="text-end pe-3">
                                    <button class="btn btn-sm btn-light text-muted rounded-circle" title="Skip"><i class="fa fa-step-forward"></i></button>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Riwayat Pasien Terakhir (Tambahan) -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="bg-white rounded-3 p-4 shadow-sm">
                <h5 class="mb-3 fw-bold text-dark">Riwayat Pemeriksaan Hari Ini</h5>
                <div class="table-responsive">
                    <table class="table table-bordered align-middle">
                        <thead class="bg-light">
                            <tr>
                                <th>Waktu</th>
                                <th>Nama Pasien</th>
                                <th>Diagnosa</th>
                                <th>Tindakan</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>09:00</td>
                                <td>Rina Wati</td>
                                <td>Pulpitis Reversibel</td>
                                <td>Tambal Gigi</td>
                                <td><span class="badge bg-success">Selesai</span></td>
                            </tr>
                            <tr>
                                <td>09:45</td>
                                <td>Joko Susilo</td>
                                <td>Gingivitis</td>
                                <td>Scaling</td>
                                <td><span class="badge bg-success">Selesai</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

@push('scripts')
    <!-- Skrip Khusus Grafik Dokter -->
    <script src="{{ asset('assets2/js/custom_dokter.js') }}"></script>
@endpush