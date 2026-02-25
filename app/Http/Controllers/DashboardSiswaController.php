<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ModelUser; // <<<=== PASTIKAN INI ModelUser ===>>>
use App\Models\HasilKuis;
use App\Models\Materi;

class DashboardSiswaController extends Controller
{
    public function index()
    {
        $user = Auth::user(); // Ini akan mengembalikan instance ModelUser yang sudah diautentikasi

        // <<<=== UBAH BARIS INI ===>>>
        // Ambil total koin dari kolom `total_koin` di tabel tb_user
        $totalKoin = $user->total_koin; // Langsung akses properti total_koin dari objek ModelUser

        // Ambil 10 aktivitas terbaru untuk tampilan awal dashboard
        $aktivitasTerbaru = HasilKuis::where('user_id', $user->id)
            ->with('materi')
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        // Cek apakah ada lebih dari 10 aktivitas
        $hasMoreActivities = HasilKuis::where('user_id', $user->id)->count() > 10;

        return view('kerangka.dashboardsiswa', compact('user', 'totalKoin', 'aktivitasTerbaru', 'hasMoreActivities'));
    }

    // --- Method loadMoreActivities tidak perlu diubah karena tidak menangani total koin global ---
    public function loadMoreActivities(Request $request)
    {
        $user = Auth::user();
        $offset = $request->input('offset', 10);
        $limit = $request->input('limit', 10);

        $moreActivities = HasilKuis::where('user_id', $user->id)
            ->with('materi')
            ->orderBy('created_at', 'desc')
            ->skip($offset)
            ->take($limit)
            ->get();

        $totalActivitiesCount = HasilKuis::where('user_id', $user->id)->count();
        $hasMore = ($offset + $limit) < $totalActivitiesCount;

        return response()->json([
            'activities' => $moreActivities->map(function ($activity) {
                $imagePath = 'img/materi/urutan' . $activity->materi->urutan . '.png';
                if (!file_exists(public_path($imagePath))) {
                    $imagePath = 'img/materi/default.png';
                }
                return [
                    'id' => $activity->id,
                    'materi_id' => $activity->materi->id,
                    'judul_materi' => $activity->materi->judul_materi,
                    'nilai_kuis' => $activity->nilai_kuis,
                    'created_at_formatted' => $activity->created_at->format('d M Y H:i'),
                    'image_url' => asset($imagePath),
                    'koin_didapatkan' => $activity->koin_didapatkan,
                ];
            }),
            'has_more' => $hasMore,
        ]);
    }
}
