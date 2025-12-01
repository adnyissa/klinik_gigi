<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8|confirmed',
        ]);

        $user = User::create([
            'email' => $request->email,
            'name' => $request->name,
            'role' => 'pasien', // Default Pasien
            'password' => Hash::make($request->password),
        ]);

        Auth::login($user);

        // PERBAIKAN: Jangan ke 'dashboard' (karena rute itu tidak ada)
        // Arahkan ke halaman pasien
        return redirect()->route('pasien.index'); 
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Cek Login
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            
            // PENTING: Regenerate Session untuk keamanan & mencegah error null
            $request->session()->regenerate();

            // Pastikan user object ada
            if (Auth::user()) {
                $role = strtolower(trim(Auth::user()->role));

                switch ($role) {
                    case 'admin':
                        return redirect()->route('admin.dashboard'); // Gunakan route name biar aman
                    case 'dokter':
                        // Pastikan kamu punya route name 'dokter.jadwal' atau url yang sesuai
                        return redirect('/dokter/dashboard'); 
                    case 'kasir':
                        return redirect('/kasir/dashboard');
                    case 'pasien':
                        return redirect()->route('pasien.dashboard');
                    default:
                        Auth::logout();
                        return redirect('/login')->withErrors(['email' => 'Role tidak dikenali.']);
                }
            }
        }

        // Jika Gagal
        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login'); // Redirect ke login setelah logout
    }
}