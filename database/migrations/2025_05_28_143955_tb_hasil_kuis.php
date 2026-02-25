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
        Schema::create('tb_hasil_kuis', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // Mengarah ke tb_user
            $table->unsignedBigInteger('materi_id');
            $table->unsignedBigInteger('kuis_id')->nullable();  // Mengarah ke tb_kuis
            $table->integer('skor_benar')->default(0);
            $table->integer('skor_salah')->default(0);
            $table->integer('nilai_kuis')->default(0);
            $table->integer('koin_didapatkan')->default(0);
            $table->timestamp('waktu_mengerjakan')->nullable();
            $table->timestamps();

            // Perbaikan foreign key
            $table->foreign('user_id')->references('id')->on('tb_user')->onDelete('cascade');
            $table->foreign('kuis_id')->references('id')->on('tb_kuis')->onDelete('set null');
            $table->foreign('materi_id')->references('id')->on('tb_materi')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_hasil_kuis');
    }
};
