<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pasien;

class PasienController extends Controller
{
    public function index()
    {
        $pasiens = Pasien::latest()->get();
        return view('pasien.dashboard', compact('pasiens'));
    }

    public function store(Request $request)
    {
        // DEBUGGING: Cek data yang dikirim dari form HTML
        // dd($request->all()); // Hapus komentar ini jika mau melihat isi data mentah

        // --- VALIDASI SESUAI MIGRASI ---
        $validated = $request->validate([
            // 1. NAMA
            'nama' => 'required|string|max:255',

            // 2. NIK (Wajib, Angka, 16 digit, Unik)
            'nik' => 'required|numeric|digits:16|unique:pasiens,nik',

            // 3. TANGGAL LAHIR (Perhatikan: namanya tanggal_lahir, BUKAN tgl_lahir)
            'tanggal_lahir' => 'required|date',

            // 4. NOMOR TELEPON (Perhatikan: namanya nomor_telepon, BUKAN no_hp)
            // Di migrasi kamu nullable, jadi di sini juga nullable
            'nomor_telepon' => 'nullable|string|max:15',

            // 5. JENIS KELAMIN (Harus sama persis dengan value di <option> HTML)
            // Karena di HTML valuenya "Laki-laki" dan "Perempuan", validasi harus cocok
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',

            // 6. ALAMAT
            'alamat' => 'nullable|string',
        ]);

        // Simpan ke Database
        Pasien::create($validated);

        return redirect()->route('pasien.dashboard')
            ->with('success', 'Data pasien berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $pasien = Pasien::findOrFail($id);

        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            // Validasi unik NIK, kecuali milik pasien ini sendiri
            'nik' => 'required|numeric|digits:16|unique:pasiens,nik,'.$pasien->pasien_id.',pasien_id',
            'tanggal_lahir' => 'required|date',
            'nomor_telepon' => 'nullable|string|max:15',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'alamat' => 'nullable|string',
        ]);

        $pasien->update($validated);

        return redirect()->route('pasien.index')
            ->with('success', 'Data pasien berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $pasien = Pasien::findOrFail($id);
        $pasien->delete();
        return redirect()->route('pasien.index')
            ->with('success', 'Data pasien berhasil dihapus.');
    }
}