<?php

namespace App\Http\Controllers;

use App\Models\ModelAdmin;
use App\Models\ModelProduk;
use App\Models\ModelUser;
use App\Models\Level;
use App\Models\Materi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use App\Models\HasilKuis;
use Illuminate\Support\Facades\DB;


class AdminController extends Controller
{
    // Login admin
    public function loginAdmin()
    {
        return view('admin.loginAdmin');
    }

    // Login admin post
    public function adminPost(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        $admin = ModelAdmin::where('email', $request->email)->first();

        if ($admin && Hash::check($request->password, $admin->password)) {
            Auth::guard('admin')->login($admin);
            return redirect()->route('statistik')->with('success', 'Admin logged in successfully');
        } else {
            return redirect()
                ->route('loginAdmin')
                ->withErrors(['email' => 'Email or password is incorrect']);
        }
    }

    // Dashboard admin
    public function dashboard()
    {
        $kelasList = ['8A', '8B', '8C', '8D'];
        $jumlahSiswaPerKelas = [];
        $totalSiswa = 0;

        foreach ($kelasList as $kelas) {
            $count = ModelUser::where('kelas', $kelas)->count();
            $jumlahSiswaPerKelas[$kelas] = $count;
            $totalSiswa += $count;
        }

        // --- Logika untuk Rata-rata Nilai Kelas ---
        $rataRataNilaiPerKelas = [];
        foreach ($kelasList as $kelas) {
            // Ambil semua siswa di kelas ini
            $usersInClass = ModelUser::where('kelas', $kelas)->get();

            $totalRataRataSiswa = 0;
            $jumlahSiswaDenganNilai = 0;

            foreach ($usersInClass as $user) {
                // Dapatkan nilai kuis tertinggi untuk setiap materi yang dikerjakan user
                $highestScoresPerMateri = HasilKuis::where('user_id', $user->id)
                    ->select(DB::raw('MAX(nilai_kuis) as max_nilai_kuis'))
                    ->groupBy('materi_id')
                    ->get();

                $totalNilaiKuisSiswa = 0;
                $jumlahMateriDikerjakanSiswa = $highestScoresPerMateri->count();

                if ($jumlahMateriDikerjakanSiswa > 0) {
                    foreach ($highestScoresPerMateri as $score) {
                        $totalNilaiKuisSiswa += $score->max_nilai_kuis;
                    }
                    $rataRataSiswa = round($totalNilaiKuisSiswa / $jumlahMateriDikerjakanSiswa, 2);
                    $totalRataRataSiswa += $rataRataSiswa;
                    $jumlahSiswaDenganNilai++;
                }
            }

            // Hitung rata-rata kelas dari rata-rata nilai siswa
            $rataRataKelas = $jumlahSiswaDenganNilai > 0 ? round($totalRataRataSiswa / $jumlahSiswaDenganNilai, 2) : 0;
            $rataRataNilaiPerKelas[$kelas] = $rataRataKelas;
        }


        // --- Logika untuk Top 5 Siswa Berdasarkan Poin (Leaderboard) ---
        // Eager load hasil kuis untuk efisiensi
        $topStudents = ModelUser::orderByDesc('total_koin')
            ->limit(5)
            ->with(['hasilKuis' => function ($query) {
                // Ambil nilai kuis tertinggi per materi untuk setiap hasil kuis
                $query->select('user_id', 'materi_id', DB::raw('MAX(nilai_kuis) as max_nilai_kuis'))
                    ->groupBy('user_id', 'materi_id');
            }])
            ->get();

        $leaderboardArray = []; // Menggunakan nama sementara untuk array
        foreach ($topStudents as $student) {
            $totalNilaiKuis = 0;
            $jumlahMateriDikerjakan = $student->hasilKuis->count();

            foreach ($student->hasilKuis as $hasil) {
                $totalNilaiKuis += $hasil->max_nilai_kuis;
            }

            $rataRataNilai = $jumlahMateriDikerjakan > 0 ? round($totalNilaiKuis / $jumlahMateriDikerjakan, 2) : 0;

            $leaderboardArray[] = [
                'nama' => $student->nama,
                'kelas' => $student->kelas,
                'total_koin' => $student->total_koin ?? 0,
                'rata_rata_nilai_kuis' => $rataRataNilai,
            ];
        }

        // Konversi array menjadi Laravel Collection
        $leaderboard = collect($leaderboardArray);


        return view('admin.statistik', compact(
            'jumlahSiswaPerKelas',
            'totalSiswa',
            'rataRataNilaiPerKelas',
            'leaderboard' // Sekarang ini adalah Collection
        ));
    }

    // Logout admin
    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return view('intro.intro')->with('success', 'Admin logged out successfully');
    }

    // Menampilkan data pengguna dengan filter kelas
    public function showUsers(Request $request)
    {
        $query = ModelUser::query();
        $selectedKelas = $request->query('kelas');

        if ($selectedKelas && $selectedKelas !== 'all') {
            $query->where('kelas', $selectedKelas);
        }

        $users = $query->get();

        return view('admin.dataUser', compact('users', 'selectedKelas'));
    }

    // Menampilkan form tambah pengguna
    public function createUser()
    {
        return view('admin.createUser');
    }

    // Menyimpan pengguna baru
    public function storeUser(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'kelas' => 'required|string|max:255',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                // Pastikan Rule::unique digunakan untuk validasi email unik
                Rule::unique('tb_user', 'email'),
            ],
            'password' => 'required|string|min:8',
            // Tambahkan validasi untuk foto jika Anda ingin admin bisa mengupload foto awal
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Buat instance ModelUser baru
        $user = new ModelUser([
            'nama' => $request->nama,
            'kelas' => $request->kelas,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            // Kolom lain jika ada yang perlu diinisialisasi saat pembuatan user
            'no_telepon' => $request->no_telepon ?? '', // Contoh inisialisasi default
            'alamat' => $request->alamat ?? null,
            'total_koin' => 0, // Inisialisasi koin awal
            'quiz_win_streak' => 0, // Inisialisasi streak awal
        ]);

        // --- BAGIAN PENTING YANG PERLU DITAMBAHKAN ---
        // Menetapkan level_id secara default ke 1
        // Pastikan Anda memiliki entri Level 1 di tabel `tb_level` dengan `id = 1`.
        $user->level_id = 1;

        // Handle upload foto profil jika admin mengunggahnya saat membuat user
        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $filename = $file->hashName(); // Buat nama file unik
            $file->storeAs('img/profil', $filename, 'public'); // Simpan di storage/app/public/img/profil
            $user->foto = $filename;
        } else {
            // Jika tidak ada foto diunggah, gunakan foto default
            // Pastikan 'default_profile.png' ada di public/img/profil/
            $user->foto = 'default_profile.png';
        }
        // --- AKHIR BAGIAN PENTING YANG PERLU DITAMBAHKAN ---

        $user->save();
        return redirect()->route('admin.users')->with('success', 'User created successfully');
    }

    // Menghapus pengguna
    public function deleteUser($id)
    {
        $user = ModelUser::find($id);
        if ($user) {
            $user->delete();
            return redirect()->route('admin.users')->with('success', 'User deleted successfully');
        }
        return redirect()->route('admin.users')->with('error', 'User not found');
    }

    // Menampilkan form edit pengguna
    public function editUser($id)
    {
        $user = ModelUser::find($id);
        if ($user) {
            return view('admin.editUser', compact('user'));
        }
        return redirect()->route('admin.users')->with('error', 'User not found');
    }

    // Update pengguna
    public function updateUser(Request $request, $id)
    {
        $user = ModelUser::find($id);

        if (!$user) {
            return redirect()->route('admin.users')->with('error', 'User not found');
        }

        // Validasi input
        $request->validate([
            'nama' => 'required|string|max:255',
            'kelas' => 'required|string|max:255', // Tambahkan validasi untuk kelas
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                // Pastikan email unik, kecuali untuk user yang sedang diedit
                Rule::unique('tb_user', 'email')->ignore($user->id),
            ],
            // Password bersifat opsional, hanya divalidasi jika diisi
            'password' => 'nullable|string|min:8|confirmed',
        ], [
            'email.unique' => 'Email ini sudah terdaftar untuk pengguna lain.',
            'password.min' => 'Password baru harus minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password baru tidak cocok.',
        ]);

        // Perbarui data user
        $user->nama = $request->nama;
        $user->kelas = $request->kelas; // Simpan nilai kelas
        $user->email = $request->email;

        // Perbarui password hanya jika diisi
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        // Jika Anda mengaktifkan kembali no_telepon dan alamat di Blade,
        // pastikan untuk menyimpannya di sini juga:
        // $user->no_telepon = $request->no_telepon;
        // $user->alamat = $request->alamat;

        $user->save(); // Simpan perubahan ke database

        return redirect()->route('admin.users')->with('success', 'User updated successfully');
    }

    // menampilkan form untuk mengunggah materi
    public function createMateri()
    {
        $levels = Level::all();
        return view('admin.materi.create', compact('levels'));
    }

    public function storeMateri(Request $request)
    {
        // 1. Validasi Input
        $request->validate([
            'level_id' => 'required|exists:tb_level,id',
            'judul_materi' => 'required|string|max:255',
            'urutan' => 'required|integer|min:1',
            'file_pdf' => 'required|mimes:pdf|max:10240', // Max 10MB
        ]);

        $storedPath = null;

        // 2. Upload File PDF
        if ($request->hasFile('file_pdf')) {
            $file = $request->file('file_pdf');
            $fileName = time() . '_' . $file->getClientOriginalName();

            try {
                $storedPath = Storage::disk('public')->putFileAs('materi_pdf', $file, $fileName);
            } catch (\Exception $e) {
                Log::error('Error uploading PDF file: ' . $e->getMessage());
                return back()->withErrors(['file_pdf' => 'Gagal mengunggah file PDF.'])->withInput();
            }
        } else {
            return back()->withErrors(['file_pdf' => 'File PDF tidak ditemukan.'])->withInput();
        }

        // 3. Simpan Data ke Database
        try {
            Materi::create([
                'level_id' => $request->level_id,
                'judul_materi' => $request->judul_materi,
                'file_pdf' => $storedPath,
                'urutan' => $request->urutan,
                'deskripsi_materi' => $request->deskripsi_materi,
            ]);
        } catch (\Exception $e) {
            // Jika ada error saat menyimpan ke DB, hapus file yang sudah terupload
            if ($storedPath) {
                Storage::disk('public')->delete($storedPath);
            }
            Log::error('Error saving materi to database: ' . $e->getMessage());
            return back()->withErrors(['database' => 'Gagal menyimpan materi ke database.'])->withInput();
        }

        return redirect()->route('admin.materi.index')->with('success', 'Materi berhasil diunggah!');
    }

    public function indexMateri()
    {
        // Ambil semua materi beserta nama levelnya untuk ditampilkan di tabel
        $materis = Materi::with('level')->orderBy('level_id')->orderBy('urutan')->get();
        return view('admin.materi.index', compact('materis'));
    }

    // Menghapus materi
    public function destroyMateri(Materi $materi) // Menggunakan Route Model Binding
    {
        try {
            // Hapus file PDF dari storage jika ada
            if ($materi->file_pdf) {
                // Konversi URL publik kembali ke path relatif di storage
                // Contoh: /storage/materi_pdf/file.pdf menjadi materi_pdf/file.pdf
                $relativePath = str_replace('/storage/', '', $materi->file_pdf);
                if (Storage::disk('public')->exists($relativePath)) {
                    Storage::disk('public')->delete($relativePath);
                } else {
                    Log::warning('File PDF tidak ditemukan di storage saat mencoba menghapus: ' . $relativePath);
                }
            }

            // Hapus record materi dari database
            $materi->delete();

            return redirect()->route('admin.materi.index')->with('success', 'Materi berhasil dihapus!');
        } catch (\Exception $e) {
            Log::error('Error deleting materi: ' . $e->getMessage());
            return redirect()->route('admin.materi.index')->with('error', 'Gagal menghapus materi: ' . $e->getMessage());
        }
    }

    // menampilkan form untuk mengedit materi
    public function editMateri(Materi $materi)
    {
        $levels = Level::all();
        return view('admin.materi.edit', compact('materi', 'levels'));
    }

    // update materi
    public function updateMateri(Request $request, Materi $materi) // Menggunakan Route Model Binding
    {
        // 1. Validasi Input
        $request->validate([
            'level_id' => 'required|exists:tb_level,id',
            'judul_materi' => 'required|string|max:255',
            'deskripsi_materi' => 'nullable|string', // Kolom baru, boleh kosong
            'urutan' => 'required|integer|min:1',
            'file_pdf' => 'nullable|mimes:pdf|max:10240', // File PDF boleh kosong jika tidak diubah
        ]);

        $storedPath = $materi->file_pdf; // Defaultnya ambil path yang sudah ada

        // 2. Upload File PDF Baru (Jika ada)
        if ($request->hasFile('file_pdf')) {
            $file = $request->file('file_pdf');
            $fileName = time() . '_' . $file->getClientOriginalName();

            try {
                // Hapus file PDF lama jika ada dan file baru diunggah
                if ($materi->file_pdf) {
                    Storage::disk('public')->delete($materi->file_pdf);
                }

                // Simpan file baru ke storage/app/public/materi_pdf
                $storedPath = Storage::disk('public')->putFileAs('materi_pdf', $file, $fileName);
            } catch (\Exception $e) {
                Log::error('Error updating PDF file: ' . $e->getMessage());
                return back()->withErrors(['file_pdf' => 'Gagal memperbarui file PDF.'])->withInput();
            }
        }

        // 3. Simpan Perubahan Data ke Database
        try {
            $materi->update([
                'level_id' => $request->level_id,
                'judul_materi' => $request->judul_materi,
                'deskripsi_materi' => $request->deskripsi_materi, // Simpan deskripsi baru
                'file_pdf' => $storedPath, // Simpan path PDF yang diperbarui/lama
                'urutan' => $request->urutan,
            ]);
        } catch (\Exception $e) {
            Log::error('Error updating materi in database: ' . $e->getMessage());
            return back()->withErrors(['database' => 'Gagal memperbarui materi di database.'])->withInput();
        }

        return redirect()->route('admin.materi.index')->with('success', 'Materi berhasil diperbarui!');
    }

    //profil admin
    public function showProfile()
    {
        return view('admin.profile_admin');
    }
    public function editProfile()
    {
        $admin = Auth::guard('admin')->user();
        return view('admin.editProfilAdmin', compact('admin'));
    }

    /**
     * Memperbarui data profil admin.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateProfile(Request $request)
    {
        // Langsung gunakan instance admin yang terautentikasi
        $authAdmin = Auth::guard('admin')->user();

        // Periksa apakah admin terautentikasi. Jika tidak, redirect ke halaman login.
        if (!$authAdmin) {
            return redirect()->route('loginAdmin')->with('error', 'Sesi Anda telah berakhir. Silakan login kembali.');
        }

        // Ambil ulang admin dari database agar bisa menggunakan Eloquent (memiliki method save)
        $admin = ModelAdmin::find($authAdmin->id_admin); // Gunakan ModelAdmin langsung
        if (!$admin) {
            return redirect()->route('loginAdmin')->with('error', 'Admin tidak ditemukan.');
        }

        // Validasi input
        $request->validate([
            'nama' => 'required|string|max:255', // Mengubah 'name' menjadi 'nama'
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                // Pastikan email unik, kecuali untuk admin yang sedang diedit
                Rule::unique('tb_admin', 'email')->ignore($admin->id_admin, 'id_admin'), // *** PERBAIKAN DI SINI ***
            ],
            // Password bersifat opsional, hanya divalidasi jika diisi
            'password' => 'nullable|string|min:8|confirmed',
        ], [
            'email.unique' => 'Email ini sudah terdaftar untuk pengguna lain.',
            'password.min' => 'Password baru harus minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password baru tidak cocok.',
        ]);

        // Perbarui data admin
        $admin->nama = $request->nama; // Mengubah $admin->name menjadi $admin->nama
        $admin->email = $request->email;

        // Perbarui password hanya jika diisi
        if ($request->filled('password')) {
            $admin->password = Hash::make($request->password);
        }

        $admin->save(); // Simpan perubahan ke database

        return redirect()->route('admin.profile')->with('success', 'Profil admin berhasil diperbarui!');
    }

    public function createAdmin()
    {
        return view('admin.create_admin'); // Mengarahkan ke tampilan baru
    }

    /**
     * Menyimpan akun admin baru ke database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeAdmin(Request $request)
    {
        // Validasi input untuk admin baru
        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:tb_admin,email', // Pastikan email unik di tabel tb_admin
            'password' => 'required|string|min:8|confirmed',
        ], [
            'nama.required' => 'Nama lengkap wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email ini sudah terdaftar untuk admin lain.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        // Buat instance ModelAdmin baru
        $admin = new ModelAdmin();
        $admin->nama = $request->nama;
        $admin->email = $request->email;
        $admin->password = Hash::make($request->password); // Hash password sebelum menyimpan
        $admin->save(); // Simpan admin baru ke database

        return redirect()->route('admin.create')->with('success', 'Akun admin baru berhasil dibuat!');
    }

    public function storeLevel(Request $request)
    {
        try {
            $request->validate([
                'nama_level' => 'required|string|max:255|unique:tb_level,nama_level',
                'deskripsi' => 'nullable|string',
            ], [
                'nama_level.required' => 'Nama level wajib diisi.',
                'nama_level.unique' => 'Nama level ini sudah ada.',
                'nama_level.max' => 'Nama level terlalu panjang.',
            ]);

            $level = Level::create([
                'nama_level' => $request->nama_level,
                'deskripsi' => $request->deskripsi,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Level berhasil ditambahkan!',
                'level' => [
                    'id' => $level->id,
                    'nama_level' => $level->nama_level,
                ]
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal.',
                'errors' => $e->errors()
            ], 422); // Unprocessable Entity
        } catch (\Exception $e) {
            Log::error('Error creating new level: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan level.'
            ], 500); // Internal Server Error
        }
    }
}
