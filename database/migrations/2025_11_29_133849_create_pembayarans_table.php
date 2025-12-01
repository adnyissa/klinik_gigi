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
        Schema::create('pembayarans', function (Blueprint $table) {
            $table->id('pembayaran_id');

            // --- Relasi Foreign Key ---
            
            // Merujuk ke Rekam Medis (PERBAIKAN: Menggunakan rekam_medis_id)
            $table->unsignedBigInteger('rekam_medis_id');
            $table->foreign('rekam_medis_id')->references('rekam_medis_id')->on('rekam_medis')->onDelete('cascade');

            // Merujuk ke Kasir yang memproses pembayaran (Pastikan kasir_id juga benar)
            $table->unsignedBigInteger('kasir_id');
            $table->foreign('kasir_id')->references('kasir_id')->on('kasirs')->onDelete('cascade');

            // --- Kolom Data Pembayaran ---
            $table->date('tgl_pembayaran');
            $table->decimal('jumlah_dibayar', 10, 2);
            $table->enum('metode_pembayaran', ['Cash', 'Transfer', 'Debit']);
            $table->string('bukti_pembayaran')->nullable();
            
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
        Schema::dropIfExists('pembayarans');
    }
};