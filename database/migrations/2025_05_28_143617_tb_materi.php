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
        Schema::create('tb_materi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('level_id')->constrained('tb_level')->onDelete('cascade');
            $table->string('judul_materi');
            $table->string('file_pdf');
            $table->integer('urutan'); // sub-materi 1 s/d 4
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_materi');
    }
};
