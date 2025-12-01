<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>@yield('title', 'Admin Dashboard')</title>
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

    <!-- Bootstrap -->
    <link href="{{ asset('assets2/css/bootstrap.min.css') }}" rel="stylesheet">
    <!-- Template Style -->
    <link href="{{ asset('assets2/css/style.css') }}" rel="stylesheet">
    <!-- Custom CSS -->
</head>

<body>
    <div class="container-xxl position-relative bg-white d-flex p-0">
        
        <!-- SIDEBAR -->
        <div class="sidebar pb-3">
            <nav class="navbar navbar-light">
                <a href="#" class="navbar-brand mx-4 mb-3">
                    <h3 class="text-white"><i class="fa-solid fa-tooth me-2"></i>DentaCare</h3>
                </a>
                
                <!-- Profil Admin -->
                <div class="d-flex align-items-center ms-4 mb-4">
                    <div class="position-relative">
                        <img class="rounded-circle" src="{{ asset('assets2/img/user.jpg') }}" alt="">
                        <div class="bg-success rounded-circle border border-2 border-white position-absolute end-0 bottom-0 p-1"></div>
                    </div>
                    <div class="ms-3 text-white">
                        <h6 class="mb-0">{{ Auth::user()->name }}</h6>
                        <span>Admin</span>
                    </div>
                </div>
                
                <!-- Menu Sidebar -->
                <div class="navbar-nav w-100">
                    <a href="{{ url('/admin/dashboard') }}" class="nav-item nav-link {{ Request::is('admin/dashboard') ? 'active' : '' }}">
                        <i class="fa-solid fa-gauge-high"></i> Dashboard
                    </a>
                    <a href="{{ route('admin.pasien.index') }}" class="nav-item nav-link {{ Request::is('admin/pasien*') ? 'active' : '' }}">
                        <i class="fa-solid fa-hospital-user"></i> Pasien
                    </a>
                    <a href="{{ route('admin.dokter.index') }}" class="nav-item nav-link {{ Request::is('admin/dokter*') ? 'active' : '' }}">
                        <i class="fa-solid fa-user-doctor"></i> Data Dokter
                    </a>
                    <a href="{{ route('admin.kasir.index') }}" class="nav-item nav-link {{ Request::is('admin/kasir*') ? 'active' : '' }}">
                        <i class="fa-solid fa-cash-register"></i> Kasir
                    </a>
                    <a href="{{ route('admin.jadwal.index') }}" class="nav-item nav-link {{ Request::is('admin/jadwal*') ? 'active' : '' }}">
                        <i class="fa-solid fa-calendar-days"></i> Jadwal
                    </a>
                     <a href="{{ route('admin.rm.index') }}" class="nav-item nav-link {{ Request::is('admin/rm*') ? 'active' : '' }}">
                        <i class="fa-solid fa-file-medical"></i> Rekam Medis
                     </a> 
                    <a href="{{ route('admin.pembayaran.index') }}" class="nav-item nav-link {{ Request::is('admin/pembayaran*') ? 'active' : '' }}">
                         <i class="fa-solid fa-wallet"></i> Pembayaran
                    </a>
                    
                </div>
            </nav>
        </div>
        
        <!-- CONTENT -->
        <div class="content">
            
            <!-- NAVBAR ATAS -->
            <nav class="navbar navbar-expand bg-white navbar-light sticky-top ps-0 pe-4 py-0 shadow-sm">
                
                <!-- TOMBOL TOGGLER -->
                <a href="#" class="sidebar-toggler flex-shrink-0 text-primary ms-0">
                    <i class="fa fa-bars"></i>
                </a>

                <!-- Profil Dropdown -->
                <div class="navbar-nav align-items-center ms-auto">
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                            <img class="rounded-circle me-lg-2" src="{{ asset('assets2/img/user.jpg') }}" alt="">
                            <span class="d-none d-lg-inline-flex text-dark">{{ Auth::user()->name ?? 'Administrator' }}</span>
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

    <!-- JS Libraries (Sudah DIBERSIHKAN dan DIURUTKAN) -->
    
    <!-- 1. JQUERY (WAJIB PERTAMA!) -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script> 

    <!-- 2. Bootstrap & Libraries Lain -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('assets2/lib/chart/chart.min.js') }}"></script>
    <script src="{{ asset('assets2/lib/easing/easing.min.js') }}"></script>
    <script src="{{ asset('assets2/lib/waypoints/waypoints.min.js') }}"></script>
    <script src="{{ asset('assets2/lib/owlcarousel/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('assets2/lib/tempusdominus/js/moment.min.js') }}"></script>
    <script src="{{ asset('assets2/lib/tempusdominus/js/moment-timezone.min.js') }}"></script>
    <script src="{{ asset('assets2/lib/tempusdominus/js/tempusdominus-bootstrap-4.min.js') }}"></script>
    
    <!-- 3. Skrip utama Anda (main.js) -->
    <script src="{{ asset('assets2/js/main.js') }}?v=1.1"></script>

    <!-- 4. SINI TEMPAT FILE DARI @push('scripts') -->
    @stack('scripts')
    
</body>
</html>