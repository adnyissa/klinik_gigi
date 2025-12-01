<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // Menampilkan Form
    public function create()
    {
        return view('admin.users.create');
    }

    // Menyimpan Data ke Database
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'role' => 'required', // Admin milih role apa
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make('klinik123'), // Password Default
            'role' => $request->role,
        ]);

        return redirect('/admin/dashboard')->with('success', 'User berhasil ditambahkan!');
    }
}