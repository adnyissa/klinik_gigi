<?php

namespace App\Http\Controllers; // Namespace langsung di Controllers

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kasir;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class AdminKasirController extends Controller
{
    public function index()
    {
        // Eager load 'user' agar query lebih cepat
        $kasirs = Kasir::with('user')->latest('kasir_id')->get();
        return view('admin.kasir.index', compact('kasirs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama'        => 'required|string|max:255',
            'email'       => 'required|email|unique:users,email',
            'password'    => 'required|min:6',
            'no_hp'       => 'required|string|max:15',
            'shift_kerja' => 'required|in:Pagi,Siang,Malam',
            'alamat'      => 'required|string',
        ]);

        DB::transaction(function () use ($request) {
            // 1. Buat Akun User Login
            $user = User::create([
                'name'     => $request->nama,
                'email'    => $request->email,
                'password' => Hash::make($request->password),
                'role'     => 'kasir',
            ]);

            // 2. Buat Data Profil Kasir
            Kasir::create([
                'user_id'     => $user->id,
                'no_hp'       => $request->no_hp,
                'shift_kerja' => $request->shift_kerja,
                'alamat'      => $request->alamat,
            ]);
        });

        return redirect()->back()->with('success', 'Akun Kasir berhasil dibuat.');
    }

    public function update(Request $request, $id)
    {
        $kasir = Kasir::findOrFail($id);
        $user  = $kasir->user;

        $request->validate([
            'nama'        => 'required|string|max:255',
            'email'       => 'required|email|unique:users,email,' . $user->id,
            'no_hp'       => 'required|string|max:15',
            'shift_kerja' => 'required|in:Pagi,Siang,Malam',
            'alamat'      => 'required|string',
        ]);

        DB::transaction(function () use ($request, $user, $kasir) {
            // Update User
            $userData = [
                'name'  => $request->nama,
                'email' => $request->email,
            ];
            // Update password hanya jika diisi
            if ($request->filled('password')) {
                $userData['password'] = Hash::make($request->password);
            }
            $user->update($userData);

            // Update Kasir
            $kasir->update([
                'no_hp'       => $request->no_hp,
                'shift_kerja' => $request->shift_kerja,
                'alamat'      => $request->alamat,
            ]);
        });

        return redirect()->back()->with('success', 'Data Kasir berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $kasir = Kasir::findOrFail($id);
        // Hapus User (Otomatis data kasir terhapus karena on delete cascade di migrasi)
        $kasir->user->delete(); 
        
        return redirect()->back()->with('success', 'Akun Kasir berhasil dihapus.');
    }
}