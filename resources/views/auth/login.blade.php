<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Klinik Gigi Semarang</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
     <link rel="stylesheet" href="{{ asset('assets/css/main.css') }}">
    
    </head>

<body class="login-body">

    <div class="bg-overlay"></div>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card card-login bg-white">
                    <div class="row g-0">
                        
                        <div class="col-md-6 p-5 d-flex flex-column justify-content-center">
                            <h2 class="mb-4 fw-bold text-custom">LOGIN</h2>
                            <p class="text-muted mb-4">Masuk sebagai Dokter, Admin, Kasir, atau Pasien.</p>
                            
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul class="mb-0">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <form action="{{ route('login') }}" method="POST">
                                @csrf 
                                
                                <div class="mb-3">
                                    <label for="email" class="form-label text-custom fw-bold">Email address</label>
                                    <input type="email" name="email" class="form-control" id="email" placeholder="email@example.com" required>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="password" class="form-label text-custom fw-bold">Password</label>
                                    <input type="password" name="password" class="form-control" id="password" placeholder="Password" required>
                                </div>
                                
                                <div class="mb-4 d-flex justify-content-between align-items-center">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="rememberMe">
                                        <label class="form-check-label text-secondary" for="rememberMe">
                                            Ingat Saya
                                        </label>
                                    </div>
                                    <a href="#" class="text-decoration-none small text-secondary">Lupa Password?</a>
                                </div>
                                
                                <button type="submit" class="btn btn-custom w-100">Sign in</button>
                                
                                <div class="mt-4 text-center">
                                    <p class="small text-secondary">Belum punya akun? <a href="{{ route('register') }}" class="text-custom fw-bold text-decoration-none">Daftar disini</a></p>
                                </div>
                            </form>
                        </div>

                        <div class="col-md-6 d-none d-md-block login-image">
                            </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>