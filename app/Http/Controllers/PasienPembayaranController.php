<?php

namespace App\Http\Controllers;

use App\Models\Pembayaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PasienPembayaranController extends Controller
{
    /**
     * Tampilkan daftar pembayaran milik pasien yang sedang login.
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        // Asumsi: tabel users punya kolom pasien_id sebagai FK ke tabel pasiens
        $pasienId = $user->pasien_id ?? null;

        if (!$pasienId) {
            $pembayarans = collect([]);
            $hasPasien = false;

            return view('pasien.pembayaran.index', compact('pembayarans', 'hasPasien'));
        }

        // Filter opsional status (Lunas, Belum Lunas, dll) jika nanti ditambahkan
        $status = $request->get('status');

        $query = Pembayaran::with(['rekamMedis', 'rekamMedis.dokter'])
            ->where('pasien_id', $pasienId)
            ->orderByDesc('tgl_pembayaran');

        if ($status) {
            $query->where('status', $status);
        }

        $pembayarans = $query->paginate(10)->withQueryString();
        $hasPasien = true;

        return view('pasien.pembayaran.index', compact('pembayarans', 'hasPasien', 'status'));
    }
}


