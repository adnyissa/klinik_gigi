@extends('layouts.kasir_user')

@section('title', 'Dashboard Kasir')

@section('content')
    
    <!-- 1. KARTU SAMBUTAN -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="kasir-welcome-card">
                <i class="fa-solid fa-cash-register kasir-bg-icon"></i>
                <div class="position-relative z-1">
                    <h4 class="fw-bold">Halo, {{ Auth::user()->name ?? 'Kasir' }}!</h4>
                    <p class="mb-0 opacity-75">Selamat bertugas di Shift {{ Auth::user()->kasir->shift_kerja ?? 'Pagi' }}. Semangat melayani transaksi hari ini!</p>
                </div>
            </div>
        </div>
    </div>

    <!-- 2. STATISTIK RINGKASAN -->
    <div class="row g-4 mb-4">
        <!-- Transaksi Hari Ini -->
        <div class="col-sm-6 col-xl-3">
            <div class="bg-light rounded d-flex align-items-center justify-content-between p-4 kasir-stat-card shadow-sm h-100">
                <div class="ms-3">
                    <p class="mb-2 text-muted small fw-bold text-uppercase">Transaksi Hari Ini</p>
                    <h4 class="mb-0 fw-bold text-success">15</h4>
                </div>
                <div class="kasir-stat-icon bg-success text-white">
                    <i class="fa-solid fa-receipt"></i>
                </div>
            </div>
        </div>

        <!-- Menunggu Pembayaran -->
        <div class="col-sm-6 col-xl-3">
            <div class="bg-light rounded d-flex align-items-center justify-content-between p-4 kasir-stat-card shadow-sm h-100" style="border-left-color: #ffc107;">
                <div class="ms-3">
                    <p class="mb-2 text-muted small fw-bold text-uppercase">Antrian Bayar</p>
                    <h4 class="mb-0 fw-bold text-warning">3</h4>
                </div>
                <div class="kasir-stat-icon bg-warning text-dark">
                    <i class="fa-solid fa-user-clock"></i>
                </div>
            </div>
        </div>

        <!-- Total Pendapatan -->
        <div class="col-sm-6 col-xl-3">
            <div class="bg-light rounded d-flex align-items-center justify-content-between p-4 kasir-stat-card shadow-sm h-100" style="border-left-color: #0d6efd;">
                <div class="ms-3">
                    <p class="mb-2 text-muted small fw-bold text-uppercase">Pendapatan (Est)</p>
                    <h4 class="mb-0 fw-bold text-primary">Rp 2.5jt</h4>
                </div>
                <div class="kasir-stat-icon bg-primary text-white">
                    <i class="fa-solid fa-wallet"></i>
                </div>
            </div>
        </div>

        <!-- Shift Info -->
        <div class="col-sm-6 col-xl-3">
            <div class="bg-light rounded d-flex align-items-center justify-content-between p-4 kasir-stat-card shadow-sm h-100" style="border-left-color: #6c757d;">
                <div class="ms-3">
                    <p class="mb-2 text-muted small fw-bold text-uppercase">Shift Kerja</p>
                    <h5 class="mb-0 fw-bold text-secondary">{{ Auth::user()->kasir->shift_kerja ?? 'Pagi' }}</h5>
                </div>
                <div class="kasir-stat-icon bg-secondary text-white">
                    <i class="fa-solid fa-clock"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- 3. TABEL ANTRIAN PEMBAYARAN -->
    <div class="row g-4">
        <div class="col-12">
            <div class="bg-light rounded h-100 p-4 shadow-sm">
                <div class="d-flex align-items-center justify-content-between mb-4">
                    <h5 class="mb-0 text-success fw-bold"><i class="fa-solid fa-list-ol me-2"></i>Antrian Pembayaran Terkini</h5>
                    <a href="#" class="btn btn-sm btn-outline-success">Lihat Semua</a>
                </div>
                
                <div class="table-responsive">
                    <table class="table table-hover align-middle table-kasir">
                        <thead class="rounded-top">
                            <tr>
                                <th scope="col" class="ps-3 border-0 rounded-start">No. RM</th>
                                <th scope="col" class="border-0">Nama Pasien</th>
                                <th scope="col" class="border-0">Layanan/Tindakan</th>
                                <th scope="col" class="border-0">Total Tagihan</th>
                                <th scope="col" class="border-0">Status</th>
                                <th scope="col" class="border-0 rounded-end text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Contoh Data Statis (Nanti diganti Foreach) -->
                            <tr>
                                <td class="ps-3 fw-bold">RM-00123</td>
                                <td>Budi Santoso</td>
                                <td>Cabut Gigi, Pembersihan Karang</td>
                                <td class="fw-bold text-dark">Rp 450.000</td>
                                <td><span class="badge-status-pending">Menunggu</span></td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-success rounded-pill px-3 shadow-sm">
                                        <i class="fa-solid fa-money-bill-wave me-1"></i> Proses
                                    </button>
                                </td>
                            </tr>
                            <tr>
                                <td class="ps-3 fw-bold">RM-00125</td>
                                <td>Siti Aminah</td>
                                <td>Tambal Gigi Permanen</td>
                                <td class="fw-bold text-dark">Rp 300.000</td>
                                <td><span class="badge-status-pending">Menunggu</span></td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-success rounded-pill px-3 shadow-sm">
                                        <i class="fa-solid fa-money-bill-wave me-1"></i> Proses
                                    </button>
                                </td>
                            </tr>
                            <tr>
                                <td class="ps-3 fw-bold">RM-00120</td>
                                <td>Andi Wijaya</td>
                                <td>Konsultasi</td>
                                <td class="fw-bold text-dark">Rp 100.000</td>
                                <td><span class="badge-status-lunas">Lunas</span></td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-outline-secondary rounded-pill px-3" disabled>
                                        <i class="fa-solid fa-check me-1"></i> Selesai
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

@endsection