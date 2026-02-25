 <?php

    use Illuminate\Support\Facades\Route;
    use Illuminate\Support\Facades\DB;
    use App\Http\Controllers\NavbarController;
    use App\Http\Controllers\AdminController;
    use App\Http\Controllers\PembelajaranController;
    use App\Http\Controllers\UserInformationController;
    use App\Http\Controllers\AuthController;
    use App\Http\Controllers\UserController;
    use App\Http\Controllers\KuisController;
    use App\Http\Controllers\LencanaController;
    use App\Http\Controllers\DashboardSiswaController;
    use App\Http\Controllers\LeaderboardController;
    use App\Http\Controllers\StudentScoreControlle; // Pastikan ini diimpor

    // Menguji koneksi database (hanya untuk development, bisa dihapus di production)
    Route::get('test-connection', function () {
        dd(DB::connection()->getPdo());
    });

    // --- Rute Umum (Public) ---
    Route::get('/landing', function () {
        return view('layout.landingpage');
    })->name('landingpage'); // Beri nama untuk kemudahan

    // Ubah route utama untuk menampilkan halaman intro
    Route::get('/', function () {
        return view('intro.intro');
    })->name('intro');

    Route::get('/story', function () {
        return view('intro.story');
    })->name('story');


    // Rute Navbar (Asumsi ini juga public)
    Route::get('/home', [NavbarController::class, 'home'])->name('home');
    Route::get('/produk', [NavbarController::class, 'produk'])->name('produk');
    Route::get('/about', [NavbarController::class, 'about'])->name('about');

    // --- Rute Autentikasi (Siswa) ---
    Route::controller(AuthController::class)->group(function () {
        Route::get('/login', 'login')->name('login');
        Route::post('/login', 'loginPost')->name('loginPost');
        Route::post('/logout', 'logout')->name('logout');
    });

    // --- Rute Autentikasi (Admin) ---
    // Login Admin
    Route::get('/loginAdmin', [AdminController::class, 'loginAdmin'])->name('loginAdmin');
    Route::post('/admin-authenticate', [AdminController::class, 'adminPost'])->name('adminPost'); // Ganti nama route post agar lebih jelas

    // --- Rute Admin (Memerlukan auth:admin) ---
    Route::middleware(['auth:admin'])->prefix('admin')->group(function () { // Gunakan prefix 'admin' untuk rute admin
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
        Route::get('/statistik', [AdminController::class, 'dashboard'])->name('statistik'); // Alias untuk dashboard jika statistik adalah dashboard
        Route::post('/logout', [AdminController::class, 'logout'])->name('admin.logout'); // Sudah ada

        // Manajemen Siswa (Users) oleh Admin
        Route::get('/users', [AdminController::class, 'showUsers'])->name('admin.users');
        Route::delete('/users/{id}', [AdminController::class, 'deleteUser'])->name('admin.users.delete');
        Route::get('/users/{id}/edit', [AdminController::class, 'editUser'])->name('admin.users.edit');
        Route::put('/users/{id}', [AdminController::class, 'updateUser'])->name('admin.users.update');
        Route::get('/users/create', [AdminController::class, 'createUser'])->name('admin.users.create');
        Route::post('/users/store', [AdminController::class, 'storeUser'])->name('admin.users.store');

        // Manajemen Materi oleh Admin (INI BARU/DIPERBAIKI)
        Route::get('/materi/create', [AdminController::class, 'createMateri'])->name('admin.materi.create');
        Route::post('/materi', [AdminController::class, 'storeMateri'])->name('admin.materi.store');
        Route::get('/materi', [AdminController::class, 'indexMateri'])->name('admin.materi.index');
        Route::delete('/materi/{materi}', [AdminController::class, 'destroyMateri'])->name('admin.materi.destroy');

        // Manajemen Kuis oleh Admin
        Route::get('/kuis', [AdminController::class, 'kelolaKuis'])->name('kelolakuis');

        // Route untuk menampilkan form edit materi
        Route::get('/admin/materi/{materi}/edit', [AdminController::class, 'editMateri'])->name('admin.materi.edit');
        // Route untuk memproses update materi
        Route::put('/admin/materi/{materi}', [AdminController::class, 'updateMateri'])->name('admin.materi.update');

        // --- ROUTES UNTUK KUIS ADMIN ---
        // Menampilkan daftar soal kuis untuk materi tertentu
        Route::get('/materi/{materi}/kuis', [KuisController::class, 'index'])->name('kuis.index');

        // Menampilkan form untuk menambah soal kuis baru untuk materi tertentu
        Route::get('/materi/{materi}/kuis/create', [KuisController::class, 'create'])->name('kuis.create');

        // Menyimpan soal kuis baru
        Route::post('/materi/{materi}/kuis', [KuisController::class, 'store'])->name('kuis.store');

        // Menampilkan form untuk mengedit soal kuis
        Route::get('/materi/{materi}/kuis/{kuis}/edit', [KuisController::class, 'edit'])->name('kuis.edit');

        // Memperbarui soal kuis
        Route::put('/materi/{materi}/kuis/{kuis}', [KuisController::class, 'update'])->name('kuis.update');

        // Menghapus soal kuis
        Route::delete('/materi/{materi}/kuis/{kuis}', [KuisController::class, 'destroy'])->name('kuis.destroy');

        // --- ROUTE BARU UNTUK OVERVIEW SEMUA KUIS ---
        Route::get('/kuis-overview', [KuisController::class, 'indexAll'])->name('kuis.overview');


        // ... (rute-rute Anda yang lain)

        Route::prefix('admin')->name('admin.')->middleware('auth:admin')->group(function () {
            // ... rute-rute admin yang sudah ada (users, materi, kuis, dll.)

            // Rute untuk Nilai Siswa
            Route::get('/student-scores', [StudentScoreControlle::class, 'index'])->name('student_scores.index');
        });

        // ... (rute-rute Anda yang lain)

        Route::prefix('admin')->name('admin.')->middleware('auth:admin')->group(function () {
            // ... rute-rute admin yang sudah ada (users, materi, kuis, dll.)

            // Rute untuk Nilai Siswa (Daftar)
            Route::get('/student-scores', [StudentScoreControlle::class, 'index'])->name('student_scores.index');

            // Rute untuk Rincian Nilai Siswa
            // Menggunakan {user} sebagai parameter yang akan di-resolve oleh Route Model Binding ke ModelUser
            Route::get('/student-scores/{user}/details', [StudentScoreControlle::class, 'showDetails'])->name('student_scores.details');
        });

        Route::prefix('admin')->name('admin.')->middleware('auth:admin')->group(function () {
            // ... rute-rute admin yang sudah ada (users, materi, kuis, student-scores, dll.)

            // Rute untuk Profil Admin (Tampilan)
            Route::get('/profile', [AdminController::class, 'showProfile'])->name('profile');
            // Rute untuk Edit Profil Admin (Formulir)
            Route::get('/profile/edit', [AdminController::class, 'editProfile'])->name('profile.edit');
            // Rute untuk Update Profil Admin (Proses Form)
            Route::put('/profile', [AdminController::class, 'updateProfile'])->name('profile.update');
        });
        Route::middleware(['auth:admin'])->group(function () {
            // ... rute admin yang sudah ada ...

            // Rute untuk menampilkan form pembuatan admin baru
            Route::get('/admin/create', [AdminController::class, 'createAdmin'])->name('admin.create');
            // Rute untuk menyimpan admin baru
            Route::post('/admin/store', [AdminController::class, 'storeAdmin'])->name('admin.store');

            // Pastikan rute profil admin sudah ada dan benar
            Route::get('/admin/profile', [AdminController::class, 'showProfile'])->name('admin.profile');
            Route::get('/admin/profile/edit', [AdminController::class, 'editProfile'])->name('admin.profile.edit');
            Route::put('/admin/profile', [AdminController::class, 'updateProfile'])->name('admin.profile.update');
        });
        // Rute BARU untuk Level (via AJAX)
        Route::post('/admin/levels/store', [AdminController::class, 'storeLevel'])->name('admin.levels.store');
    });

    /// --- Rute Siswa (Memerlukan auth) ---
    Route::middleware(['auth'])->group(function () {
        // Dashboard Siswa
        Route::get('/dashboard-siswa', [App\Http\Controllers\DashboardSiswaController::class, 'index'])->name('dashboard.siswa');

        // Route untuk memuat lebih banyak aktivitas (API endpoint)
        Route::get('/dashboard-siswa/load-more-activities', [App\Http\Controllers\DashboardSiswaController::class, 'loadMoreActivities'])->name('dashboard.load_more_activities');

        // Informasi Pengguna (Profil Siswa)
        Route::get('/user-information', [UserInformationController::class, 'show'])->name('user.show');
        Route::put('/user-update', [UserInformationController::class, 'update'])->name('user.update');
        Route::get('/profil', function () { // Jika profil siswa adalah tampilan statis dari kerangka.profil
            return view('kerangka.profil');
        })->name('profil.siswa');

        Route::middleware(['auth'])->group(function () {
            Route::get('/pembelajaran', [PembelajaranController::class, 'index'])->name('pembelajaran.index');
            Route::get('/materi/{materi}', [PembelajaranController::class, 'showMateri'])->name('materi.show');
        });

        // Route untuk menampilkan leaderboard siswa per kelas
        Route::get('/leaderboard/siswa', [LeaderboardController::class, 'showLeaderboard'])->name('leaderboard.siswa')->middleware('auth');

        //cuba cuba diganti jadi ini
        Route::get('/siswa/materi/{materi}/kuis', [KuisController::class, 'showQuizForStudent'])->name('siswa.kuis.show');
        // Route::post('/kuis/selesai', [KuisController::class, 'simpanHasilKuis'])->name('kuis.selesai')->middleware('auth'); // Pastikan user terautentikasi
        Route::post('/simpan-hasil-kuis', [KuisController::class, 'simpanHasilKuis'])->name('kuis.simpan');

        // rute menampilkan riwayat kuis
        Route::get('/kuis/{materi}/riwayat', [App\Http\Controllers\KuisController::class, 'showQuizHistory'])->name('kuis.history');

        //riwayat kuis detail
        Route::get('/riwayat-kuis/{hasilKuisId}/detail', [App\Http\Controllers\KuisController::class, 'showDetailHasilKuis'])->name('kuis.detail_riwayat');
    });

    Route::get('/intro', function () {
        return view('intro.intro');
    })->name('intro');

    Route::get('/story', function () {
        return view('intro.story');
    })->name('story');

    // ROUTE BARU UNTUK UPDATE PASSWORD
    Route::put('/profil/password', [UserInformationController::class, 'updatePassword'])->name('user.updatePassword');
    //Route::get('/login-guru', [GuruLoginController::class, 'showLoginForm'])->name('guru.login');
    Route::middleware(['auth'])->group(function () {
        // ... route profil yang sudah ada ...
        Route::get('/user-information', [UserInformationController::class, 'show'])->name('user.show');
        Route::post('/user-information/update-password', [UserInformationController::class, 'updatePassword'])->name('user.updatePassword');

        // Route baru untuk menampilkan semua lencana
        Route::get('/all-badges', [UserInformationController::class, 'showAllBadges'])->name('badges.all');
    });
