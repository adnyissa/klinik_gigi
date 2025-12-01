@extends('layouts.pasien_user')

@section('title', 'Rekam Medis')

@section('content')
<div class="container-fluid pt-4 px-4">
    <div class="row">
        <div class="col-12">
            <div class="bg-light rounded p-4 shadow-sm">

                <!-- HEADER HALAMAN -->
                <div class="d-flex align-items-center justify-content-between mb-4">
                    <div>
                        <h5 class="mb-1 fw-bold text-primary">
                            <i class="fa-solid fa-file-medical me-2"></i>Rekam Medis Anda
                        </h5>
                        <p class="mb-0 text-muted small">
                            Riwayat pemeriksaan dan tindakan yang pernah Anda lakukan di Klinik Gigi Semarang.
                        </p>
                    </div>
                </div>

                @if(!$hasPasien)
                    <div class="alert alert-warning mb-0">
                        <i class="fa-solid fa-circle-info me-2"></i>
                        Data pasien untuk akun ini belum terhubung. Silakan hubungi petugas klinik.
                    </div>
                @else
                    <!-- TABEL REKAM MEDIS -->
                    <div class="table-responsive">
                        <table class="table table-striped table-hover align-middle">
                            <thead class="table-primary">
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Tanggal Kunjungan</th>
                                    <th scope="col">Dokter</th>
                                    <th scope="col">Diagnosis</th>
                                    <th scope="col">Tindakan</th>
                                    <th scope="col">Biaya Total</th>
                                    <th scope="col">Kasir</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($rekamMedis as $item)
                                    <tr>
                                        <th scope="row">
                                            {{ $loop->iteration + ($rekamMedis->currentPage() - 1) * $rekamMedis->perPage() }}
                                        </th>
                                        <td>
                                            @if($item->konsultasi && $item->konsultasi->tgl_kunjungan)
                                                {{ \Carbon\Carbon::parse($item->konsultasi->tgl_kunjungan)->translatedFormat('d F Y') }}
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>
                                            Dr. {{ $item->dokter->nama ?? '-' }}<br>
                                            <span class="small text-muted">
                                                {{ $item->dokter->spesialisasi ?? '' }}
                                            </span>
                                        </td>
                                        <td>{{ $item->diagnosis }}</td>
                                        <td>
                                            <span class="small d-block" style="max-width: 260px;">
                                                {{ \Illuminate\Support\Str::limit($item->tindakan, 80) }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="fw-bold text-success">
                                                Rp {{ number_format($item->biaya_total, 0, ',', '.') }}
                                            </span>
                                        </td>
                                        <td>{{ $item->kasir->nama ?? '-' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center text-muted py-5">
                                            <i class="fa-solid fa-inbox fa-3x mb-3 opacity-25"></i>
                                            <p class="mb-0">Belum ada rekam medis yang tercatat.</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if(method_exists($rekamMedis, 'links'))
                        <div class="d-flex justify-content-center mt-3">
                            {{ $rekamMedis->links() }}
                        </div>
                    @endif
                @endif

            </div>
        </div>
    </div>
</div>
@endsection


