@extends('layouts.pasien_user')

@section('title', 'Pembayaran')

@section('content')
<div class="container-fluid pt-4 px-4">
    <div class="row">
        <div class="col-12">
            <div class="bg-light rounded p-4 shadow-sm">

                <!-- HEADER HALAMAN -->
                <div class="d-flex align-items-center justify-content-between mb-4">
                    <div>
                        <h5 class="mb-1 fw-bold text-primary">
                            <i class="fa-solid fa-wallet me-2"></i>Riwayat Pembayaran
                        </h5>
                        <p class="mb-0 text-muted small">
                            Lihat riwayat transaksi dan status pembayaran kunjungan Anda.
                        </p>
                    </div>

                    @if($hasPasien ?? false)
                        <form method="GET" class="d-flex align-items-center gap-2">
                            <label for="status" class="small text-muted mb-0">Filter Status:</label>
                            <select name="status" id="status" class="form-select form-select-sm" onchange="this.form.submit()">
                                <option value="">Semua</option>
                                <option value="Lunas" {{ ($status ?? '') === 'Lunas' ? 'selected' : '' }}>Lunas</option>
                                <option value="Belum Lunas" {{ ($status ?? '') === 'Belum Lunas' ? 'selected' : '' }}>Belum Lunas</option>
                            </select>
                        </form>
                    @endif
                </div>

                @if(!($hasPasien ?? false))
                    <div class="alert alert-warning mb-0">
                        <i class="fa-solid fa-circle-info me-2"></i>
                        Data pasien untuk akun ini belum terhubung. Silakan hubungi petugas klinik.
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-striped table-hover align-middle">
                            <thead class="table-primary">
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Tanggal Pembayaran</th>
                                    <th scope="col">Dokter / Kunjungan</th>
                                    <th scope="col">Metode</th>
                                    <th scope="col">Jumlah Dibayar</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Bukti</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($pembayarans as $bayar)
                                    <tr>
                                        <th scope="row">
                                            {{ $loop->iteration + ($pembayarans->currentPage() - 1) * $pembayarans->perPage() }}
                                        </th>
                                        <td>
                                            {{ \Carbon\Carbon::parse($bayar->tgl_pembayaran)->translatedFormat('d F Y') }}
                                        </td>
                                        <td>
                                            @if($bayar->rekamMedis && $bayar->rekamMedis->dokter)
                                                Dr. {{ $bayar->rekamMedis->dokter->nama }}
                                                <div class="small text-muted">
                                                    {{ $bayar->rekamMedis->dokter->spesialisasi ?? '' }}
                                                </div>
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>{{ $bayar->metode_pembayaran }}</td>
                                        <td>
                                            <span class="fw-bold text-success">
                                                Rp {{ number_format($bayar->jumlah_dibayar, 0, ',', '.') }}
                                            </span>
                                        </td>
                                        <td>
                                            @php
                                                $badgeClass = match($bayar->status ?? '') {
                                                    'Lunas' => 'bg-success',
                                                    'Belum Lunas' => 'bg-warning text-dark',
                                                    default => 'bg-secondary'
                                                };
                                            @endphp
                                            <span class="badge {{ $badgeClass }}">
                                                {{ $bayar->status ?? '-' }}
                                            </span>
                                        </td>
                                        <td>
                                            @if($bayar->bukti_pembayaran)
                                                <a href="{{ asset('storage/'.$bayar->bukti_pembayaran) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                                    Lihat
                                                </a>
                                            @else
                                                <span class="text-muted small">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center text-muted py-5">
                                            <i class="fa-solid fa-inbox fa-3x mb-3 opacity-25"></i>
                                            <p class="mb-0">Belum ada data pembayaran.</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if(method_exists($pembayarans, 'links'))
                        <div class="d-flex justify-content-center mt-3">
                            {{ $pembayarans->links() }}
                        </div>
                    @endif
                @endif

            </div>
        </div>
    </div>
</div>
@endsection


