@extends('layouts.pasien_user')

@section('title', 'Dashboard Pasien')

{{-- ASUMSI: Data berikut dilewatkan dari Controller Pasien/Dashboard.
     Data diambil melalui JOIN / Relasi Model agar properti seperti nama_dokter dan jadwal tersedia.

     $data['antrian'] = (object) ['nomor' => 'A-03', 'panggilan_saat_ini' => 'A-01']; // Derived data
     
     $data['janji_temu_berikutnya'] = null; // Objek Konsultasi (tgl_kunjungan, dokter->nama, jadwal_praktik->jam_mulai)
     $data['kunjungan_terakhir'] = null; // Objek Rekam Medis / Konsultasi (tgl_kunjungan, rekam_medis->tindakan)
     
     $data['dokter_favorit'] = []; // Array Dokter yang telah di-JOIN dengan Jadwal Praktik (hari, jam_mulai, jam_selesai)
     $data['aktivitas'] = []; // Array Gabungan dari Konsultasi, Rekam Medis, atau Pembayaran
--}}

@section('content')

@php
    // --- PENANGANAN ERROR: Memastikan variabel didefinisikan untuk menghindari 'Undefined variable' jika Controller lupa mengirimnya ---
    $antrian = $antrian ?? null;
    $janji_temu_berikutnya = $janji_temu_berikutnya ?? null;
    $kunjungan_terakhir = $kunjungan_terakhir ?? null;
    $dokter_favorit = $dokter_favorit ?? [];
    $aktivitas = $aktivitas ?? [];
@endphp

    <!-- 1. KARTU SELAMAT DATANG MODERN & PERSONALISASI -->
    <div class="row">
        <div class="col-12">
            {{-- MENGGUNAKAN CLASS BARU: pasien-welcome-card-bg --}}
            <div class="pasien-welcome-card-bg mb-4">
                
                {{-- MENGGUNAKAN CLASS BARU: pasien-bg-tooth-icon --}}
                <i class="fa-solid fa-tooth pasien-bg-tooth-icon"></i>
                
                {{-- MENGGUNAKAN CLASS BARU: pasien-header-klinik --}}
                <div class="pasien-header-klinik mb-2 text-white opacity-75 fw-bold">
                    KLINIK GIGI SEMARANG
                </div>

                <!-- Mengambil nama pengguna dari Auth -->
                {{-- MENGGUNAKAN CLASS BARU: pasien-welcome-title --}}
                <h1 class="pasien-welcome-title display-5 fw-bold mb-1">Selamat Datang, {{ Auth::user()->name ?? 'Pasien' }}!</h1>
                
                {{-- MENGGUNAKAN CLASS BARU: pasien-welcome-subtitle --}}
                <p class="pasien-welcome-subtitle lead text-warning fw-medium">"Kamu jaga hatimu, Kami jaga gigimu."</p>

                <p class="mt-4 mb-0 small text-white opacity-90">
                    Ini adalah ringkasan status janji temu dan informasi medis terbaru Anda.
                </p>
                
            </div>
        </div>
    </div>

    <!-- 2. KARTU STATISTIK UTAMA (Ringkasan Cepat) -->
    <div class="row g-4 mb-4">
        
        <!-- Kartu 1: Status Antrian Hari Ini -->
        <div class="col-sm-6 col-lg-4 col-xl-4">
            {{-- Tambahkan h-100 agar semua kartu memiliki tinggi yang sama --}}
            <div class="bg-light rounded p-4 pasien-card-stat shadow-sm h-100"> 
                {{-- Tambahkan d-flex, flex-column, dan h-100 untuk penataan konten internal --}}
                <div class="d-flex flex-column h-100">
                    <div class="d-flex align-items-center justify-content-between">
                        <i class="fa-solid fa-ticket fa-3x text-danger"></i>
                        <div class="ms-3 text-end">
                            <p class="mb-2 text-secondary small">Nomor Antrian Anda</p>
                            <!-- DATA DINAMIS: Nomor Antrian -->
                            <h2 class="mb-0 text-danger display-6 fw-bold">{{ $antrian->nomor ?? '-' }}</h2> 
                        </div>
                    </div>
                    {{-- Pindahkan bagian ini ke luar d-flex utama agar tidak mengganggu sejajarannya --}}
                    <div class="text-end position-absolute bottom-0 end-0 p-2">
                        <small class="text-primary fw-medium">
                            <i class="fa-solid fa-bell me-1"></i> Panggilan saat ini: {{ $antrian->panggilan_saat_ini ?? '-' }}
                        </small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Kartu 2: Janji Temu Mendatang (Menggunakan tgl_kunjungan dari tabel 'konsultasis') -->
        <div class="col-sm-6 col-lg-4 col-xl-4">
            {{-- Tambahkan h-100 agar semua kartu memiliki tinggi yang sama --}}
            <div class="bg-light rounded p-4 pasien-card-stat shadow-sm h-100">
                 {{-- Tambahkan d-flex, flex-column, dan h-100 untuk penataan konten internal --}}
                <div class="d-flex flex-column h-100">
                    <div class="d-flex align-items-center justify-content-between">
                        <i class="fa-solid fa-calendar-alt fa-3x text-success"></i>
                        <div class="ms-3 text-end">
                            <p class="mb-2 text-secondary small">Janji Temu Mendatang</p>
                            <!-- DATA DINAMIS: Tanggal Janji Temu -->
                            <h4 class="mb-0 fw-bold text-success">
                                {{-- Menggunakan tgl_kunjungan dari Konsultasi --}}
                                {{ $janji_temu_berikutnya ? date('D, j M Y', strtotime($janji_temu_berikutnya->tgl_kunjungan)) : 'Belum Ada' }}
                            </h4> 
                        </div>
                    </div>
                    {{-- Pindahkan bagian ini ke luar d-flex utama agar tidak mengganggu sejajarannya --}}
                    <div class="text-end position-absolute bottom-0 end-0 p-2">
                        <small class="text-success fw-medium">
                            <!-- DATA DINAMIS: Dokter dan Waktu (Menggunakan jam_mulai dari Jadwal) -->
                            <i class="fa-solid fa-user-doctor me-1"></i> 
                            {{ $janji_temu_berikutnya ? 'Dr. ' . $janji_temu_berikutnya->nama_dokter . ' (' . $janji_temu_berikutnya->jam_mulai . ')' : '-' }}
                        </small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Kartu 3: Riwayat Medis Terakhir (Menggunakan tindakan dari tabel 'rekam_medis') -->
        <div class="col-sm-6 col-lg-4 col-xl-4">
            {{-- Tambahkan h-100 agar semua kartu memiliki tinggi yang sama --}}
            <div class="bg-light rounded p-4 pasien-card-stat shadow-sm h-100">
                 {{-- Tambahkan d-flex, flex-column, dan h-100 untuk penataan konten internal --}}
                <div class="d-flex flex-column h-100">
                    <div class="d-flex align-items-center justify-content-between">
                        <i class="fa-solid fa-notes-medical fa-3x text-warning"></i>
                        <div class="ms-3 text-end">
                            <p class="mb-2 text-secondary small">Kunjungan Terakhir</p>
                            <!-- DATA DINAMIS: Tanggal Kunjungan Terakhir -->
                            <h4 class="mb-0 fw-bold text-warning">
                                {{-- Menggunakan tgl_kunjungan dari Konsultasi atau Rekam Medis --}}
                                {{ $kunjungan_terakhir ? date('j M Y', strtotime($kunjungan_terakhir->tgl_kunjungan)) : 'Belum Ada' }}
                            </h4> 
                        </div>
                    </div>
                    {{-- Pindahkan bagian ini ke luar d-flex utama agar tidak mengganggu sejajarannya --}}
                    <div class="text-end position-absolute bottom-0 end-0 p-2">
                        <small class="text-warning fw-medium">
                            <!-- DATA DINAMIS: Tipe Perawatan (Menggunakan tindakan dari Rekam Medis) -->
                            <i class="fa-solid fa-tooth me-1"></i> {{ $kunjungan_terakhir->tindakan ?? '-' }}
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- 3. DETAIL AKTIVITAS (Jadwal Dokter Favorit & Aktivitas Terbaru) -->
    <div class="row g-4">
        
        <!-- KOLOM KIRI: Jadwal Dokter Favorit -->
        <div class="col-lg-6">
            <div class="bg-light rounded p-4 shadow-sm h-100">
                <div class="d-flex align-items-center justify-content-between mb-4">
                    <h5 class="mb-0 fw-bold text-primary"><i class="fa-solid fa-user-doctor me-2"></i>Jadwal Dokter Favorit</h5>
                    <a href="#" class="btn btn-sm btn-outline-primary fw-medium">Lihat Semua Dokter</a>
                </div>
                
                <!-- LOOP DATA DINAMIS DOKTER (Data gabungan dari 'dokters' dan 'jadwal_praktiks') -->
                @forelse($dokter_favorit as $dokter)
                <div class="d-flex align-items-center @if(!$loop->last) border-bottom @endif py-3">
                    <!-- Gunakan placeholder atau gambar profil dokter -->
                    <img class="rounded-circle flex-shrink-0" src="https://placehold.co/100x100/{{ $dokter->kode_warna ?? '96A2B3' }}/ffffff?text={{ $dokter->inisial ?? 'Dr' }}" alt="{{ $dokter->nama }}" style="width: 40px; height: 40px;">
                    <div class="w-100 ms-3">
                        <div class="d-flex w-100 justify-content-between">
                            <h6 class="mb-0 fw-medium">Dr. {{ $dokter->nama }}, {{ $dokter->spesialisasi }}</h6>
                            <small class="text-{{ $dokter->status_warna ?? 'secondary' }} fw-bold">
                                {{-- Menggunakan hari, jam_mulai, dan jam_selesai dari Jadwal Praktik --}}
                                Jadwal: {{ $dokter->hari ?? '-' }} ({{ $dokter->jam_mulai ?? '-' }} - {{ $dokter->jam_selesai ?? '-' }})
                            </small>
                        </div>
                        <span class="small text-muted">Spesialisasi: {{ $dokter->spesialisasi_lengkap ?? $dokter->spesialisasi }}</span>
                    </div>
                </div>
                @empty
                    <div class="p-2 text-center text-muted small mt-2">
                        <p>Belum ada daftar Dokter Favorit. Silakan tambahkan!</p>
                    </div>
                @endforelse
                <!-- END LOOP DATA DINAMIS DOKTER -->

            </div>
        </div>

        <!-- KOLOM KANAN: Aktivitas Terbaru (Riwayat & Pembayaran) -->
        <div class="col-lg-6">
            <div class="bg-light rounded p-4 shadow-sm h-100">
                <div class="d-flex align-items-center justify-content-between mb-4">
                    <h5 class="mb-0 fw-bold text-primary"><i class="fa-solid fa-list-check me-2"></i>Aktivitas Terbaru Anda</h5>
                    <a href="#" class="btn btn-sm btn-outline-secondary fw-medium">Riwayat Lengkap</a>
                </div>
                
                <!-- Timeline/List Aktivitas -->
                <div class="list-group list-group-flush">
                    
                    <!-- LOOP DATA DINAMIS AKTIVITAS -->
                    @forelse($aktivitas as $item)
                    <a href="{{ $item->link ?? '#' }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center @if(!$loop->last) border-bottom @endif py-3">
                        <div class="d-flex align-items-center">
                            <!-- Icon disesuaikan berdasarkan tipe aktivitas -->
                            <i class="fa-solid {{ $item->icon_class }} fa-lg text-{{ $item->warna }} me-3"></i> 
                            <div class="me-auto">
                                {{-- Judul aktivitas bisa berupa Konsultasi, Pembayaran, dll. --}}
                                <div class="fw-bold mb-0">{{ $item->judul }}</div>
                                <span class="small text-muted">{{ $item->detail }}</span>
                            </div>
                        </div>
                        <small class="text-muted">{{ $item->waktu_relatif }}</small>
                    </a>
                    @empty
                    <div class="p-2 text-center text-muted small mt-2">
                        <p>Belum ada aktivitas terbaru yang tercatat.</p>
                    </div>
                    @endforelse
                    <!-- END LOOP DATA DINAMIS AKTIVITAS -->
                    
                </div>

            </div>
        </div>
    </div>
@endsection