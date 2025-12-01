<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Pasien; // Import Model yang dirujuk
use App\Models\Dokter; // Import Model yang dirujuk

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('konsultasis', function (Blueprint $table) {
            $table->id('konsultasi_id'); // Primary Key untuk tabel konsultasis
            
            // --- KOREKSI FOREIGN KEY (Gunakan constrained) ---
            
            // Relasi ke Pasien: Jika tabel pasien Anda bernama 'pasiens'
            // Gunakan foreignId()->constrained() untuk memastikan tipe data cocok (bigInt unsigned)
            $table->foreignId('pasien_id')->constrained('pasiens', 'pasien_id')->cascadeOnDelete();

            // Relasi ke Dokter: Jika tabel dokter Anda bernama 'dokters'
            $table->foreignId('dokter_id')->constrained('dokters', 'dokter_id')->cascadeOnDelete(); 

            // Relasi ke Jadwal: Jika tabel jadwal Anda bernama 'jadwal_praktiks'
            // CATATAN: Karena nama tabel ini custom, kita harus tentukan nama tabel dan kolom Primary Key-nya.
            // Asumsi: Primary Key di tabel 'jadwal_praktik' adalah 'jadwal_id'
            $table->foreignId('jadwal_id')->constrained('jadwal_praktiks', 'jadwal_id')->cascadeOnDelete();
            
            // Catatan: Jika Anda tidak ingin menggunakan nama tabel jamak Laravel (misal, ingin tetap 'pasien'),
            // pastikan Primary Key di tabel 'pasien' juga adalah 'pasien_id' dan tipenya sama.
            // Jika Anda ingin tetap menggunakan '.on('pasien')', maka pastikan migrasi 'pasiens' Anda:
            // 1. Dijalankan sebelum migrasi ini.
            // 2. Kolom pasien_id adalah Primary Key dan UNSIGNED BIGINT.

            $table->date('tgl_kunjungan');
            $table->text('keluhan_awal');
            $table->enum('status', ['Menunggu', 'Diperiksa', 'Selesai', 'Batal'])->default('Menunggu');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('konsultasis');
    }
};