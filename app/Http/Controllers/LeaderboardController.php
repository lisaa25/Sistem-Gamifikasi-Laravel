<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ModelUser; // <<< PENTING: Pastikan ini ModelUser Anda

class LeaderboardController extends Controller
{
    public function showLeaderboard()
    {
        // Pastikan pengguna sudah login sebelum mengakses leaderboard
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Anda harus login untuk melihat leaderboard.');
        }

        // Ambil objek pengguna yang sedang login
        $loggedInUser = auth()->user();

        // Ambil kelas siswa yang sedang login dari objek pengguna
        $kelasSiswa = $loggedInUser->kelas;

        // Jika kelas siswa tidak terdefinisi (misal: null), berikan pesan error atau default
        if (empty($kelasSiswa)) {
            // Anda bisa mengarahkan kembali atau menampilkan pesan di halaman leaderboard
            return redirect()->back()->with('error', 'Kelas Anda belum terdaftar. Silakan hubungi admin.');
            // Atau jika ingin tetap menampilkan halaman, kirim data kosong atau pesan khusus
            // return view('leaderboard.siswa', [
            //     'topThree' => collect(),
            //     'others' => collect(),
            //     'kelasSiswa' => 'Tidak Ditemukan',
            //     'loggedInUserRank' => null,
            //     'loggedInUserScore' => 0
            // ]);
        }


        // Ambil semua pengguna di kelas yang sama, urutkan berdasarkan total_koin tertinggi
        // Perhatikan bahwa kita mengurutkan berdasarkan kolom fisik 'total_koin' di tabel 'tb_user'
        $leaderboardData = ModelUser::where('kelas', $kelasSiswa)
            ->orderBy('total_koin', 'desc') // Menggunakan kolom 'total_koin'
            ->limit(10) // Ambil 10 teratas
            ->get();

        // Pisahkan 3 teratas dan sisanya
        $topThree = $leaderboardData->take(3);
        $others = $leaderboardData->skip(3);

        // Cari tahu peringkat siswa yang sedang login (jika ada di dalam 10 teratas yang diambil)
        $loggedInUserRank = null;
        foreach ($leaderboardData as $index => $player) {
            if ($player->id === $loggedInUser->id) {
                $loggedInUserRank = $index + 1; // Rank adalah indeks + 1
                break;
            }
        }

        // Jika siswa yang login tidak ada di 10 teratas, kita tetap bisa menampilkan skornya
        $loggedInUserScore = $loggedInUser->total_koin; // Mengambil total_koin dari user yang login

        return view('kerangka.leaderboard', compact('topThree', 'others', 'kelasSiswa', 'loggedInUserRank', 'loggedInUserScore'));
    }
}
