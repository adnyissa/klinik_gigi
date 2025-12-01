@extends('layouts.dokter_user')

@section('title', 'Periksa Pasien')

@section('content')
<div class="container-fluid pt-4 px-4">

    <div class="row mb-4">
        <div class="col-12">
            <div class="bg-white rounded-3 p-4 shadow-sm d-flex justify-content-between border-start border-4 border-primary">
                <div>
                    <h4 class="text-primary fw-bold mb-1">Periksa Pasien</h4>
                    <p class="text-muted mb-0">Daftar pasien yang menunggu dan sedang diperiksa hari ini.</p>
                </div>
                <div class="text-end d-none d-md-block">
                    <h6 class="text-dark mb-0">{{ \Carbon\Carbon::now()->isoFormat('dddd, D MMMM Y') }}</h6>
                    <small class="text-muted">Total Antrian: {{ $antrianPeriksa->count() }}</small>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-12">
            <div class="bg-white rounded-3 p-4 shadow-sm">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold mb-0">Antrian Pasien Hari Ini</h5>
                    <div>
                        <button class="btn btn-sm btn-success rounded-pill px-3 me-2" data-bs-toggle="modal" data-bs-target="#tambahAntrianModal">
                            <i class="fa-solid fa-plus me-1"></i> Tambah Antrian
                        </button>
                        <button class="btn btn-sm btn-outline-secondary" onclick="window.location.reload()">
                            <i class="fa-solid fa-rotate me-1"></i> Refresh
                        </button>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="bg-light text-muted small">
                            <tr>
                                <th>No</th>
                                <th>Nomor Antrian</th>
                                <th>Nama Pasien</th>
                                <th>Jam Kunjungan</th>
                                <th>Status</th>
                                <th>Keluhan</th>
                                <th class="text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($antrianPeriksa as $antrian)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    <span class="badge bg-primary">{{ $antrian->nomor_antrian ?? '-' }}</span>
                                </td>
                                <td>
                                    <strong>{{ $antrian->pasien->nama ?? 'Nama tidak ditemukan' }}</strong><br>
                                    <small class="text-muted">{{ $antrian->pasien->nik ?? '' }}</small>
                                </td>
                                <td>
                                    @if($antrian->jam_kunjungan)
                                        <span class="fw-semibold">{{ \Carbon\Carbon::parse($antrian->jam_kunjungan)->format('H:i') }}</span> WIB
                                    @else
                                        <span class="text-muted small">-</span>
                                    @endif
                                </td>
                                <td>
                                    @php $status = strtolower($antrian->status); @endphp
                                    @if($status === 'menunggu')
                                        <span class="badge bg-warning text-dark">Menunggu</span>
                                    @elseif($status === 'diperiksa')
                                        <span class="badge bg-primary">Sedang Diperiksa</span>
                                    @else
                                        <span class="badge bg-secondary">{{ ucfirst($antrian->status) }}</span>
                                    @endif
                                </td>
                                <td>{{ $antrian->keluhan_awal ?? '-' }}</td>
                                <td class="text-end">
                                    <a href="#" class="btn btn-sm btn-success rounded-pill px-3">
                                        <i class="fa-solid fa-stethoscope me-1"></i> Mulai / Lanjut Periksa
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">
                                    Belum ada pasien yang menunggu untuk diperiksa hari ini.
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

{{-- Modal Tambah Antrian --}}
<div class="modal fade" id="tambahAntrianModal" tabindex="-1" aria-labelledby="tambahAntrianModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="tambahAntrianModalLabel">
                    <i class="fa-solid fa-plus me-2"></i>Tambah Antrian Baru
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('dokter.antrian.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="pasien_id" class="form-label">Pasien <span class="text-danger">*</span></label>
                        <select class="form-select" id="pasien_id" name="pasien_id" required>
                            <option value="">Pilih Pasien</option>
                            @foreach($pasiens ?? [] as $pasien)
                                <option value="{{ $pasien->pasien_id }}">{{ $pasien->nama }} - {{ $pasien->nik }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="tgl_kunjungan" class="form-label">Tanggal Kunjungan <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" id="tgl_kunjungan" name="tgl_kunjungan" value="{{ date('Y-m-d') }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="jam_kunjungan" class="form-label">Jam Kunjungan <span class="text-danger">*</span></label>
                        <input type="time" class="form-control" id="jam_kunjungan" name="jam_kunjungan" required>
                    </div>
                    <div class="mb-3">
                        <label for="keluhan_awal" class="form-label">Keluhan Awal</label>
                        <textarea class="form-control" id="keluhan_awal" name="keluhan_awal" rows="3" placeholder="Masukkan keluhan pasien..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fa-solid fa-save me-1"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show position-fixed top-0 end-0 m-3" role="alert" style="z-index: 9999;">
    <i class="fa-solid fa-check-circle me-2"></i>{{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

@if(session('error'))
<div class="alert alert-danger alert-dismissible fade show position-fixed top-0 end-0 m-3" role="alert" style="z-index: 9999;">
    <i class="fa-solid fa-exclamation-circle me-2"></i>{{ session('error') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

@endsection


