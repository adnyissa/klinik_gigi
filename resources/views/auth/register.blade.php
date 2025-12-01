<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar - Klinik Gigi Semarang</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <link rel="stylesheet" href="{{ asset('assets/css/main.css') }}">
</head>

<body class="register-body">

    <div class="bg-overlay"></div>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card card-register bg-white">
                    <div class="row g-0">
                        
                        <div class="col-md-6 p-5">
                            <h2 class="mb-2 fw-bold text-custom">DAFTAR</h2>
                            <p class="text-muted mb-4">Buat akun baru untuk mulai konsultasi.</p>
                            
                            @if ($errors->any())
                                <div class="alert alert-danger py-2 small">
                                    <ul class="mb-0 ps-3">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <form action="{{ route('register') }}" method="POST">
                                @csrf 
                                
                                <div class="mb-3">
                                    <label for="name" class="form-label text-custom fw-bold">Nama Lengkap</label>
                                    <input type="text" name="name" class="form-control" id="name" placeholder="Nama Anda" value="{{ old('name') }}" required>
                                </div>

                                <div class="mb-3">
                                    <label for="email" class="form-label text-custom fw-bold">Email</label>
                                    <input type="email" name="email" class="form-control" id="email" placeholder="email@example.com" value="{{ old('email') }}" required>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="password" class="form-label text-custom fw-bold">Password</label>
                                    <input type="password" name="password" class="form-control" id="password" placeholder="Minimal 8 karakter" required>
                                </div>

                                <div class="mb-4">
                                    <label for="password_confirmation" class="form-label text-custom fw-bold">Ulangi Password</label>
                                    <input type="password" name="password_confirmation" class="form-control" id="password_confirmation" placeholder="Ketik ulang password" required>
                                </div>
                                
                                <button type="submit" class="btn btn-custom w-100">Daftar Sekarang</button>
                                
                                <div class="mt-4 text-center">
                                    <p class="small text-secondary">Sudah punya akun? <a href="{{ route('login') }}" class="text-custom fw-bold text-decoration-none">Login disini</a></p>
                                </div>
                            </form>
                        </div>

                        <div class="col-md-6 d-none d-md-block register-image">
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>