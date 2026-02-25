<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tb_lencana', function (Blueprint $table) {
            $table->id();
            $table->string('nama_lencana');
            $table->string('deskripsi');
            $table->string('gambar'); // kolom untuk menyimpan nama file/icon lencana
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_lencana');
    }
};
