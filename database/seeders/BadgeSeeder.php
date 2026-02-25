<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Lencana;

class BadgeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Lencana "Si Paling Pintar"
        // Pastikan 'nama_lencana' dan 'gambar' sesuai dengan kolom di tb_lencana
        Lencana::firstOrCreate(
            ['nama_lencana' => 'Si Paling Pintar'], // Kriteria untuk menemukan lencana (nama unik)
            [
                'deskripsi' => 'Mendapatkan skor 100 sempurna pada kuis.',
                'gambar' => '/img/badges/si_paling_pintar.png', // <<< GANTI DENGAN PATH GAMBAR ASLI ANDA
            ]
        );
    }
}
