<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kasir;
use App\Models\Pasien;
use App\Models\Dokter;
use App\Models\Konsultasi;
use App\Models\RekamMedis;

class AdminRMController extends Controller
{
    /**
     * Menampilkan daftar semua Rekam Medis.
     */
    public function index()
    {
        // 1. Mengambil data Rekam Medis, diurutkan berdasarkan tanggal terbaru.
        $rekamMedis = RekamMedis::with(['pasien', 'dokter'])
            ->latest('created_at') 
            ->get();
            
        // 2. Ambil data pendukung untuk Form/Dropdown
        $dokters = Dokter::select('dokter_id', 'nama')->get();
        $pasiens = Pasien::select('pasien_id', 'nama')->get();
        
        // PERBAIKAN FINAL: Mengganti 'nama' (atau 'jenis_konsultasi') dengan 'tgl_kunjungan',
        // karena ini adalah kolom deskriptif yang ada di tabel 'konsultasis'.
        $konsultasis = Konsultasi::select('konsultasi_id', 'tgl_kunjungan')->get(); 
        
        // 3. Ambil data Kasir untuk dropdown
        $kasirs = Kasir::with('user')->orderBy('kasir_id')->get(); 

        return view('admin.rm.index', compact(
            'rekamMedis', 
            'dokters', 
            'pasiens', 
            'konsultasis',
            'kasirs'
        ));
    }
    

    /**
     * Menyimpan data Rekam Medis baru.
     */
    public function store(Request $request)
    {
        // Validasi data input
        $validatedData = $request->validate([
            'pasien_id' => 'required|exists:pasiens,pasien_id',
            'dokter_id' => 'required|exists:dokters,dokter_id',
            'kasir_id' => 'required|exists:kasirs,kasir_id', // Pastikan kasir_id ada
            'tanggal' => 'required|date',
            'jenis_konsultasi' => 'required|string|max:50',
            'keluhan' => 'required|string',
            'diagnosa' => 'nullable|string',
            'tindakan' => 'nullable|string',
        ]);

        try {
            RekamMedis::create($validatedData);

            return redirect()->route('admin.rekam_medis.index')
                             ->with('success', 'Data Rekam Medis berhasil ditambahkan.');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Gagal menyimpan data Rekam Medis. Error: ' . $e->getMessage()]);
        }
    }

    /**
     * Menampilkan detail Rekam Medis (biasanya untuk Edit form)
     */
    public function show($id)
    {
        $rekamMedis = RekamMedis::with(['pasien', 'dokter.user', 'kasir.user'])->findOrFail($id);
        
        // Data pendukung untuk form Edit
        $pasiens = Pasien::select('pasien_id', 'nama_lengkap', 'no_rm')->orderBy('nama_lengkap')->get();
        $dokters = Dokter::with('user')->get();
        $kasirs = Kasir::with('user')->get();
        $konsultasis = ['Umum', 'Gigi', 'Spesialis Anak', 'Spesialis Kandungan'];

        // Anda mungkin ingin me-return view edit
        return view('admin.rm.edit', compact(
            'rekamMedis', 'pasiens', 'dokters', 'kasirs', 'konsultasis'
        ));
    }

    /**
     * Memperbarui data Rekam Medis.
     */
    public function update(Request $request, $id)
    {
        $rekamMedis = RekamMedis::findOrFail($id);

        $validatedData = $request->validate([
            'pasien_id' => 'required|exists:pasiens,pasien_id',
            'dokter_id' => 'required|exists:dokters,dokter_id',
            'kasir_id' => 'required|exists:kasirs,kasir_id',
            'tanggal' => 'required|date',
            'jenis_konsultasi' => 'required|string|max:50',
            'keluhan' => 'required|string',
            'diagnosa' => 'nullable|string',
            'tindakan' => 'nullable|string',
        ]);

        try {
            $rekamMedis->update($validatedData);

            return redirect()->route('admin.rm.index')
                             ->with('success', 'Data Rekam Medis berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Gagal memperbarui data Rekam Medis.']);
        }
    }

    /**
     * Menghapus Rekam Medis.
     */
    public function destroy($id)
    {
        try {
            $rekamMedis = RekamMedis::findOrFail($id);
            $rekamMedis->delete();

            return redirect()->route('admin.rm.index')
                             ->with('success', 'Data Rekam Medis berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Gagal menghapus data Rekam Medis.']);
        }
    }
}