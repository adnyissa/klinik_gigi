@extends('layouts.admin')

@section('content')
<div class="col-sm-12 col-xl-6 mx-auto">
    <div class="bg-light rounded h-100 p-4 shadow-sm">
        <h6 class="mb-4">Buat Akun Baru (Dokter/Kasir)</h6>
        
        <form action="{{ route('admin.users.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label">Nama Lengkap</label>
                <input type="text" name="name" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Email Address</label>
                <input type="email" name="email" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Role (Jabatan)</label>
                <select class="form-select" name="role">
                    <option value="dokter">Dokter</option>
                    <option value="kasir">Kasir</option>
                    <option value="admin">Admin</option>
                </select>
            </div>
            <div class="alert alert-info">
                Password default untuk akun baru adalah: <strong>klinik123</strong>
            </div>
            <button type="submit" class="btn btn-primary">Simpan User</button>
        </form>
    </div>
</div>
@endsection