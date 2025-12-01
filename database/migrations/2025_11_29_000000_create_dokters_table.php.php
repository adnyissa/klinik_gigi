<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
{
    Schema::create('dokters', function (Blueprint $table) {
        $table->id('dokter_id'); // PK tetap Auto Increment (1, 2, 3...)
        
        $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
        
        // --- IDENTITAS DOKTER ---
        $table->string('nama');
        $table->string('no_sip')->unique(); // Tambah kolom ini (Surat Izin Praktik)
        $table->string('spesialisasi');
        
        $table->string('nomor_telepon')->unique();
        $table->string('email')->unique();
        $table->timestamps();
    });
}

    public function down()
    {
        Schema::dropIfExists('dokters');
    }
};