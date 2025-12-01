<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>@yield('title', 'Pasien Dashboard')</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <!-- Favicon -->
    <link href="{{ asset('assets2/img/favicon.ico') }}" rel="icon">

    <!-- Fonts Google -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    
    <!-- Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Libraries -->
    <link href="{{ asset('assets2/lib/owlcarousel/assets/owl.carousel.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets2/lib/tempusdominus/css/tempusdominus-bootstrap-4.min.css') }}" rel="stylesheet" />

    <!-- Bootstrap & Template Style -->
    <link href="{{ asset('assets2/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets2/css/style.css') }}" rel="stylesheet">
    
    <!-- Custom CSS Pasien BARU -->
</head>

<body>
    <div class="container-xxl position-relative bg-white d-flex p-0">
        
        <!-- SIDEBAR -->
        <div class="sidebar pb-3">
            <nav class="navbar navbar-light">
                <a href="{{ route('pasien.dashboard') }}" class="navbar-brand mx-4 mb-3">
                    <h3 class="text-white"><i class="fa-solid fa-tooth me-2"></i>DentaPasien</h3>
                </a>
                
                <!-- Profil Pasien -->
                <div class="d-flex align-items-center ms-4 mb-4">
                    <div class="position-relative">
                        <img class="rounded-circle" src="https://placehold.co/100x100/A0D9FF/0056B3?text=P" alt="Pasien Avatar" style="width: 40px; height: 40px;">
                        <div class="bg-success rounded-circle border border-2 border-white position-absolute end-0 bottom-0 p-1"></div>
                    </div>
                    <div class="ms-3 text-white">
                        <h6 class="mb-0 text-white">{{ Auth::user()->name ?? 'Pasien' }}</h6>
                        <span>Pasien</span>
                    </div>
                </div>
                
                <!-- Menu Sidebar Pasien -->
                <div class="navbar-nav w-100">
                    <a href="{{ route('pasien.dashboard') }}" class="nav-item nav-link {{ Request::routeIs('pasien.dashboard') ? 'active' : '' }}">
                        <i class="fa-solid fa-house me-2"></i> Dashboard
                    </a>
                    <a href="{{ route('pasien.konsultasi.index') }}" class="nav-item nav-link {{ Request::routeIs('pasien.konsultasi.*') ? 'active' : '' }}">
                        <i class="fa-solid fa-calendar-check me-2"></i> Konsultasi
                    </a>
                    <a href="{{ route('pasien.jadwal.index') }}" class="nav-item nav-link {{ Request::routeIs('pasien.jadwal.*') ? 'active' : '' }}">
                        <i class="fa-regular fa-clock me-2"></i> Jadwal Praktik
                    </a>
                    <a href="{{ route('pasien.rekam.index') }}" class="nav-item nav-link {{ Request::routeIs('pasien.rekam.*') ? 'active' : '' }}">
                        <i class="fa-solid fa-file-medical me-2"></i> Rekam Medis
                    </a>
                    <a href="{{ route('pasien.pembayaran.index') }}" class="nav-item nav-link {{ Request::routeIs('pasien.pembayaran.*') ? 'active' : '' }}">
                        <i class="fa-solid fa-wallet me-2"></i> Pembayaran
                    </a>
                </div>
            </nav>
        </div>
        
        <!-- CONTENT -->
        <div class="content">
            
            <!-- NAVBAR ATAS -->
            <nav class="navbar navbar-expand bg-white navbar-light sticky-top ps-0 pe-4 py-0 shadow-sm">
                <a href="#" class="sidebar-toggler flex-shrink-0 text-primary ms-0">
                    <i class="fa fa-bars"></i>
                </a>
                
                <div class="navbar-nav align-items-center ms-auto">
                    <!-- Nav Item: Waktu Sekarang -->
                    <div class="nav-item d-none d-md-flex align-items-center me-3">
                        <i class="far fa-clock text-primary me-2"></i>
                        <span class="small text-muted" id="live-time">Loading...</span>
                    </div>

                    <!-- Profil Dropdown -->
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                            <img class="rounded-circle me-lg-2" src="https://placehold.co/100x100/A0D9FF/0056B3?text=P" alt="Pasien Avatar" style="width: 40px; height: 40px;">
                            <span class="d-none d-lg-inline-flex text-dark">{{ Auth::user()->name ?? 'Pasien' }}</span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end bg-white border-0 shadow-sm rounded-0 rounded-bottom m-0">
                            <a href="#" class="dropdown-item">My Profile</a>
                            <a href="#" class="dropdown-item">Settings</a>
                            <hr class="dropdown-divider">
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger">Log Out</button>
                            </form>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- KONTEN UTAMA -->
            <div class="container-fluid pt-4 px-4">
                @yield('content')
            </div>

            <!-- FOOTER -->
            <div class="container-fluid pt-4 px-4">
                <div class="bg-light rounded-top p-4 mt-3">
                    <div class="row">
                        <div class="col-12 text-center text-muted small">
                            &copy; <a href="#" class="text-primary fw-bold">Klinik Gigi Semarang</a>, All Right Reserved.
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
    
    <!-- Skrip utama template (main.js) -->
    <script src="{{ asset('assets2/js/main.js') }}?v=1.1"></script>
    
    <!-- Skrip Kustom Pasien BARU -->

    <!-- @stack('scripts') -->
    
</body>
</html>