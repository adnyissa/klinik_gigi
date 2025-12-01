<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Dokter; // Model Data Dokter
use App\Models\User;   // Model Akun Login
use Illuminate\Support\Facades\Hash; // Untuk enkripsi password
use Illuminate\Support\Facades\DB;   // Untuk transaksi database

class AdminDokterController extends Controller
{
    /**
     * Menampilkan daftar dokter.
     */
    public function index()
    {
        // Ambil data dokter terbaru dari database
        $dokters = Dokter::latest()->get();
        return view('admin.dokter.index', compact('dokters'));
    }

    /**
     * Menyimpan data dokter baru (Store).
     */
    public function store(Request $request)
    {
        // 1. Validasi Input
        $request->validate([
            'nama' => 'required|string|max:255',
            'no_sip' => 'required|string|unique:dokters,no_sip', // SIP Wajib Unik
            'spesialisasi' => 'required|string',
            'nomor_telepon' => 'required|numeric|unique:dokters,nomor_telepon',
            'email' => 'required|email|unique:users,email', // Email cek di tabel users
            'password' => 'required|min:8', // Password wajib saat tambah baru
        ]);

        // 2. Proses Simpan ke 2 Tabel (User & Dokter)
        // Gunakan Transaction: Jika satu gagal, semua dibatalkan (Rollback)
        DB::transaction(function () use ($request) {
            
            // A. Buat Akun Login di tabel 'users'
            $user = User::create([
                'name' => $request->nama,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'dokter', // Role otomatis diset dokter
            ]);

            // B. Buat Data Profil di tabel 'dokters'
            Dokter::create([
                'user_id' => $user->id, // Sambungkan dengan ID user yang baru dibuat
                'nama' => $request->nama,
                'no_sip' => $request->no_sip, // Simpan SIP
                'spesialisasi' => $request->spesialisasi,
                'nomor_telepon' => $request->nomor_telepon,
                'email' => $request->email,
            ]);
        });

        // 3. Kembali ke halaman dan tampilkan pesan sukses
        return redirect()->back()->with('success', 'Data Dokter berhasil ditambahkan!');
    }

    /**
     * Mengupdate data dokter (Update).
     */
    public function update(Request $request, $id)
    {
        // Cari data dokter berdasarkan ID
        $dokter = Dokter::findOrFail($id);
        
        // Cari user yang emailnya sama dengan dokter ini (untuk update login)
        $user = User::where('email', $dokter->email)->first();

        // Validasi
        $request->validate([
            'nama' => 'required|string|max:255',
            'spesialisasi' => 'required|string',
            
            // Validasi Unik dengan PENGECUALIAN (Ignore) ID saat ini
            // Artinya: Boleh pakai SIP/HP/Email lama milik sendiri, tapi tidak boleh pakai punya orang lain
            'no_sip' => 'required|string|unique:dokters,no_sip,'.$id.',dokter_id',
            'nomor_telepon' => 'required|numeric|unique:dokters,nomor_telepon,'.$id.',dokter_id',
            'email' => 'required|email|unique:users,email,'.$user->id,
        ]);

        DB::transaction(function () use ($request, $dokter, $user) {
            // A. Update Data Dokter
            $dokter->update([
                'nama' => $request->nama,
                'no_sip' => $request->no_sip,
                'spesialisasi' => $request->spesialisasi,
                'nomor_telepon' => $request->nomor_telepon,
                'email' => $request->email,
            ]);

            // B. Update Data User (Jika User Ditemukan)
            if ($user) {
                $userData = [
                    'name' => $request->nama,
                    'email' => $request->email,
                ];

                // Cek apakah admin mengisi password baru?
                // Jika kosong, berarti password tidak diubah
                if ($request->filled('password')) {
                    $userData['password'] = Hash::make($request->password);
                }

                $user->update($userData);
            }
        });

        return redirect()->back()->with('success', 'Data Dokter berhasil diperbarui!');
    }

    /**
     * Menghapus data dokter (Destroy).
     */
    public function destroy($id)
    {
        $dokter = Dokter::findOrFail($id);
        
        // Hapus juga akun loginnya agar tidak jadi sampah data
        $user = User::where('email', $dokter->email)->first();
        
        if ($user) {
            $user->delete();
        }

        $dokter->delete();

        return redirect()->back()->with('success', 'Data Dokter dan Akun Login berhasil dihapus!');
    }
}