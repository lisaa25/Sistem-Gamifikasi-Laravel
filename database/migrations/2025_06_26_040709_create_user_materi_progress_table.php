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
        Schema::create('user_materi_progress', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('tb_user')->onDelete('cascade'); // Mengacu ke tabel user Anda
            $table->foreignId('materi_id')->constrained('tb_materi')->onDelete('cascade'); // Mengacu ke tabel materi Anda
            $table->string('status')->default('locked');
            $table->timestamps();

            $table->unique(['user_id', 'materi_id']); // Unik untuk kombinasi user dan materi
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_materi_progress');
    }
};
