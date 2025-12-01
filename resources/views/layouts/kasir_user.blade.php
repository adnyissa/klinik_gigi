<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>@yield('title', 'Dashboard Kasir')</title>
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
    
    <!-- Custom CSS Kasir -->
</head>

<body>
    <div class="container-xxl position-relative bg-white d-flex p-0">
        
        <!-- SIDEBAR -->
        <div class="sidebar pb-3 bg-dark-green"> 
            <nav class="navbar navbar-light">
                <a href="{{ url('/kasir/dashboard') }}" class="navbar-brand mx-4 mb-3">
                    <h3 class="text-white"><i class="fa-solid fa-cash-register me-2"></i>DentaKasir</h3>
                </a>
                
                <!-- Profil Kasir -->
                <div class="d-flex align-items-center ms-4 mb-4">
                    <div class="position-relative">
                        <img class="rounded-circle" src="https://ui-avatars.com/api/?name={{ Auth::user()->name ?? 'Kasir' }}&background=28a745&color=fff" alt="Avatar" style="width: 40px; height: 40px;">
                        <div class="bg-success rounded-circle border border-2 border-white position-absolute end-0 bottom-0 p-1"></div>
                    </div>
                    <div class="ms-3 text-white">
                        <h6 class="mb-0 text-white">{{ Auth::user()->name ?? 'Kasir' }}</h6>
                        <span class="small text-white-50">Kasir Shift {{ Auth::user()->kasir->shift_kerja ?? 'Pagi' }}</span>
                    </div>
                </div>
                
                <!-- Menu Sidebar Kasir -->
                <div class="navbar-nav w-100">
                    <a href="{{ url('/kasir/dashboard') }}" class="nav-item nav-link {{ Request::is('kasir/dashboard') ? 'active' : '' }}">
                        <i class="fa-solid fa-house me-2"></i> Dashboard
                    </a>
                    
                    <a href="#" class="nav-item nav-link">
                        <i class="fa-solid fa-file-invoice-dollar me-2"></i> Pembayaran
                    </a>
                    
                    <a href="#" class="nav-item nav-link">
                        <i class="fa-solid fa-list-check me-2"></i> Riwayat Transaksi
                    </a>

                    <a href="#" class="nav-item nav-link">
                        <i class="fa-solid fa-print me-2"></i> Laporan Harian
                    </a>
                </div>
            </nav>
        </div>
        
        <!-- CONTENT -->
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
                            <img class="rounded-circle me-lg-2" src="https://ui-avatars.com/api/?name={{ Auth::user()->name ?? 'Kasir' }}&background=random" alt="" style="width: 40px; height: 40px;">
                            <span class="d-none d-lg-inline-flex text-dark">{{ Auth::user()->name ?? 'Kasir' }}</span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end bg-white border-0 shadow-sm rounded-0 rounded-bottom m-0">
                            <a href="#" class="dropdown-item">Profil Saya</a>
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
    
    <!-- Skrip Utama -->
    <script src="{{ asset('assets2/js/main.js') }}"></script>
    
    <!-- Skrip Khusus Kasir -->

    @stack('scripts')
</body>
</html>