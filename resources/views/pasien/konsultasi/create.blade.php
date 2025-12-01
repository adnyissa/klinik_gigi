@extends('layouts.pasien_user')

@section('title', 'Buat Janji Temu Baru')

@section('content')
<div class="container-fluid pt-4 px-4">
    <div class="row">
        <div class="col-lg-8 offset-lg-2">
            <div class="bg-light rounded p-4 shadow-lg">
                
                <h5 class="mb-4 fw-bold text-primary border-bottom pb-2">
                    <i class="fa-solid fa-user-plus me-2"></i>Formulir Pendaftaran Janji Temu
                </h5>

                @if(session('warning'))
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        {{ session('warning') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <form action="{{ route('pasien.konsultasi.store') }}" method="POST">
                    @csrf
                    
                    <!-- Pilihan Dokter -->
                    <div class="mb-3">
                        <label for="dokter_id" class="form-label fw-medium">Pilih Dokter Tujuan <span class="text-danger">*</span></label>
                        <select class="form-select @error('dokter_id') is-invalid @enderror" id="dokter_id" name="dokter_id" required>
                            <option value="">-- Pilih Dokter --</option>
                            @foreach ($dokters as $dokter)
                                <option value="{{ $dokter->dokter_id }}" {{ old('dokter_id') == $dokter->dokter_id ? 'selected' : '' }}>
                                    Dr. {{ $dokter->nama }} ({{ $dokter->spesialisasi }})
                                </option>
                            @endforeach
                        </select>
                        @error('dokter_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Tanggal Kunjungan -->
                    <div class="mb-3">
                        <label for="tgl_kunjungan" class="form-label fw-medium">Tanggal Kunjungan <span class="text-danger">*</span></label>
                        <input type="date" class="form-control @error('tgl_kunjungan') is-invalid @enderror" id="tgl_kunjungan" name="tgl_kunjungan" value="{{ old('tgl_kunjungan') }}" min="{{ \Carbon\Carbon::today()->format('Y-m-d') }}" required>
                        @error('tgl_kunjungan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Pilihan Jadwal Praktik (Diisi via AJAX) -->
                    <div class="mb-3">
                        <label for="jadwal_id" class="form-label fw-medium">Pilih Jam Praktik <span class="text-danger">*</span></label>
                        <select class="form-select @error('jadwal_id') is-invalid @enderror" id="jadwal_id" name="jadwal_id" required disabled>
                            <option value="">-- Pilih Dokter dan Tanggal Dahulu --</option>
                        </select>
                        @error('jadwal_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <!-- Keluhan Awal -->
                    <div class="mb-4">
                        <label for="keluhan_awal" class="form-label fw-medium">Keluhan Awal (Ringkas) <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('keluhan_awal') is-invalid @enderror" id="keluhan_awal" name="keluhan_awal" rows="3" required>{{ old('keluhan_awal') }}</textarea>
                        <div class="form-text">Jelaskan keluhan Anda secara singkat, maksimal 500 karakter.</div>
                        @error('keluhan_awal')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-between pt-2 border-top">
                        <a href="{{ route('pasien.konsultasi.index') }}" class="btn btn-outline-secondary">
                            <i class="fa-solid fa-arrow-left me-1"></i> Kembali ke Riwayat
                        </a>
                        <button type="submit" class="btn btn-primary fw-bold">
                            <i class="fa-solid fa-calendar-plus me-1"></i> Daftarkan Janji Temu
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const dokterSelect = document.getElementById('dokter_id');
        const tanggalInput = document.getElementById('tgl_kunjungan');
        const jadwalSelect = document.getElementById('jadwal_id');

        function fetchJadwal() {
            const dokterId = dokterSelect.value;
            const tglKunjungan = tanggalInput.value;

            // Dapatkan nama hari dalam bahasa Inggris (Senin-Minggu) dari tanggal yang dipilih
            const date = new Date(tglKunjungan);
            const dayOfWeek = date.getDay(); // 0=Minggu, 1=Senin, ..., 6=Sabtu
            
            // Konversi ke format angka 1-7 yang mungkin digunakan di database (atau 1-6 jika Minggu diabaikan)
            // Di sini kita asumsikan 1=Senin, 6=Sabtu
            const dayMap = {
                0: 'Minggu', 
                1: 'Senin', 
                2: 'Selasa', 
                3: 'Rabu', 
                4: 'Kamis', 
                5: 'Jumat', 
                6: 'Sabtu'
            };
            const namaHari = dayMap[dayOfWeek] || '';

            jadwalSelect.innerHTML = '<option value="">Memuat Jadwal...</option>';
            jadwalSelect.disabled = true;

            if (dokterId && tglKunjungan && dayOfWeek !== 0) { // Cek Dokter dan Tanggal valid, dan bukan hari Minggu (0)
                
                // --- SIMULASI PENGAMBILAN JADWAL VIA AJAX ---
                // Dalam implementasi nyata, Anda akan menggunakan fetch/axios untuk memanggil route API
                // Route API: /api/jadwal-dokter?dokter_id=X&hari=Y
                
                console.log(`Mencari jadwal untuk Dokter ID: ${dokterId} pada hari: ${namaHari}`);

                // JANGAN LUPA: Anda harus membuat Controller dan Route API terpisah
                // untuk mengembalikan data jadwal dalam format JSON

                // SIMULASI data dummy JSON dari server
                setTimeout(() => {
                    const dummyData = {
                        1: [ // Dokter ID 1
                            { jadwal_id: 101, hari: 'Senin', jam_mulai: '08:00', jam_selesai: '12:00' },
                            { jadwal_id: 102, hari: 'Senin', jam_mulai: '14:00', jam_selesai: '17:00' },
                        ],
                        2: [ // Dokter ID 2
                            { jadwal_id: 201, hari: 'Selasa', jam_mulai: '10:00', jam_selesai: '15:00' },
                        ]
                        // ... data dokter lainnya
                    };

                    const selectedJadwal = dummyData[dokterId] ? 
                                           dummyData[dokterId].filter(j => j.hari === namaHari) : 
                                           [];

                    jadwalSelect.innerHTML = '';
                    if (selectedJadwal.length > 0) {
                        jadwalSelect.disabled = false;
                        selectedJadwal.forEach(jadwal => {
                            const option = document.createElement('option');
                            option.value = jadwal.jadwal_id;
                            option.textContent = `${jadwal.jam_mulai} - ${jadwal.jam_selesai}`;
                            jadwalSelect.appendChild(option);
                        });
                        // Jika ada data lama (old('jadwal_id')), pilih kembali
                        const oldJadwalId = "{{ old('jadwal_id') }}";
                        if (oldJadwalId) {
                             jadwalSelect.value = oldJadwalId;
                        }

                    } else {
                        jadwalSelect.disabled = true;
                        jadwalSelect.innerHTML = `<option value="">Tidak ada jadwal aktif pada hari ${namaHari}</option>`;
                    }
                    
                }, 500); // Penundaan simulasi server
                // --- AKHIR SIMULASI ---

            } else if (dayOfWeek === 0) {
                jadwalSelect.disabled = true;
                jadwalSelect.innerHTML = '<option value="">Klinik tutup pada hari Minggu</option>';
            } else {
                jadwalSelect.disabled = true;
                jadwalSelect.innerHTML = '<option value="">-- Pilih Dokter dan Tanggal Dahulu --</option>';
            }
        }

        // Event Listeners
        dokterSelect.addEventListener('change', fetchJadwal);
        tanggalInput.addEventListener('change', fetchJadwal);
        
        // Panggil saat load jika ada nilai lama di form
        if (dokterSelect.value && tanggalInput.value) {
            fetchJadwal();
        }
    });
</script>
@endsection