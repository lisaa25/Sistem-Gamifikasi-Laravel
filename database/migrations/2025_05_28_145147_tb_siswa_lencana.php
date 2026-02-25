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
        Schema::create('tb_siswa_lencana', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siswa_id')->constrained('tb_user')->onDelete('cascade');
            $table->foreignId('lencana_id')->constrained('tb_lencana')->onDelete('cascade');
            $table->date('tanggal_dicapai');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_siswa_lencana');
    }
};
