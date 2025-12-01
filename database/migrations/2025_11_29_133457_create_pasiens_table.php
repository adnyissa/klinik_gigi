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
        Schema::create('pasiens', function (Blueprint $table) {
    $table->id('pasien_id');
    $table->string('nama');
    $table->string('nik')->unique();
    $table->string('nomor_telepon')->nullable();
    $table->date('tanggal_lahir');
    $table->enum('jenis_kelamin', ['Laki-laki', 'Perempuan']);
    $table->text('alamat')->nullable(); // sekarang alamat di bawah
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
        Schema::dropIfExists('pasiens');
    }
};