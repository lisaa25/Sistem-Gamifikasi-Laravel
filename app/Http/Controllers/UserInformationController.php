<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ModelUser;
use App\Models\Lencana; // Pastikan ini diimpor
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash; // Penting: Tambahkan ini untuk hashing password
use Illuminate\Validation\ValidationException; // Penting: Tambahkan ini untuk custom validation errors


class UserInformationController extends Controller
{
    public function show()
    {
        // Pastikan mengambil user sebagai instance dari ModelUser
        //$user = ModelUser::find(Auth::id());
        $user = ModelUser::with('level')->find(Auth::id());

        if (!$user) {
            return redirect('/login')->with('error', 'Anda harus login untuk melihat profil.');
        }

        // --- LOGIKA PENGAMBILAN LENCANA BARU DIMULAI DI SINI ---
        $userBadges = $user->lencana()->get(); // Mengambil lencana yang dimiliki user

        // Ambil semua lencana yang tersedia (dari tb_lencana)
        $allAvailableBadges = Lencana::all();

        // Untuk menampilkan lencana yang belum didapat sebagai 'terkunci'
        $userBadgeIds = $userBadges->pluck('id')->toArray();

        $badgesDisplayData = $allAvailableBadges->map(function ($badge) use ($userBadgeIds, $userBadges) {
            $isUnlocked = in_array($badge->id, $userBadgeIds);
            $awardedDate = null;
            if ($isUnlocked) {
                $awardedBadge = $userBadges->firstWhere('id', $badge->id);
                // Pastikan pivot ada dan tanggal_dicapai ada sebelum diakses
                $awardedDate = $awardedBadge && $awardedBadge->pivot && $awardedBadge->pivot->tanggal_dicapai
                    ? \Carbon\Carbon::parse($awardedBadge->pivot->tanggal_dicapai)->format('d M Y')
                    : 'N/A'; // Jika tidak ada tanggal, tampilkan 'N/A'
            }

            return [
                'id' => $badge->id,
                'nama_lencana' => $badge->nama_lencana,
                'deskripsi' => $badge->deskripsi,
                'gambar' => asset('img/badges/' . $badge->gambar), // Ini akan menghasilkan http://127.0.0.1:8000/img/badges/si_paling_pintar.png
                'is_unlocked' => $isUnlocked,
                'tanggal_dicapai' => $awardedDate,
                'locked_image' => asset('img/locked_badge.png'), // Pastikan gambar ini ada di public/img/locked_badge.png
            ];
        });
        // --- LOGIKA PENGAMBILAN LENCANA BARU BERAKHIR DI SINI ---

        // Kirim $user dan $badgesDisplayData ke view
        return view('kerangka.profil', compact('user', 'badgesDisplayData'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:tb_user,email,' . $user->id . ',id',
            'kelas' => 'nullable|string|max:255',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($user instanceof \App\Models\ModelUser) {
            $user->nama = $request->nama;
            $user->email = $request->email;
            $user->kelas = $request->kelas;

            if ($request->hasFile('foto')) {
                // Path untuk menghapus foto lama: 'img/profil/' adalah subfolder dari storage/app/public/
                if ($user->foto && $user->foto !== 'default_profile.png' && Storage::disk('public')->exists('img/profil/' . $user->foto)) {
                    Storage::disk('public')->delete('img/profil/' . $user->foto);
                }

                $file = $request->file('foto');
                // Gunakan hashName() untuk nama file yang unik dan aman dari karakter spesial
                $filename = $file->hashName();

                // Simpan di storage/app/public/img/profil
                $file->storeAs('img/profil', $filename, 'public'); // 'img/profil' adalah subfolder dari disk 'public'
                $user->foto = $filename; // Simpan hanya nama file di database
            }

            $user->save();

            return redirect()->route('user.show')->with('success', 'Profil berhasil diperbarui!');
        } else {
            return redirect()->back()->withErrors(['message' => 'Data pengguna tidak valid.']);
        }
    }

    // --- Metode Baru untuk Update Password ---
    public function updatePassword(Request $request)
    {
        $user = Auth::user();

        // Validasi input
        $request->validate([
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:8|confirmed', // 'confirmed' akan mencari field new_password_confirmation
        ], [
            'new_password.min' => 'Password baru harus minimal 8 karakter.',
            'new_password.confirmed' => 'Konfirmasi password baru tidak cocok.',
        ]);

        // Cek apakah password lama cocok
        if (!Hash::check($request->current_password, $user->password)) {
            throw ValidationException::withMessages([
                'current_password' => ['Password lama tidak cocok.'],
            ]);
        }

        // Pastikan $user adalah instance dari ModelUser sebelum menyimpan
        if ($user instanceof \App\Models\ModelUser) {
            // Update password baru
            $user->password = Hash::make($request->new_password);
            $user->save();

            return redirect()->route('user.show')->with('success', 'Password berhasil diubah!');
        } else {
            return redirect()->back()->withErrors(['message' => 'Data pengguna tidak valid.']);
        }
    }

    public function showAllBadges()
    {
        // Ambil semua lencana yang tersedia dari database
        $allBadges = Lencana::all();

        // Siapkan data untuk ditampilkan di view, termasuk path gambar yang benar
        $badgesDisplayData = $allBadges->map(function ($badge) {
            // Gunakan pendekatan yang sudah Anda berhasil terapkan (Pendekatan B)
            // di mana URL lengkap dibuat di controller untuk gambar lencana yang didapat
            $gambarUrl = asset('img/badges/' . $badge->gambar);

            // Untuk gambar terkunci, gunakan path yang sudah Anda set di controller sebelumnya
            $lockedImageUrl = asset('img/locked_badge.png'); // Atau asset('img/badges/locked_badge.png') jika Anda menempatkannya di sana

            return [
                'nama_lencana' => $badge->nama_lencana,
                'deskripsi' => $badge->deskripsi,
                'gambar' => $gambarUrl,
                'locked_image' => $lockedImageUrl,
                // Anda bisa tambahkan field lain jika diperlukan, misal "cara_mendapatkan"
                // 'cara_mendapatkan' => 'Selesaikan ' . $badge->nama_lencana . ' ini.' // Contoh, jika tidak ada di DB
            ];
        });

        // Kirim data lencana ke view baru
        return view('kerangka.daftar_lencana', compact('badgesDisplayData'));
    }
}
