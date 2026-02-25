<?php

namespace App\Http\Controllers;

use App\Models\Materi;
use App\Models\Level;
use App\Models\Lencana;
use App\Models\ModelUser;

use Illuminate\Support\Facades\Auth;


use Illuminate\Http\Request;

class UserController extends Controller
{
    public function pembelajaran()
    {
        // Ambil semua level dan materi yang terkait, urutkan berdasarkan level dan urutan materi
        $levels = Level::with(['materi' => function ($query) {
            $query->orderBy('urutan');
        }])->orderBy('id')->get(); // Urutkan level juga jika ada kolom urutan level

        return view('kerangka.pembelajaran', compact('levels'));
    }

    public function showMateri($id)
    {
        // Ambil materi berdasarkan ID
        $materi = Materi::findOrFail($id); // Jika tidak ditemukan, akan mengembalikan 404

        // Pastikan materi ini milik level yang sesuai
        if ($materi->level_id !== Auth::user()->level_id) {
            abort(403, 'Unauthorized action.'); // Atau redirect dengan pesan error
        }

        // Kirim objek materi ke view
        return view('kerangka.materi', compact('materi')); // Menggunakan view materi.blade.php Anda
    }
}
