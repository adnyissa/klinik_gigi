@extends('layouts.pasien_user')

@section('title', 'Konsultasi & Janji Temu')

@section('content')
<div class="container-fluid pt-4 px-4">
    <div class="row">
        <div class="col-12">
            <div class="bg-light rounded p-4 shadow-sm">
                
                <!-- HEADER HALAMAN -->
                <div class="d-flex align-items-center justify-content-between mb-4">
                    <h5 class="mb-0 fw-bold text-primary"><i class="fa-solid fa-calendar-check me-2"></i>Riwayat Janji Temu</h5>
                    
                    <!-- TOMBOL UNTUK MEMBUKA MODAL (Formulir Tersembunyi) -->
                    <!-- Data-bs-toggle akan memicu modal dengan ID modalBuatJanji -->
                    <button type="button" class="btn btn-primary fw-medium" data-bs-toggle="modal" data-bs-target="#modalBuatJanji">
                        <i class="fa-solid fa-plus me-1"></i> Buat Janji Temu Baru
                    </button>
                </div>

                <!-- ALERT PESAN (Sukses/Error/Warning) -->
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fa-solid fa-check-circle me-2"></i>{{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                
                @if(session('warning'))
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        <i class="fa-solid fa-exclamation-triangle me-2"></i>{{ session('warning') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fa-solid fa-times-circle me-2"></i>{{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <!-- TABEL DATA RIWAYAT -->
                <div class="table-responsive">
                    <table class="table table-striped table-hover align-middle">
                        <thead class="table-primary">
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Tanggal</th>
                                <th scope="col">Dokter & Waktu</th>
                                <th scope="col">Keluhan</th>
                                <th scope="col">Status</th>
                                <th scope="col" class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($riwayat_konsultasi as $konsultasi)
                            <tr>
                                <th scope="row">{{ $loop->iteration + ($riwayat_konsultasi->currentPage() - 1) * $riwayat_konsultasi->perPage() }}</th>
                                <!-- Format Tanggal Indonesia -->
                                <td>{{ \Carbon\Carbon::parse($konsultasi->tgl_kunjungan)->translatedFormat('D, j F Y') }}</td>
                                <td>
                                    <div class="fw-bold">Dr. {{ $konsultasi->dokter->nama ?? 'N/A' }}</div>
                                    <div class="small text-primary">
                                        <i class="fa-regular fa-clock me-1"></i> 
                                        {{ $konsultasi->jadwal->jam_mulai ?? '-' }} - {{ $konsultasi->jadwal->jam_selesai ?? '-' }}
                                    </div>
                                </td>
                                <td>{{ Str::limit($konsultasi->keluhan_awal, 40) }}</td>
                                <td>
                                    @php
                                        // Logika warna badge berdasarkan status
                                        $statusClass = match($konsultasi->status) {
                                            'Menunggu' => 'bg-warning text-dark',
                                            'Diperiksa' => 'bg-info text-white',
                                            'Selesai' => 'bg-success',
                                            'Batal' => 'bg-secondary',
                                            default => 'bg-secondary'
                                        };
                                    @endphp
                                    <span class="badge {{ $statusClass }}">{{ $konsultasi->status }}</span>
                                </td>
                                <td class="text-center">
                                    <!-- Tombol Batal hanya muncul jika status masih 'Menunggu' -->
                                    @if($konsultasi->status == 'Menunggu')
                                        <form action="{{ route('pasien.konsultasi.cancel', $konsultasi->konsultasi_id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin membatalkan janji temu ini?')">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Batalkan">
                                                <i class="fa-solid fa-times"></i>
                                            </button>
                                        </form>
                                    @else
                                        <!-- Tombol Disabled jika sudah selesai/batal -->
                                        <button class="btn btn-sm btn-outline-secondary" disabled>
                                            <i class="fa-solid fa-lock"></i>
                                        </button>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-5">
                                    <i class="fa-solid fa-inbox fa-3x mb-3 opacity-25"></i>
                                    <p class="mb-0">Belum ada riwayat janji temu.</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <!-- PAGINATION (Navigasi Halaman) -->
                <div class="d-flex justify-content-center mt-3">
                    {{ $riwayat_konsultasi->links() }}
                </div>

            </div>
        </div>
    </div>
</div>

<!-- ========================================== -->
<!-- MODAL FORMULIR (POP-UP)                    -->
<!-- ========================================== -->
<div class="modal fade" id="modalBuatJanji" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            
            <!-- Header Modal -->
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title fw-bold">
                    <i class="fa-solid fa-calendar-plus me-2"></i>Buat Janji Temu
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <!-- Body Modal (Formulir) -->
            <form action="{{ route('pasien.konsultasi.store') }}" method="POST">
                @csrf <!-- Token Keamanan Wajib -->
                <div class="modal-body p-4">
                    
                    <!-- Pilihan Dokter -->
                    <div class="mb-3">
                        <label for="dokter_id" class="form-label fw-bold small text-uppercase text-muted">Dokter Tujuan</label>
                        <select class="form-select" id="dokter_id" name="dokter_id" required>
                            <option value="">-- Pilih Dokter --</option>
                            @foreach ($dokters as $dokter)
                                <option value="{{ $dokter->dokter_id }}">Dr. {{ $dokter->nama }} ({{ $dokter->spesialisasi }})</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Tanggal -->
                    <div class="mb-3">
                        <label for="tgl_kunjungan" class="form-label fw-bold small text-uppercase text-muted">Tanggal Kunjungan</label>
                        <input type="date" class="form-control" id="tgl_kunjungan" name="tgl_kunjungan" min="{{ date('Y-m-d') }}" required>
                    </div>

                    <!-- Jadwal (Akan diisi otomatis via JavaScript) -->
                    <div class="mb-3">
                        <label for="jadwal_id" class="form-label fw-bold small text-uppercase text-muted">Jam Praktik</label>
                        <select class="form-select" id="jadwal_id" name="jadwal_id" required disabled>
                            <option value="">-- Pilih Dokter & Tanggal Dulu --</option>
                        </select>
                        <div class="form-text text-muted" id="jadwal-info"></div>
                    </div>

                    <!-- Keluhan -->
                    <div class="mb-3">
                        <label for="keluhan_awal" class="form-label fw-bold small text-uppercase text-muted">Keluhan</label>
                        <textarea class="form-control" id="keluhan_awal" name="keluhan_awal" rows="3" required placeholder="Contoh: Sakit gigi geraham kanan bawah berdenyut..."></textarea>
                    </div>

                </div>
                
                <!-- Footer Modal -->
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary px-4"><i class="fa-solid fa-paper-plane me-1"></i> Daftar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- SCRIPT JAVASCRIPT UNTUK MODAL & AJAX JADWAL -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const dokterSelect = document.getElementById('dokter_id');
        const tanggalInput = document.getElementById('tgl_kunjungan');
        const jadwalSelect = document.getElementById('jadwal_id');
        const jadwalInfo = document.getElementById('jadwal-info');

        // Fungsi untuk mengambil/mensimulasikan jadwal
        function fetchJadwal() {
            const dokterId = dokterSelect.value;
            const tglKunjungan = tanggalInput.value;

            // Reset dropdown jadwal jika data belum lengkap
            if (!dokterId || !tglKunjungan) {
                jadwalSelect.disabled = true;
                jadwalSelect.innerHTML = '<option value="">-- Pilih Dokter & Tanggal Dulu --</option>';
                jadwalInfo.textContent = '';
                return;
            }

            // Simulasi hari dari tanggal (Minggu = 0, Senin = 1, dst)
            const date = new Date(tglKunjungan);
            const dayOfWeek = date.getDay(); 
            
            jadwalSelect.disabled = true;
            jadwalSelect.innerHTML = '<option value="">Memuat Jadwal...</option>';

            // --- SIMULASI AJAX (Ganti dengan API asli nanti jika sudah siap) ---
            setTimeout(() => {
                // Di sini nanti Anda ganti dengan fetch('/api/jadwal?dokter='+dokterId+'&hari='+dayOfWeek)
                
                // Contoh Data Dummy (Simulasi)
                // Pastikan value '1' dan '2' ini diganti dengan ID jadwal asli dari database Anda (tabel jadwal_praktiks)
                const dummyJadwal = [
                    { id: 1, text: 'Pagi (08:00 - 12:00)' },
                    { id: 2, text: 'Sore (16:00 - 20:00)' }
                ];

                jadwalSelect.innerHTML = '';
                
                if (dayOfWeek === 0) { // Minggu
                    jadwalSelect.innerHTML = '<option value="">Minggu Libur</option>';
                    jadwalInfo.textContent = 'Maaf, klinik tutup pada hari Minggu.';
                    jadwalInfo.classList.add('text-danger');
                } else {
                    jadwalSelect.disabled = false;
                    jadwalSelect.innerHTML = '<option value="">-- Pilih Jam --</option>';
                    jadwalInfo.textContent = 'Jadwal tersedia untuk hari yang dipilih.';
                    jadwalInfo.classList.remove('text-danger');
                    jadwalInfo.classList.add('text-success');
                    
                    // Masukkan opsi jadwal dummy ke dropdown
                    dummyJadwal.forEach(j => {
                        const opt = document.createElement('option');
                        opt.value = j.id; // Value yang dikirim ke database
                        opt.textContent = j.text;
                        jadwalSelect.appendChild(opt);
                    });
                }
            }, 500); // Simulasi delay loading 0.5 detik
        }

        // Panggil fungsi saat user mengubah dokter atau tanggal
        dokterSelect.addEventListener('change', fetchJadwal);
        tanggalInput.addEventListener('change', fetchJadwal);
    });
</script>
@endsection