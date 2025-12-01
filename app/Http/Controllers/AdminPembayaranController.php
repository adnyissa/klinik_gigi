<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\RekamMedis; 
use App\Models\Pembayaran; 

class AdminPembayaranController extends Controller
{
    /**
     * Menampilkan daftar Rekam Medis (RM) yang statusnya 'Selesai'
     * dan belum memiliki catatan pembayaran (belum dibayar).
     */
    public function index()
    {
        try {
            // Catatan: Ini membutuhkan relasi 'pembayaran()' di Model RekamMedis
            $rmUntukDibayar = RekamMedis::where('status', 'Selesai')
                ->whereDoesntHave('pembayaran') 
                ->with(['pasien', 'dokter']) 
                ->latest()
                ->get();

            return view('admin.pembayaran.index', compact('rmUntukDibayar'));
        } catch (\Exception $e) {
            // Handle error jika ada masalah database/relasi
            return redirect()->back()->with('error', 'Gagal memuat data tagihan: ' . $e->getMessage());
        }
    }

    /**
     * Mengambil detail tagihan berdasarkan ID Rekam Medis.
     * Dipanggil via AJAX untuk mengisi formulir pembayaran.
     */
    public function fetchDetail(Request $request)
    {
        $request->validate([
            'rekam_medis_id' => 'required|exists:rekam_medis,id',
        ]);

        try {
            // Menggunakan with(['pasien', 'dokter', 'layanan', 'obats'])
            // untuk memuat data relasi yang diperlukan dalam penghitungan
            $rekamMedis = RekamMedis::with(['pasien', 'dokter', 'layanan', 'obats'])
                ->findOrFail($request->rekam_medis_id);

            // 1. Definisikan Biaya Tetap (contoh: biaya jasa dokter)
            // Di aplikasi nyata, ini bisa diambil dari tabel konfigurasi atau dokter
            $biayaDokter = 200000; 

            // 2. Hitung Total Biaya Layanan dari relasi 'layanan'
            // ASUMSI: Relasi layanan() ada di RekamMedis dan Model Layanan memiliki kolom 'harga'.
            $biayaLayanan = $rekamMedis->layanan->sum('harga');
            
            // 3. Hitung Biaya Obat (Jika ada relasi obats)
            // ASUMSI: Relasi obats() ada di RekamMedis dan memiliki kolom 'subtotal' (atau 'harga').
            $biayaObat = $rekamMedis->obats->sum('subtotal') ?? 0; // Gunakan subtotal jika ada, atau 0

            // 4. Hitung Total Tagihan Keseluruhan
            $totalTagihan = $biayaDokter + $biayaLayanan + $biayaObat;

            return response()->json([
                'success' => true,
                'data' => [
                    'pasien_nama' => $rekamMedis->pasien->nama,
                    'total_tagihan' => $totalTagihan,
                    'biaya_dokter' => $biayaDokter,
                    'biaya_layanan' => $biayaLayanan,
                    'biaya_obat' => $biayaObat,
                    'detail_rm' => $rekamMedis,
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil detail tagihan: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Memproses dan menyimpan data pembayaran yang baru.
     */
    public function store(Request $request)
    {
        $request->validate([
            'rekam_medis_id' => 'required|exists:rekam_medis,id', 
            'total_bayar' => 'required|numeric|min:0', 
            'metode_pembayaran' => 'required|string|max:50',
        ]);

        DB::beginTransaction();
        try {
            $rekamMedis = RekamMedis::findOrFail($request->rekam_medis_id);
            
            // Kolom 'rekam_medis_id' sudah KONSISTEN dengan Model Pembayaran
            $pembayaran = Pembayaran::create([
                'rekam_medis_id' => $rekamMedis->id, 
                'pasien_id' => $rekamMedis->pasien_id,
                'tgl_pembayaran' => now(), 
                'total_biaya' => $request->total_bayar, 
                'metode_pembayaran' => $request->metode_pembayaran,
                'status' => 'Lunas', 
            ]);

            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Pembayaran berhasil dicatat!',
                'redirect' => route('admin.pembayaran.index')
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal memproses pembayaran: ' . $e->getMessage(),
            ], 500);
        }
    }
}