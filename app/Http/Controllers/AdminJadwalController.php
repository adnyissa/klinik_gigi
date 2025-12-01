<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\JadwalPraktik;
use App\Models\Dokter;

class AdminJadwalController extends Controller
{
    public function index()
    {
        // Ambil jadwal beserta data dokternya, urutkan berdasarkan hari
        $jadwals = JadwalPraktik::with('dokter')
                    ->orderByRaw("FIELD(hari, 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu')")
                    ->orderBy('jam_mulai')
                    ->get();
        
        // Ambil list dokter untuk dropdown di modal tambah
        $dokters = Dokter::all();

        return view('admin.jadwal.index', compact('jadwals', 'dokters'));
    }

    public function store(Request $request)
    {
        // Validasi input
        $validatedData = $request->validate([
            'dokter_id'   => 'required|exists:dokters,dokter_id',
            'hari'        => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu,Minggu',
            'jam_mulai'   => 'required',
            'jam_selesai' => 'required|after:jam_mulai',
            'status'      => 'required|in:Aktif,Libur',
        ]);

        // Gunakan data yang sudah divalidasi untuk membuat record
        // Menggunakan create() dengan data yang sudah difilter/divalidasi adalah praktik terbaik
        JadwalPraktik::create($validatedData);

        return redirect()->back()->with('success', 'Jadwal praktik berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $jadwal = JadwalPraktik::findOrFail($id);

        // Validasi input
        $validatedData = $request->validate([
            'dokter_id'   => 'required|exists:dokters,dokter_id',
            'hari'        => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu,Minggu',
            'jam_mulai'   => 'required',
            'jam_selesai' => 'required|after:jam_mulai',
            'status'      => 'required|in:Aktif,Libur',
        ]);

        // Gunakan data yang sudah divalidasi untuk update
        $jadwal->update($validatedData);

        return redirect()->back()->with('success', 'Jadwal praktik berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $jadwal = JadwalPraktik::findOrFail($id);
        $jadwal->delete();

        return redirect()->back()->with('success', 'Jadwal praktik berhasil dihapus.');
    }
}