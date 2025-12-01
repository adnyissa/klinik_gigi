@extends('layouts.dokter_user')

@section('title', 'Resep Obat')

@section('content')
<div class="container-fluid pt-4 px-4">
    
    {{-- Welcome Banner --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="bg-white rounded-3 p-4 shadow-sm d-flex align-items-center justify-content-between border-start border-4 border-primary">
                <div>
                    <h4 class="text-primary fw-bold mb-1">Resep Obat</h4>
                    <p class="text-muted mb-0">Daftar resep obat yang telah Anda buat untuk pasien.</p>
                </div>
                <div class="d-none d-md-block text-end">
                    <h6 class="text-dark mb-0">{{ \Carbon\Carbon::now()->isoFormat('dddd, D MMMM Y') }}</h6>
                    <span class="badge bg-primary bg-opacity-75 rounded-pill">Total: {{ $resepObat->total() }} Resep</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Tabel Resep Obat --}}
    <div class="row g-4">
        <div class="col-12">
            <div class="bg-white rounded-3 p-4 shadow-sm">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <h5 class="mb-0 fw-bold text-dark">Daftar Resep Obat</h5>
                    <div>
                        <button class="btn btn-sm btn-success rounded-pill px-3 me-2" data-bs-toggle="modal" data-bs-target="#tambahResepModal">
                            <i class="fa-solid fa-plus me-1"></i> Tambah Resep
                        </button>
                        <button class="btn btn-sm btn-outline-secondary" onclick="window.location.reload();">
                            <i class="fa-solid fa-sync-alt me-1"></i> Refresh
                        </button>
                    </div>
                </div>
                
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light text-muted small sticky-top">
                            <tr>
                                <th class="ps-3">No</th>
                                <th>Tanggal</th>
                                <th>Nama Pasien</th>
                                <th>Diagnosis</th>
                                <th>Resep Obat / Tindakan</th>
                                <th class="text-end pe-3">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($resepObat as $resep)
                            <tr>
                                <td class="ps-3">
                                    <span class="badge bg-light text-dark border border-secondary">{{ $loop->iteration + ($resepObat->currentPage() - 1) * $resepObat->perPage() }}</span>
                                </td>
                                <td>
                                    <div>
                                        <span class="fw-semibold text-dark">{{ \Carbon\Carbon::parse($resep->created_at)->format('d/m/Y') }}</span>
                                        <br>
                                        <small class="text-muted" style="font-size: 11px;">{{ \Carbon\Carbon::parse($resep->created_at)->format('H:i') }} WIB</small>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img src="https://ui-avatars.com/api/?name={{ $resep->konsultasi->pasien->nama ?? 'Pasien' }}&background=random&size=35" 
                                             class="rounded-circle me-2" alt="Avatar">
                                        <div>
                                            <h6 class="mb-0 text-dark small fw-bold">{{ $resep->konsultasi->pasien->nama ?? 'Nama Tidak Ditemukan' }}</h6>
                                            <small class="text-muted" style="font-size: 11px;">
                                                NIK: {{ $resep->konsultasi->pasien->nik ?? '-' }}
                                            </small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-warning text-dark">{{ $resep->diagnosis ?? '-' }}</span>
                                </td>
                                <td>
                                    <small class="text-muted">{{ Str::limit($resep->tindakan ?? '-', 80) }}</small>
                                </td>
                                <td class="text-end pe-3">
                                    <button class="btn btn-sm btn-primary rounded-pill px-3" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#resepModal{{ $resep->rekam_medis_id }}">
                                        <i class="fa-solid fa-prescription-bottle-medical me-1"></i> Lihat Resep
                                    </button>
                                </td>
                            </tr>

                            {{-- Modal Detail Resep Obat --}}
                            <div class="modal fade" id="resepModal{{ $resep->rekam_medis_id }}" tabindex="-1" aria-labelledby="resepModalLabel{{ $resep->rekam_medis_id }}" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header bg-primary text-white">
                                            <h5 class="modal-title" id="resepModalLabel{{ $resep->rekam_medis_id }}">
                                                <i class="fa-solid fa-prescription-bottle-medical me-2"></i>Resep Obat
                                            </h5>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row mb-3">
                                                <div class="col-md-6">
                                                    <h6 class="text-muted small mb-1">Tanggal Resep</h6>
                                                    <p class="mb-0 fw-bold">{{ \Carbon\Carbon::parse($resep->created_at)->isoFormat('dddd, D MMMM Y [pukul] HH:mm') }} WIB</p>
                                                </div>
                                                <div class="col-md-6">
                                                    <h6 class="text-muted small mb-1">No. Rekam Medis</h6>
                                                    <p class="mb-0 fw-bold">RM-{{ str_pad($resep->rekam_medis_id, 6, '0', STR_PAD_LEFT) }}</p>
                                                </div>
                                            </div>
                                            
                                            <hr>

                                            <div class="row mb-3">
                                                <div class="col-12">
                                                    <h6 class="text-muted small mb-2">Data Pasien</h6>
                                                    <div class="bg-light p-3 rounded">
                                                        <div class="row">
                                                            <div class="col-md-6 mb-2">
                                                                <strong>Nama:</strong> {{ $resep->konsultasi->pasien->nama ?? '-' }}
                                                            </div>
                                                            <div class="col-md-6 mb-2">
                                                                <strong>NIK:</strong> {{ $resep->konsultasi->pasien->nik ?? '-' }}
                                                            </div>
                                                            <div class="col-md-6 mb-2">
                                                                <strong>Jenis Kelamin:</strong> {{ $resep->konsultasi->pasien->jenis_kelamin ?? '-' }}
                                                            </div>
                                                            <div class="col-md-6 mb-2">
                                                                <strong>Tanggal Lahir:</strong> {{ $resep->konsultasi->pasien->tanggal_lahir ? \Carbon\Carbon::parse($resep->konsultasi->pasien->tanggal_lahir)->format('d/m/Y') : '-' }}
                                                            </div>
                                                            <div class="col-12 mb-2">
                                                                <strong>Alamat:</strong> {{ $resep->konsultasi->pasien->alamat ?? '-' }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row mb-3">
                                                <div class="col-12">
                                                    <h6 class="text-muted small mb-2">Keluhan Awal</h6>
                                                    <p class="mb-0 bg-light p-3 rounded">{{ $resep->konsultasi->keluhan_awal ?? '-' }}</p>
                                                </div>
                                            </div>

                                            <div class="row mb-3">
                                                <div class="col-12">
                                                    <h6 class="text-muted small mb-2">Diagnosis</h6>
                                                    <p class="mb-0">
                                                        <span class="badge bg-warning text-dark fs-6">{{ $resep->diagnosis ?? '-' }}</span>
                                                    </p>
                                                </div>
                                            </div>

                                            <div class="row mb-3">
                                                <div class="col-12">
                                                    <h6 class="text-primary small mb-2">
                                                        <i class="fa-solid fa-prescription-bottle-medical me-1"></i>Resep Obat / Tindakan
                                                    </h6>
                                                    <div class="bg-primary bg-opacity-10 p-3 rounded border border-primary">
                                                        <p class="mb-0 text-dark" style="white-space: pre-wrap;">{{ $resep->tindakan ?? '-' }}</p>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-12">
                                                    <h6 class="text-muted small mb-2">Biaya Total</h6>
                                                    <h4 class="mb-0 text-success fw-bold">Rp {{ number_format($resep->biaya_total ?? 0, 0, ',', '.') }}</h4>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                            <button type="button" class="btn btn-primary" onclick="window.print();">
                                                <i class="fa-solid fa-print me-1"></i> Cetak Resep
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted empty-row">
                                    <div class="d-flex flex-column align-items-center">
                                        <i class="fa-solid fa-prescription-bottle-medical fa-3x mb-3 text-muted"></i>
                                        <p class="mb-0">Belum ada resep obat yang dibuat.</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                @if($resepObat->hasPages())
                <div class="d-flex justify-content-center mt-4">
                    {{ $resepObat->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>

</div>

{{-- Modal Tambah Resep --}}
<div class="modal fade" id="tambahResepModal" tabindex="-1" aria-labelledby="tambahResepModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="tambahResepModalLabel">
                    <i class="fa-solid fa-prescription-bottle-medical me-2"></i>Tambah Resep Obat Baru
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('dokter.rekam-medis.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="konsultasi_id_resep" class="form-label">Konsultasi <span class="text-danger">*</span></label>
                        <select class="form-select" id="konsultasi_id_resep" name="konsultasi_id" required>
                            <option value="">Pilih Konsultasi</option>
                            @foreach($konsultasisResep ?? [] as $konsultasi)
                                <option value="{{ $konsultasi->konsultasi_id }}">
                                    {{ $konsultasi->pasien->nama ?? 'Pasien' }} - 
                                    {{ \Carbon\Carbon::parse($konsultasi->tgl_kunjungan)->format('d/m/Y') }}
                                </option>
                            @endforeach
                        </select>
                        <small class="text-muted">Pilih konsultasi yang sudah selesai dan belum memiliki rekam medis</small>
                    </div>
                    <div class="mb-3">
                        <label for="diagnosis_resep" class="form-label">Diagnosis <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="diagnosis_resep" name="diagnosis" placeholder="Masukkan diagnosis..." required>
                    </div>
                    <div class="mb-3">
                        <label for="tindakan_resep" class="form-label">Resep Obat / Tindakan <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="tindakan_resep" name="tindakan" rows="6" placeholder="Masukkan resep obat atau tindakan medis..." required></textarea>
                        <small class="text-muted">Contoh: Paracetamol 500mg 3x1 setelah makan, Amoxicillin 500mg 3x1 setelah makan</small>
                    </div>
                    <div class="mb-3">
                        <label for="biaya_total_resep" class="form-label">Biaya Total <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="number" class="form-control" id="biaya_total_resep" name="biaya_total" min="0" step="0.01" placeholder="0" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fa-solid fa-save me-1"></i> Simpan Resep
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

@push('styles')
<style>
    @media print {
        .sidebar, .navbar, .btn, .modal-footer {
            display: none !important;
        }
        .modal-dialog {
            max-width: 100% !important;
            margin: 0 !important;
        }
        .modal-content {
            border: none !important;
            box-shadow: none !important;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        console.log('Halaman Resep Obat dimuat.');
    });
</script>
@endpush

