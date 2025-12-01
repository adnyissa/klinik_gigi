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
    Schema::create('kasirs', function (Blueprint $table) {
        $table->id('kasir_id');
        $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
        
        $table->string('no_hp', 15);
        $table->text('alamat');
        $table->enum('shift_kerja', ['Pagi', 'Siang', 'Malam']);
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
        Schema::dropIfExists('kasirs');
    }
};
