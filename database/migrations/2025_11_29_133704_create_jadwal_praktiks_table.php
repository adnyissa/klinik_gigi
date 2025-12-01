<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Pastikan tabel lama dihapus dulu jika ada (untuk menghindari konflik alter)
        Schema::dropIfExists('jadwal_praktiks');

        Schema::create('jadwal_praktiks', function (Blueprint $table) {
            $table->id('jadwal_id');
            
            // Relasi ke Tabel Dokter
            $table->foreignId('dokter_id')
                  ->constrained('dokters', 'dokter_id') // Sesuaikan nama tabel & PK dokter kamu
                  ->onDelete('cascade');
            
            // Menggunakan HARI (Senin-Minggu) bukan Tanggal spesifik
            // Agar jadwal ini berlaku mingguan/berulang
            $table->enum('hari', ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu']);
            
            $table->time('jam_mulai');
            $table->time('jam_selesai');
            
            $table->enum('status', ['Aktif', 'Libur'])->default('Aktif');
            
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('jadwal_praktiks');
    }
};