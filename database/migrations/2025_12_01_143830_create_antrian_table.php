<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('antrian', function (Blueprint $table) {
            $table->id('antrian_id');

            // Relasi ke pasien
            $table->unsignedBigInteger('pasien_id');

            // Nomor antrian per hari (A001, A002)
            $table->string('nomor_antrian')->nullable();

            // Tanggal + jam kunjungan
            $table->date('tgl_kunjungan');
            $table->time('jam_kunjungan')->nullable();

            // Keluhan awal
            $table->string('keluhan_awal')->nullable();

            // Status antrian
            $table->enum('status', ['menunggu', 'diperiksa', 'selesai', 'batal'])
                  ->default('menunggu');

            $table->timestamps();

            // foreign key
            $table->foreign('pasien_id')
                  ->references('pasien_id')
                  ->on('pasiens')
                  ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('antrian');
    }
};
