@extends('layouts.dokter_user')

@section('title', 'Riwayat Pasien')

@section('content')
<div class="container-fluid pt-4 px-4">
    
    {{-- Welcome Banner --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="bg-white rounded-3 p-4 shadow-sm d-flex align-items-center justify-content-between border-start border-4 border-info">
                <div>
                    <h4 class="text-info fw-bold mb-1">Riwayat Pasien</h4>
                    <p class="text-muted mb-0">Daftar rekam medis pasien yang telah Anda periksa.</p>
                </div>
                <div class="d-none d-md-block text-end">
                    <h6 class="text-dark mb-0">{{ \Carbon\Carbon::now()->isoFormat('dddd, D MMMM Y') }}</h6>
                    <span class="badge bg-info bg-opacity-75 rounded-pill">Total: {{ $riwayatRekamMedis->total() }} Rekam Medis</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Tabel Riwayat Rekam Medis --}}
    <div class="row g-4">
        <div class="col-12">
            <div class="bg-white rounded-3 p-4 shadow-sm">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <h5 class="mb-0 fw-bold text-dark">Daftar Riwayat Rekam Medis</h5>
                    <div>
                        <button class="btn btn-sm btn-success rounded-pill px-3 me-2" data-bs-toggle="modal" data-bs-target="#tambahRekamMedisModal">
                            <i class="fa-solid fa-plus me-1"></i> Tambah Rekam Medis
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
                                <th>Tindakan</th>
                                <th>Biaya Total</th>
                                <th class="text-end pe-3">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($riwayatRekamMedis as $rekam)
                            <tr>
                                <td class="ps-3">
                                    <span class="badge bg-light text-dark border border-secondary">{{ $loop->iteration + ($riwayatRekamMedis->currentPage() - 1) * $riwayatRekamMedis->perPage() }}</span>
                                </td>
                                <td>
                                    <div>
                                        <span class="fw-semibold text-dark">{{ \Carbon\Carbon::parse($rekam->created_at)->format('d/m/Y') }}</span>
                                        <br>
                                        <small class="text-muted" style="font-size: 11px;">{{ \Carbon\Carbon::parse($rekam->created_at)->format('H:i') }} WIB</small>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img src="https://ui-avatars.com/api/?name={{ $rekam->konsultasi->pasien->nama ?? 'Pasien' }}&background=random&size=35" 
                                             class="rounded-circle me-2" alt="Avatar">
                                        <div>
                                            <h6 class="mb-0 text-dark small fw-bold">{{ $rekam->konsultasi->pasien->nama ?? 'Nama Tidak Ditemukan' }}</h6>
                                            <small class="text-muted" style="font-size: 11px;">
                                                NIK: {{ $rekam->konsultasi->pasien->nik ?? '-' }}
                                            </small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-warning text-dark">{{ $rekam->diagnosis ?? '-' }}</span>
                                </td>
                                <td>
                                    <small class="text-muted">{{ Str::limit($rekam->tindakan ?? '-', 50) }}</small>
                                </td>
                                <td>
                                    <span class="fw-bold text-success">Rp {{ number_format($rekam->biaya_total ?? 0, 0, ',', '.') }}</span>
                                </td>
                                <td class="text-end pe-3">
                                    <button class="btn btn-sm btn-info rounded-pill px-3" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#detailModal{{ $rekam->rekam_medis_id }}">
                                        <i class="fa-solid fa-eye me-1"></i> Detail
                                    </button>
                                </td>
                            </tr>

                            {{-- Modal Detail Rekam Medis --}}
                            <div class="modal fade" id="detailModal{{ $rekam->rekam_medis_id }}" tabindex="-1" aria-labelledby="detailModalLabel{{ $rekam->rekam_medis_id }}" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header bg-info text-white">
                                            <h5 class="modal-title" id="detailModalLabel{{ $rekam->rekam_medis_id }}">
                                                <i class="fa-solid fa-file-medical me-2"></i>Detail Rekam Medis
                                            </h5>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row mb-3">
                                                <div class="col-md-6">
                                                    <h6 class="text-muted small mb-1">Tanggal Pemeriksaan</h6>
                                                    <p class="mb-0 fw-bold">{{ \Carbon\Carbon::parse($rekam->created_at)->isoFormat('dddd, D MMMM Y [pukul] HH:mm') }} WIB</p>
                                                </div>
                                                <div class="col-md-6">
                                                    <h6 class="text-muted small mb-1">No. Rekam Medis</h6>
                                                    <p class="mb-0 fw-bold">RM-{{ str_pad($rekam->rekam_medis_id, 6, '0', STR_PAD_LEFT) }}</p>
                                                </div>
                                            </div>
                                            
                                            <hr>

                                            <div class="row mb-3">
                                                <div class="col-12">
                                                    <h6 class="text-muted small mb-2">Data Pasien</h6>
                                                    <div class="bg-light p-3 rounded">
                                                        <div class="row">
                                                            <div class="col-md-6 mb-2">
                                                                <strong>Nama:</strong> {{ $rekam->konsultasi->pasien->nama ?? '-' }}
                                                            </div>
                                                            <div class="col-md-6 mb-2">
                                                                <strong>NIK:</strong> {{ $rekam->konsultasi->pasien->nik ?? '-' }}
                                                            </div>
                                                            <div class="col-md-6 mb-2">
                                                                <strong>Jenis Kelamin:</strong> {{ $rekam->konsultasi->pasien->jenis_kelamin ?? '-' }}
                                                            </div>
                                                            <div class="col-md-6 mb-2">
                                                                <strong>Tanggal Lahir:</strong> {{ $rekam->konsultasi->pasien->tanggal_lahir ? \Carbon\Carbon::parse($rekam->konsultasi->pasien->tanggal_lahir)->format('d/m/Y') : '-' }}
                                                            </div>
                                                            <div class="col-12 mb-2">
                                                                <strong>Alamat:</strong> {{ $rekam->konsultasi->pasien->alamat ?? '-' }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row mb-3">
                                                <div class="col-12">
                                                    <h6 class="text-muted small mb-2">Keluhan Awal</h6>
                                                    <p class="mb-0 bg-light p-3 rounded">{{ $rekam->konsultasi->keluhan_awal ?? '-' }}</p>
                                                </div>
                                            </div>

                                            <div class="row mb-3">
                                                <div class="col-12">
                                                    <h6 class="text-muted small mb-2">Diagnosis</h6>
                                                    <p class="mb-0">
                                                        <span class="badge bg-warning text-dark fs-6">{{ $rekam->diagnosis ?? '-' }}</span>
                                                    </p>
                                                </div>
                                            </div>

                                            <div class="row mb-3">
                                                <div class="col-12">
                                                    <h6 class="text-muted small mb-2">Tindakan</h6>
                                                    <p class="mb-0 bg-light p-3 rounded">{{ $rekam->tindakan ?? '-' }}</p>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-12">
                                                    <h6 class="text-muted small mb-2">Biaya Total</h6>
                                                    <h4 class="mb-0 text-success fw-bold">Rp {{ number_format($rekam->biaya_total ?? 0, 0, ',', '.') }}</h4>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                            <button type="button" class="btn btn-primary">
                                                <i class="fa-solid fa-print me-1"></i> Cetak
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            @empty
                            <tr>
                                <td colspan="7" class="text-center py-5 text-muted empty-row">
                                    <div class="d-flex flex-column align-items-center">
                                        <i class="fa-solid fa-folder-open fa-3x mb-3"></i>
                                        <p class="mb-0">Belum ada riwayat rekam medis.</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                @if($riwayatRekamMedis->hasPages())
                <div class="d-flex justify-content-center mt-4">
                    {{ $riwayatRekamMedis->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>

</div>

{{-- Modal Tambah Rekam Medis --}}
<div class="modal fade" id="tambahRekamMedisModal" tabindex="-1" aria-labelledby="tambahRekamMedisModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title" id="tambahRekamMedisModalLabel">
                    <i class="fa-solid fa-plus me-2"></i>Tambah Rekam Medis Baru
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('dokter.rekam-medis.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="konsultasi_id" class="form-label">Konsultasi <span class="text-danger">*</span></label>
                        <select class="form-select" id="konsultasi_id" name="konsultasi_id" required>
                            <option value="">Pilih Konsultasi</option>
                            @foreach($konsultasis ?? [] as $konsultasi)
                                <option value="{{ $konsultasi->konsultasi_id }}">
                                    {{ $konsultasi->pasien->nama ?? 'Pasien' }} - 
                                    {{ \Carbon\Carbon::parse($konsultasi->tgl_kunjungan)->format('d/m/Y') }}
                                    ({{ $konsultasi->keluhan_awal ? Str::limit($konsultasi->keluhan_awal, 30) : 'Tidak ada keluhan' }})
                                </option>
                            @endforeach
                        </select>
                        <small class="text-muted">Pilih konsultasi yang sudah selesai dan belum memiliki rekam medis</small>
                    </div>
                    <div class="mb-3">
                        <label for="diagnosis" class="form-label">Diagnosis <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="diagnosis" name="diagnosis" placeholder="Masukkan diagnosis..." required>
                    </div>
                    <div class="mb-3">
                        <label for="tindakan" class="form-label">Tindakan / Resep Obat <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="tindakan" name="tindakan" rows="5" placeholder="Masukkan tindakan medis atau resep obat..." required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="biaya_total" class="form-label">Biaya Total <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="number" class="form-control" id="biaya_total" name="biaya_total" min="0" step="0.01" placeholder="0" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-info text-white">
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

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        console.log('Halaman Riwayat Pasien dimuat.');
    });
</script>
@endpush

