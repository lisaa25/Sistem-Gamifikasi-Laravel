<?php

namespace App\Services;

use App\Models\ModelUser;
use App\Models\Lencana;
use App\Models\SiswaLencana;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class BadgeService
{
    /**
     * Memberikan lencana kepada pengguna jika kriteria terpenuhi.
     * Menggunakan firstOrCreate untuk menghindari duplikasi lencana.
     *
     * @param ModelUser $user
     * @param string $lencanaNama
     * @return array|null Mengembalikan array data lencana jika baru diberikan, null jika sudah ada/tidak ditemukan.
     */
    public function awardBadge(ModelUser $user, string $lencanaNama)
    {
        $lencana = Lencana::where('nama_lencana', $lencanaNama)->first();

        if (!$lencana) {
            Log::warning("Lencana dengan nama '{$lencanaNama}' tidak ditemukan di database.");
            return null;
        }

        $siswaLencana = SiswaLencana::firstOrCreate(
            [
                'siswa_id' => $user->id,
                'lencana_id' => $lencana->id,
            ],
            [
                'tanggal_dicapai' => Carbon::now()->toDateString(),
            ]
        );

        if ($siswaLencana->wasRecentlyCreated) {
            Log::info("Lencana '{$lencanaNama}' berhasil diberikan kepada user ID: {$user->id}.");
            return [
                'id' => $lencana->id,
                'nama' => $lencana->nama_lencana,
                'deskripsi' => $lencana->deskripsi,
                // PERBAIKAN DI SINI: Tambahkan 'img/badges/' di depan $lencana->gambar
                'gambar' => asset('img/badges/' . $lencana->gambar),
                'tanggal_dicapai' => $siswaLencana->tanggal_dicapai->format('d M Y'),
            ];
        }

        Log::info("User ID: {$user->id} sudah memiliki lencana '{$lencanaNama}'.");
        return null;
    }

    /**
     * Cek dan berikan lencana "Si Paling Pintar" (nilai kuis 100).
     *
     * @param ModelUser $user
     * @param float $nilaiKuis
     * @return array|null Mengembalikan array data lencana jika baru diberikan, null jika tidak.
     */
    public function checkSiPalingPintarBadge(ModelUser $user, float $nilaiKuis)
    {
        if ($nilaiKuis == 100) {
            return $this->awardBadge($user, 'Si Paling Pintar');
        }
        return null;
    }

    // Metode untuk lencana lain akan ditambahkan di sini nanti
}
