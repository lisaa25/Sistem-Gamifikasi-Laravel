<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Materi;
use App\Models\Level;
use App\Models\UserMateriProgress; // <<< IMPORT MODEL BARU
use App\Models\HasilKuis;

class PembelajaranController extends Controller
{
    public function index()
    {
        $userId = Auth::id(); // Dapatkan ID user yang sedang login

        // Dapatkan semua materi dan level yang diurutkan
        $materis = Materi::with('level')->orderBy('level_id')->orderBy('urutan')->get();
        $levels = Level::orderBy('id')->get();

        // Dapatkan progres materi untuk user yang sedang login
        // Ini akan mengambil array [materi_id => status, ...]
        $userProgress = UserMateriProgress::where('user_id', $userId)
            ->pluck('status', 'materi_id')
            ->toArray();

        $materisByLevel = collect([]);

        foreach ($levels as $level) {
            $levelMateris = $materis->where('level_id', $level->id);

            $processedMateris = collect([]);
            foreach ($levelMateris as $materi) {
                // Tentukan status materi untuk user ini
                $status = $userProgress[$materi->id] ?? 'locked'; // Default ke 'locked' jika tidak ada entri

                // Logika inisial: Materi pertama di Level 1 harus selalu unlocked jika belum ada progress
                if ($materi->level_id === 1 && $materi->urutan === 1) {
                    // Jika materi pertama Level 1 dan belum ada status (belum pernah dibuka/diselesaikan),
                    // atau statusnya masih 'locked' (kemungkinan dari default saat inisiasi),
                    // pastikan itu 'unlocked'.
                    if (!isset($userProgress[$materi->id]) || $userProgress[$materi->id] === 'locked') {
                        UserMateriProgress::updateOrCreate(
                            [
                                'user_id' => $userId,
                                'materi_id' => $materi->id
                            ],
                            [
                                'status' => 'unlocked'
                            ]
                        );
                        $status = 'unlocked'; // Set status di variabel lokal juga
                    }
                }

                // Tambahkan properti is_locked ke objek materi
                $materi->is_locked = ($status === 'locked');
                $materi->user_status = $status; // Untuk referensi lebih lanjut di Blade jika perlu

                $processedMateris->push($materi);
            }

            if ($processedMateris->isNotEmpty()) {
                $materisByLevel->put($level->nama_level, $processedMateris);
                $materisByLevel[$level->nama_level]->level_description = $level->deskripsi;
            }
        }

        return view('kerangka.pembelajaran', compact('materisByLevel'));
    }

    // ... (fungsi showMateri akan kita modifikasi di langkah berikutnya)
    public function showMateri(Materi $materi)
    {
        $userId = Auth::id();
        $batasKelulusan = 70; // Sesuaikan dengan batas kelulusan kuis Anda

        // 1. Periksa apakah materi ini terkunci untuk user
        $materiProgress = UserMateriProgress::where('user_id', $userId)
            ->where('materi_id', $materi->id)
            ->first();

        if (!$materiProgress || $materiProgress->status === 'locked') {
            return redirect()->route('pembelajaran.index')->with('error', 'Materi ini terkunci. Selesaikan materi sebelumnya.');
        }

        // 2. Dapatkan hasil kuis tertinggi user untuk materi ini
        $highestScore = HasilKuis::where('user_id', $userId)
            ->where('materi_id', $materi->id)
            ->max('nilai_kuis'); // Mengambil nilai kuis tertinggi

        // 3. Tentukan apakah user sudah lulus kuis materi ini
        $hasPassedQuiz = ($highestScore !== null && $highestScore >= $batasKelulusan);

        // 4. Dapatkan semua hasil kuis user untuk materi ini (untuk tampilan riwayat)
        $userHasilKuis = HasilKuis::where('user_id', $userId)
            ->where('materi_id', $materi->id)
            ->orderBy('created_at', 'desc') // Tampilkan yang terbaru duluan
            ->get();


        // Kirim data ke view
        return view('kerangka.berhasil', compact('materi', 'hasPassedQuiz', 'userHasilKuis'));
    }
}
