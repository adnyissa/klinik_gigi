<?php

namespace App\Http\Controllers; // Namespace di root Controllers (sesuai file aslimu)

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pasien;

class AdminPasienController extends Controller
{
    /**
     * Menampilkan daftar semua pasien.
     */
    public function index()
    {
        // Mengambil data urut dari yang terbaru
        $pasiens = Pasien::latest('pasien_id')->get();
        return view('admin.pasien.index', compact('pasiens'));
    }

    /**
     * Menyimpan data pasien baru.
     */
    public function store(Request $request)
    {
        // 1. VALIDASI (NAMA FIELD HARUS SAMA DENGAN DATABASE & FORM HTML)
        // PERHATIKAN: Saya sudah ganti tgl_lahir jadi tanggal_lahir, dll.
        $validated = $request->validate([
            'nama'            => 'required|string|max:255',
            'nik'             => 'required|numeric|digits:16|unique:pasiens,nik',
            
            // PERBAIKAN: Gunakan 'tanggal_lahir' (sesuai database & HTML)
            'tanggal_lahir'   => 'required|date',
            
            // PERBAIKAN: Value harus 'Laki-laki' atau 'Perempuan'
            'jenis_kelamin'   => 'required|in:Laki-laki,Perempuan',
            
            // PERBAIKAN: Gunakan 'nomor_telepon' (sesuai database & HTML)
            'nomor_telepon'   => 'nullable|string|max:15',
            
            'alamat'          => 'nullable|string',
        ]);

        // 2. SIMPAN DATA
        // Langsung create pakai data validasi yang sudah benar namanya
        Pasien::create($validated);

        return redirect()->route('admin.pasien.index')
            ->with('success', 'Pasien berhasil ditambahkan');
    }

    /**
     * Memperbarui data pasien yang sudah ada.
     */
    public function update(Request $request, $id)
    {
        // Cari pasien berdasarkan ID (karena route resource kirim ID, bukan model binding otomatis jika namanya beda)
        $pasien = Pasien::findOrFail($id);

        // 1. VALIDASI UPDATE
        $validated = $request->validate([
            'nama'            => 'required|string|max:255',
            
            // Unik NIK kecuali punya dia sendiri
            'nik'             => 'required|numeric|digits:16|unique:pasiens,nik,'.$pasien->pasien_id.',pasien_id',
            
            'tanggal_lahir'   => 'required|date',
            'jenis_kelamin'   => 'required|in:Laki-laki,Perempuan',
            'nomor_telepon'   => 'nullable|string|max:15',
            'alamat'          => 'nullable|string',
        ]);

        // 2. UPDATE DATA
        $pasien->update($validated);

        return redirect()->route('admin.pasien.index')
            ->with('success', 'Data pasien berhasil diperbarui');
    }

    /**
     * Menghapus data pasien.
     */
    public function destroy($id)
    {
        $pasien = Pasien::findOrFail($id);
        $pasien->delete();
        
        return redirect()->route('admin.pasien.index')
            ->with('success', 'Pasien berhasil dihapus');
    }
}