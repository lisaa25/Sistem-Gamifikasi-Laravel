<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tb_jawaban_siswa', function (Blueprint $table) {
            $table->id();

            $table->foreignId('hasil_kuis_id')
                ->constrained('tb_hasil_kuis')
                ->onDelete('cascade');

            // --- PERBAIKAN DI SINI ---
            $table->foreignId('soal_id')
                ->constrained('tb_kuis') // <<< UBAH DARI 'tb_soal_kuis' MENJADI 'tb_kuis'
                ->onDelete('cascade');

            // Hapus baris ini jika Anda tidak memiliki tabel 'tb_pilihan_jawaban'
            // karena pilihan A,B,C,D ada di tb_kuis
            // $table->foreignId('pilihan_terpilih_id')
            //       ->nullable()
            //       ->constrained('tb_pilihan_jawaban')
            //       ->onDelete('cascade');

            // Kolom untuk menyimpan pilihan yang dipilih siswa (A, B, C, D)
            // Ini akan menggantikan foreign key ke pilihan_jawaban
            $table->string('pilihan_terpilih')->nullable(); // Menampung 'A', 'B', 'C', 'D'

            $table->boolean('is_correct');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tb_jawaban_siswa');
    }
};
