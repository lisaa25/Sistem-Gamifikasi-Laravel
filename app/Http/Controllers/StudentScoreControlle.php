<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ModelUser; // Model untuk user/siswa
use App\Models\HasilKuis; // Model untuk hasil kuis
use App\Models\Materi;    // Model untuk materi
use App\Models\Kuis;      // Model untuk kuis (ditambahkan)
use Illuminate\Support\Facades\DB; // Untuk query database yang lebih kompleks

class StudentScoreControlle extends Controller
{
    /**
     * Tampilkan daftar nilai siswa dengan filter.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $query = ModelUser::query();
        $selectedKelas = $request->query('kelas');

        // Filter berdasarkan kelas jika ada yang dipilih
        if ($selectedKelas && $selectedKelas !== 'all') {
            $query->where('kelas', $selectedKelas);
        }

        // Ambil semua siswa yang relevan
        // Gunakan paginate() jika jumlah siswa bisa sangat banyak
        $users = $query->get();

        $studentScores = [];

        foreach ($users as $user) {
            // Dapatkan nilai kuis tertinggi untuk setiap materi yang dikerjakan user
            // Ini akan mengelompokkan hasil kuis berdasarkan materi_id dan mengambil nilai_kuis tertinggi
            $highestScoresPerMateri = HasilKuis::where('user_id', $user->id)
                ->select('materi_id', DB::raw('MAX(nilai_kuis) as max_nilai_kuis'))
                ->groupBy('materi_id')
                ->get();

            $totalNilaiKuis = 0;
            $jumlahMateriDikerjakan = $highestScoresPerMateri->count();

            foreach ($highestScoresPerMateri as $score) {
                $totalNilaiKuis += $score->max_nilai_kuis;
            }

            // Hitung rata-rata nilai kuis
            $rataRataNilai = $jumlahMateriDikerjakan > 0 ? round($totalNilaiKuis / $jumlahMateriDikerjakan, 2) : 0;

            // Tentukan kategori nilai (A, B, C, atau -)
            $kategoriNilai = '-'; // Default ke '-' jika belum mengerjakan apapun
            if ($jumlahMateriDikerjakan > 0) { // Hanya tentukan kategori jika ada materi yang dikerjakan
                if ($rataRataNilai >= 90) {
                    $kategoriNilai = 'A';
                } elseif ($rataRataNilai >= 75) {
                    $kategoriNilai = 'B';
                } else { // Nilai di bawah 75 (tapi sudah mengerjakan)
                    $kategoriNilai = 'C';
                }
            }

            $studentScores[] = [
                'id' => $user->id,
                'nama' => $user->nama,
                'kelas' => $user->kelas,
                'total_koin' => $user->total_koin ?? 0, // Ambil total_koin dari model user
                'rata_rata_nilai_kuis' => $rataRataNilai,
                'kategori_nilai' => $kategoriNilai,
            ];
        }

        // Daftar kelas yang tersedia untuk filter
        $kelasList = ['8A', '8B', '8C', '8D']; // Sesuaikan jika ada kelas lain

        return view('admin.nilaiSiswa', compact('studentScores', 'selectedKelas', 'kelasList'));
    }

    /**
     * Tampilkan rincian nilai kuis untuk siswa tertentu.
     *
     * @param  \App\Models\ModelUser  $user (menggunakan Route Model Binding)
     * @return \Illuminate\View\View
     */
    public function showDetails(ModelUser $user)
    {
        // Ambil semua hasil kuis untuk user ini, eager load materi dan kuis
        $quizResults = HasilKuis::where('user_id', $user->id)
            ->with(['materi.level', 'kuis']) // Load materi (dan levelnya) serta kuis
            ->orderBy('created_at', 'desc') // Urutkan berdasarkan waktu pengerjaan terbaru
            ->get();

        // Anda bisa menambahkan logika untuk mengelompokkan atau menghitung rata-rata di sini
        // jika diperlukan untuk tampilan detail.
        // Untuk saat ini, kita akan menampilkan semua hasil yang relevan.

        return view('admin.detailNilai', compact('user', 'quizResults'));
    }
}
