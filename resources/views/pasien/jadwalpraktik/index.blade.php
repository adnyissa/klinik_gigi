@extends('layouts.pasien_user')

@section('title', 'Jadwal Praktik Dokter')

@section('content')
<div class="container-fluid pt-4 px-4">
    <div class="row">
        <div class="col-12">
            <div class="bg-light rounded p-4 shadow-sm">

                <!-- HEADER HALAMAN -->
                <div class="d-flex align-items-center justify-content-between mb-4">
                    <div>
                        <h5 class="mb-1 fw-bold text-primary">
                            <i class="fa-regular fa-clock me-2"></i>Jadwal Praktik Dokter
                        </h5>
                        <p class="mb-0 text-muted small">
                            Lihat jadwal praktik dokter yang tersedia di Klinik Gigi Semarang.
                        </p>
                    </div>

                    <!-- FILTER HARI -->
                    <form method="GET" class="d-flex align-items-center gap-2">
                        <label for="hari" class="small text-muted mb-0">Filter Hari:</label>
                        <select name="hari" id="hari" class="form-select form-select-sm" onchange="this.form.submit()">
                            <option value="">Semua Hari</option>
                            @foreach ($daftarHari as $h)
                                <option value="{{ $h }}" {{ $hari === $h ? 'selected' : '' }}>
                                    {{ $h }}
                                </option>
                            @endforeach
                        </select>
                    </form>
                </div>

                <!-- TABEL JADWAL PRAKTIK -->
                <div class="table-responsive">
                    <table class="table table-striped table-hover align-middle">
                        <thead class="table-primary">
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Dokter</th>
                                <th scope="col">Spesialisasi</th>
                                <th scope="col">Hari</th>
                                <th scope="col">Jam Praktik</th>
                                <th scope="col">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($jadwals as $jadwal)
                                <tr>
                                    <th scope="row">{{ $loop->iteration + ($jadwals->currentPage() - 1) * $jadwals->perPage() }}</th>
                                    <td>
                                        <div class="fw-bold">
                                            Dr. {{ $jadwal->dokter->nama ?? '-' }}
                                        </div>
                                        <div class="small text-muted">
                                            {{ $jadwal->dokter->nomor_telepon ?? '-' }} • {{ $jadwal->dokter->email ?? '-' }}
                                        </div>
                                    </td>
                                    <td>{{ $jadwal->dokter->spesialisasi ?? '-' }}</td>
                                    <td>{{ $jadwal->hari }}</td>
                                    <td>
                                        <span class="badge bg-primary-subtle text-primary">
                                            <i class="fa-regular fa-clock me-1"></i>
                                            {{ \Carbon\Carbon::parse($jadwal->jam_mulai)->format('H:i') }}
                                            -
                                            {{ \Carbon\Carbon::parse($jadwal->jam_selesai)->format('H:i') }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($jadwal->status === 'Aktif')
                                            <span class="badge bg-success">Aktif</span>
                                        @else
                                            <span class="badge bg-secondary">Libur</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-5">
                                        <i class="fa-solid fa-inbox fa-3x mb-3 opacity-25"></i>
                                        <p class="mb-0">Belum ada jadwal praktik yang terdaftar.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- PAGINATION -->
                <div class="d-flex justify-content-center mt-3">
                    {{ $jadwals->links() }}
                </div>

            </div>
        </div>
    </div>
</div>
@endsection