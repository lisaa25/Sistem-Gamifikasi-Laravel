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
        Schema::table('tb_user', function (Blueprint $table) {
            // Menambahkan kolom level_id sebagai foreign key
            // Default ke level 1 jika level_id tidak ada, atau buat nullable jika level tidak wajib
            $table->foreignId('level_id')->nullable()->constrained('tb_level')->onDelete('set null')->after('password');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tb_user', function (Blueprint $table) {
            // Menghapus foreign key dan kolom level_id saat rollback
            $table->dropForeign(['level_id']);
            $table->dropColumn('level_id');
        });
    }
};
