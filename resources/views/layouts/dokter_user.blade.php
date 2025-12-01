<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Dashboard Dokter - DentaCare</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <!-- Favicon -->
    <link href="{{ asset('assets2/img/favicon.ico') }}" rel="icon">

    <!-- Fonts Google -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Libraries -->
    <link href="{{ asset('assets2/lib/owlcarousel/assets/owl.carousel.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets2/lib/tempusdominus/css/tempusdominus-bootstrap-4.min.css') }}" rel="stylesheet" />

    <!-- Bootstrap & Template Style -->
    <link href="{{ asset('assets2/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets2/css/style.css') }}" rel="stylesheet">
    
    <!-- Custom CSS Dokter (Dipisah) -->
    <link href="{{ asset('assets2/css/custom_dokter.css') }}" rel="stylesheet">
</head>

<body>
    <div class="container-xxl position-relative bg-white d-flex p-0">
        
        <!-- SIDEBAR DOKTER -->
        <div class="sidebar pb-3 bg-dark-green"> 
            <nav class="navbar navbar-light">
                <a href="{{ url('/dokter/dashboard') }}" class="navbar-brand mx-4 mb-3">
                    <h3 class="text-white"><i class="fa-solid fa-user-doctor me-2"></i>DentaDoc</h3>
                </a>
                
                <!-- Profil Dokter -->
                <div class="d-flex align-items-center ms-4 mb-4">
                    <div class="position-relative">
                        <img class="rounded-circle" src="https://ui-avatars.com/api/?name={{ Auth::user()->name ?? 'Dokter' }}&background=white&color=198754" alt="Avatar" style="width: 40px; height: 40px;">
                        <div class="bg-success rounded-circle border border-2 border-white position-absolute end-0 bottom-0 p-1"></div>
                    </div>
                    <div class="ms-3 text-white">
                        <h6 class="mb-0 text-white">dr. {{ Auth::user()->name ?? 'Fulan' }}</h6>
                        <span class="small text-white-50">Dokter Gigi Umum</span>
                    </div>
                </div>
                
                <!-- Menu Sidebar Dokter -->
                <div class="navbar-nav w-100">
                    <a href="{{ url('/dokter/dashboard') }}" class="nav-item nav-link active">
                        <i class="fa-solid fa-house me-2"></i> Dashboard
                    </a>
                    
                    <a href="#" class="nav-item nav-link">
                        <i class="fa-solid fa-calendar-days me-2"></i> Jadwal Saya
                    </a>
                    
                    <a href="#" class="nav-item nav-link">
                        <i class="fa-solid fa-stethoscope me-2"></i> Periksa Pasien
                    </a>

                    <a href="#" class="nav-item nav-link">
                        <i class="fa-solid fa-clock-rotate-left me-2"></i> Riwayat Pasien
                    </a>

                    <a href="#" class="nav-item nav-link">
                        <i class="fa-solid fa-prescription-bottle-medical me-2"></i> Resep Obat
                    </a>
                </div>
            </nav>
        </div>
        
        <!-- CONTENT WRAPPER -->
        <div class="content">
            
            <!-- NAVBAR ATAS -->
            <nav class="navbar navbar-expand bg-white navbar-light sticky-top ps-0 pe-4 py-0 shadow-sm">
                <a href="#" class="sidebar-toggler flex-shrink-0 text-success ms-0">
                    <i class="fa fa-bars"></i>
                </a>
                
                <div class="navbar-nav align-items-center ms-auto">
                    <!-- Waktu -->
                    <div class="nav-item d-none d-md-flex align-items-center me-3">
                        <i class="far fa-clock text-success me-2"></i>
                        <span class="small text-muted fw-bold" id="live-time">Loading...</span>
                    </div>

                    <!-- Profil Dropdown -->
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                            <img class="rounded-circle me-lg-2" src="https://ui-avatars.com/api/?name={{ Auth::user()->name ?? 'Dokter' }}&background=random" alt="" style="width: 40px; height: 40px;">
                            <span class="d-none d-lg-inline-flex text-dark">dr. {{ Auth::user()->name ?? 'Fulan' }}</span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end bg-white border-0 shadow-sm rounded-0 rounded-bottom m-0">
                            <a href="#" class="dropdown-item">Profil Saya</a>
                            <a href="#" class="dropdown-item">Pengaturan</a>
                            <hr class="dropdown-divider">
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger">Log Out</button>
                            </form>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- KONTEN UTAMA (DASHBOARD) -->
            <div class="container-fluid pt-4 px-4">
                
                <!-- Welcome Banner -->
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="bg-white rounded-3 p-4 shadow-sm d-flex align-items-center justify-content-between border-start border-4 border-success">
                            <div>
                                <h4 class="text-success fw-bold mb-1">Selamat Bertugas, dr. {{ Auth::user()->name ?? 'Fulan' }}!</h4>
                                <p class="text-muted mb-0">Semoga hari ini berjalan lancar. Berikut ringkasan pasien Anda.</p>
                            </div>
                            <div class="d-none d-md-block text-end">
                                <h6 class="text-dark mb-0">{{ \Carbon\Carbon::now()->isoFormat('dddd, D MMMM Y') }}</h6>
                                <small class="text-muted">Status Poliklinik: <span class="badge bg-success bg-opacity-75 rounded-pill">Aktif</span></small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Statistik Cards -->
                <div class="row g-4 mb-4">
                    <!-- Card 1 -->
                    <div class="col-sm-6 col-xl-3">
                        <div class="card-clean h-100">
                            <h5>Pasien Hari Ini</h5>
                            <h2>{{ $pasienHariIni ?? '12' }}</h2>
                            <p class="text-muted small mb-0">Total Terdaftar</p>
                            <i class="fa-solid fa-hospital-user card-icon-bg"></i>
                        </div>
                    </div>

                    <!-- Card 2 -->
                    <div class="col-sm-6 col-xl-3">
                        <div class="card-clean h-100">
                            <h5>Menunggu</h5>
                            <h2 class="text-warning">{{ $pasienMenunggu ?? '4' }}</h2>
                            <p class="text-muted small mb-0">Belum Diperiksa</p>
                            <i class="fa-solid fa-hourglass-half card-icon-bg text-warning"></i>
                        </div>
                    </div>

                    <!-- Card 3 -->
                    <div class="col-sm-6 col-xl-3">
                        <div class="card-clean h-100">
                            <h5>Selesai</h5>
                            <h2 class="text-success">{{ $pasienSelesai ?? '8' }}</h2>
                            <p class="text-muted small mb-0">Sudah Diperiksa</p>
                            <i class="fa-solid fa-circle-check card-icon-bg text-success"></i>
                        </div>
                    </div>

                    <!-- Card 4 -->
                    <div class="col-sm-6 col-xl-3">
                        <div class="card-clean h-100">
                            <h5>Jadwal Besok</h5>
                            <h3 class="fw-bold text-primary mb-0">{{ $jadwalBesok ?? '5' }}</h3>
                            <p class="text-muted small mb-0">Pasien Booking</p>
                            <i class="fa-solid fa-calendar-day card-icon-bg text-primary"></i>
                        </div>
                    </div>
                </div>

                <!-- Grafik & Tabel Antrian -->
                <div class="row g-4">
                    <!-- Grafik Kunjungan (Kiri) -->
                    <div class="col-sm-12 col-xl-7">
                        <div class="bg-white rounded-3 p-4 shadow-sm h-100">
                            <div class="d-flex align-items-center justify-content-between mb-4">
                                <h5 class="mb-0 fw-bold text-dark">Statistik Pasien Mingguan</h5>
                                <select class="form-select form-select-sm w-auto border-0 bg-light text-muted">
                                    <option>Minggu Ini</option>
                                    <option>Bulan Ini</option>
                                </select>
                            </div>
                            <!-- Canvas untuk Chart.js -->
                            <canvas id="kunjungan-chart" height="150"></canvas>
                        </div>
                    </div>

                    <!-- Tabel Antrian (Kanan) -->
                    <div class="col-sm-12 col-xl-5">
                        <div class="bg-white rounded-3 p-4 shadow-sm h-100">
                            <div class="d-flex align-items-center justify-content-between mb-4">
                                <h5 class="mb-0 fw-bold text-dark">Antrian Saat Ini</h5>
                                <a href="#" class="btn btn-sm btn-outline-success rounded-pill px-3">Lihat Semua</a>
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
                                        <!-- Dummy Data untuk Preview -->
                                        @forelse($antrianPasien ?? [] as $antrian)
                                        <tr>
                                            <td><span class="badge bg-secondary">{{ $antrian->nomor_antrian }}</span></td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <img src="https://ui-avatars.com/api/?name={{ $antrian->name }}" class="rounded-circle me-2" style="width: 35px; height: 35px;">
                                                    <div>
                                                        <h6 class="mb-0 text-dark small fw-bold">{{ $antrian->name }}</h6>
                                                        <small class="text-muted" style="font-size: 11px;">{{ $antrian->jenis_keluhan ?? 'Pemeriksaan Rutin' }}</small>
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
                                        <!-- Data Statis jika variabel kosong -->
                                        <tr>
                                            <td><span class="badge bg-secondary">A001</span></td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <img src="https://ui-avatars.com/api/?name=Budi+Santoso" class="rounded-circle me-2" style="width: 35px; height: 35px;">
                                                    <div>
                                                        <h6 class="mb-0 text-dark small fw-bold">Budi Santoso</h6>
                                                        <small class="text-muted" style="font-size: 11px;">Sakit Gigi Berlubang</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-end">
                                                <button class="btn btn-sm btn-success rounded-pill px-3">
                                                    <i class="fa fa-stethoscope me-1"></i> Periksa
                                                </button>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><span class="badge bg-secondary">A002</span></td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <img src="https://ui-avatars.com/api/?name=Siti+Aminah" class="rounded-circle me-2" style="width: 35px; height: 35px;">
                                                    <div>
                                                        <h6 class="mb-0 text-dark small fw-bold">Siti Aminah</h6>
                                                        <small class="text-muted" style="font-size: 11px;">Scaling Karang Gigi</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-end">
                                                <button class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                                    <i class="fa fa-stethoscope me-1"></i> Panggil
                                                </button>
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

            <!-- FOOTER -->
            <div class="container-fluid pt-4 px-4">
                <div class="bg-light rounded-top p-4 mt-3">
                    <div class="row">
                        <div class="col-12 text-center text-muted small">
                            &copy; <a href="#" class="text-success fw-bold">Klinik Gigi Semarang</a>, All Right Reserved.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- JS Libraries -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script> 
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('assets2/lib/chart/chart.min.js') }}"></script>
    <script src="{{ asset('assets2/lib/easing/easing.min.js') }}"></script>
    <script src="{{ asset('assets2/lib/waypoints/waypoints.min.js') }}"></script>
    <script src="{{ asset('assets2/lib/owlcarousel/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('assets2/lib/tempusdominus/js/moment.min.js') }}"></script>
    <script src="{{ asset('assets2/lib/tempusdominus/js/moment-timezone.min.js') }}"></script>
    <script src="{{ asset('assets2/lib/tempusdominus/js/tempusdominus-bootstrap-4.min.js') }}"></script>
    
    <!-- Skrip Utama Template -->
    <script src="{{ asset('assets2/js/main.js') }}"></script>

    <!-- Skrip Khusus Grafik Dokter (Dipisah) -->
    <script src="{{ asset('assets2/js/custom_dokter.js') }}"></script>
</body>
</html>