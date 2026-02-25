<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tb_user', function (Blueprint $table) {
            $table->id('id');
            $table->string('nama');
            $table->string('kelas')->nullable();
            $table->string('no_telepon')->default('');
            $table->text('alamat')->nullable();
            $table->string('email')->unique();
            $table->string('password');
            $table->string('foto')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tb_user');
    }
};
