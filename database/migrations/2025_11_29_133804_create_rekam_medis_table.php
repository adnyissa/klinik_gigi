<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rekam_medis', function (Blueprint $table) {
            $table->id('rekam_medis_id');
            
            // --- Relasi Foreign Key ---
            // Menggunakan tipe data yang sama dengan PK (UNSIGNED BIGINT)
            
            $table->unsignedBigInteger('konsultasi_id');
            $table->foreign('konsultasi_id')->references('konsultasi_id')->on('konsultasis')->onDelete('cascade');

            $table->unsignedBigInteger('dokter_id');
            $table->foreign('dokter_id')->references('dokter_id')->on('dokters')->onDelete('cascade');
            
            // Asumsi: Kasir menggunakan tabel 'kasirs' dan kolom PK 'kasir_id'
            $table->unsignedBigInteger('kasir_id');
            $table->foreign('kasir_id')->references('kasir_id')->on('kasirs')->onDelete('cascade');

            // --- Kolom Data Medis ---
            $table->string('diagnosis');
            $table->text('tindakan');
            $table->decimal('biaya_total', 10, 2); // Contoh: 10 digit total, 2 di belakang koma

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
        Schema::dropIfExists('rekam_medis');
    }
};