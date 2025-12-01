@extends('layouts.admin') 

@section('title', 'Manajemen Pembayaran (Kasir)')

@section('content')

<div class="container-fluid pt-4 px-4">
    <h3 class="text-dark mb-4">
        <i class="fa-solid fa-wallet me-2"></i> Daftar Tagihan Pasien
    </h3>

    {{-- Alert Messages dari Session Laravel --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-12">
            <div class="bg-white rounded p-4 shadow-sm">
                <h5 class="mb-4 text-primary">Rekam Medis Siap Bayar (Status: Selesai)</h5>
                
                <div class="table-responsive">
                    <table class="table table-striped table-hover align-middle">
                        <thead class="bg-light">
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">No. RM</th>
                                <th scope="col">Nama Pasien</th>
                                <th scope="col">Dokter</th>
                                <th scope="col">Tgl. Selesai</th>
                                <th scope="col">Status Tagihan</th>
                                <th scope="col">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{-- Looping data rekam medis yang siap dibayar --}}
                            {{-- Catatan: Variabel $rmUntukDibayar harus dikirim dari Controller --}}
                            @forelse ($rmUntukDibayar as $rm)
                            <tr>
                                <th scope="row">{{ $loop->iteration }}</th>
                                <td>RM-{{ $rm->id }}</td>
                                <td>{{ $rm->pasien->nama ?? 'N/A' }}</td>
                                <td>{{ $rm->dokter->nama ?? 'N/A' }}</td>
                                <td>{{ \Carbon\Carbon::parse($rm->tanggal_selesai)->format('d M Y') }}</td>
                                <td><span class="badge bg-warning text-dark">Belum Bayar</span></td>
                                <td>
                                    <button class="btn btn-sm btn-primary bayar-btn" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#pembayaranModal"
                                            data-rm-id="{{ $rm->id }}"
                                            data-pasien-nama="{{ $rm->pasien->nama ?? 'N/A' }}">
                                        <i class="fa-solid fa-file-invoice"></i> Proses Pembayaran
                                    </button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted">Tidak ada tagihan yang menunggu pembayaran saat ini.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</div>

{{-- MODAL PEMBAYARAN --}}
<div class="modal fade" id="pembayaranModal" tabindex="-1" aria-labelledby="pembayaranModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="pembayaranModalLabel"><i class="fa-solid fa-receipt me-2"></i> Proses Pembayaran Tagihan</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formPembayaran" method="POST"> 
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="rekam_medis_id" id="modal_rekam_medis_id">
                    {{-- Hidden Inputs untuk URL AJAX Laravel --}}
                    <input type="hidden" id="url_fetch_detail" value="{{ route('admin.pembayaran.fetchDetail') }}">
                    <input type="hidden" id="url_store_pembayaran" value="{{ route('admin.pembayaran.store') }}">
                    <input type="hidden" id="url_redirect_success" value="{{ route('admin.pembayaran.index') }}">
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="pasien_nama" class="form-label">Pasien</label>
                            <input type="text" class="form-control" id="pasien_nama" readonly>
                        </div>
                        <div class="col-md-6">
                            <label for="rm_id_display" class="form-label">No. Rekam Medis</label>
                            <input type="text" class="form-control" id="rm_id_display" readonly>
                        </div>
                    </div>
                    
                    <h6 class="mt-4 mb-3 text-info"><i class="fa-solid fa-list-check me-1"></i> Detail Tagihan</h6>
                    <div id="detailTagihan" class="border p-3 rounded bg-light mb-4">
                        <p class="text-center text-muted">Memuat detail tagihan...</p>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="metode_pembayaran" class="form-label">Metode Pembayaran <span class="text-danger">*</span></label>
                            <select class="form-select" id="metode_pembayaran" name="metode_pembayaran" required>
                                <option value="" disabled selected>Pilih Metode</option>
                                <option value="Tunai">Tunai</option>
                                <option value="Transfer Bank">Transfer Bank</option>
                                <option value="Debit Card">Debit Card</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="total_bayar" class="form-label">Jumlah Dibayar (Total Tagihan) <span class="text-danger">*</span></label>
                            <input type="number" class="form-control form-control-lg text-end fw-bold text-success" 
                                id="total_bayar" name="total_bayar" required min="0" readonly>
                        </div>
                    </div>

                    {{-- Alert untuk pesan error AJAX --}}
                    <div class="alert alert-danger d-none" id="ajaxErrorAlert"></div>
                    {{-- Alert untuk pesan sukses AJAX (walaupun akan redirect, ini disiapkan) --}}
                    <div class="alert alert-success d-none" id="ajaxSuccessAlert"></div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success" id="submitPembayaranBtn" disabled>
                        <i class="fa-solid fa-check-circle"></i> Catat Pembayaran
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="{{ asset('assets2/js/main.js') }}"></script>
@endpush