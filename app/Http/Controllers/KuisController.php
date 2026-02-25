<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Materi;
use App\Models\Kuis;
use App\Models\Level;
use App\Models\ModelUser;
use App\Models\JawabanSiswa; // Pastikan Anda mengimpor model JawabanSiswa
use App\Models\UserMateriProgress; // Pastikan Anda mengimpor model UserMateriProgress
use Illuminate\Support\Facades\Log; // Untuk logging error
use App\Models\HasilKuis; // Pastikan Anda mengimpor model HasilKuis Anda
use App\Models\Poin;      // Pastikan Anda mengimpor model Poin Anda
use Illuminate\Support\Facades\Auth; // Untuk mendapatkan ID siswa yang sedang login
use Illuminate\Support\Facades\DB; // Jika perlu transaksi database
use Illuminate\Validation\ValidationException;
use App\Services\BadgeService;


class KuisController extends Controller
{
    protected $badgeService;

    public function __construct(BadgeService $badgeService)
    {
        $this->badgeService = $badgeService;
    }

    public function indexAll()
    {
        $kuis = Kuis::with('materi')->orderBy('materi_id')->get();
        return view('admin.kuis.index_all', compact('kuis'));
    }

    public function index(Materi $materi)
    {
        $kuis = $materi->kuis()->orderBy('id')->get();
        return view('admin.kuis.index', compact('materi', 'kuis'));
    }

    public function create(Materi $materi)
    {
        return view('admin.kuis.create', compact('materi'));
    }

    public function store(Request $request, Materi $materi)
    {
        // Validasi input untuk setiap soal kuis yang dikirim dalam bentuk array
        // Tanda '*' digunakan untuk memvalidasi setiap elemen dalam array
        $request->validate([
            'pertanyaan.*' => 'required|string|max:1000', // Batasi panjang pertanyaan
            'opsi_a.*' => 'required|string|max:255',
            'opsi_b.*' => 'required|string|max:255',
            'opsi_c.*' => 'required|string|max:255',
            'opsi_d.*' => 'required|string|max:255',
            'jawaban.*' => 'required|in:A,B,C,D', // Jawaban harus salah satu dari A, B, C, D
        ], [
            // Pesan kustom untuk validasi
            'pertanyaan.*.required' => 'Setiap pertanyaan kuis wajib diisi.',
            'pertanyaan.*.string' => 'Pertanyaan harus berupa teks.',
            'pertanyaan.*.max' => 'Panjang pertanyaan tidak boleh melebihi :max karakter.',
            'opsi_a.*.required' => 'Opsi A wajib diisi untuk setiap soal.',
            'opsi_b.*.required' => 'Opsi B wajib diisi untuk setiap soal.',
            'opsi_c.*.required' => 'Opsi C wajib diisi untuk setiap soal.',
            'opsi_d.*.required' => 'Opsi D wajib diisi untuk setiap soal.',
            'jawaban.*.required' => 'Jawaban benar wajib dipilih untuk setiap soal.',
            'jawaban.*.in' => 'Jawaban benar harus A, B, C, atau D.',
        ]);

        try {
            // Loop melalui setiap set data soal kuis yang diterima
            // Data dikirim dalam format array seperti pertanyaan[0], pertanyaan[1], dst.
            // Kita bisa mengiterasi salah satu array (misal: pertanyaan) dan menggunakan indeksnya
            // untuk mengakses data opsi dan jawaban yang sesuai.
            foreach ($request->pertanyaan as $index => $pertanyaan) {
                // Buat record Kuis baru untuk setiap soal
                $materi->kuis()->create([ // Menggunakan relasi materi->kuis()
                    'pertanyaan' => $pertanyaan,
                    'opsi_a' => $request->opsi_a[$index],
                    'opsi_b' => $request->opsi_b[$index],
                    'opsi_c' => $request->opsi_c[$index],
                    'opsi_d' => $request->opsi_d[$index],
                    'jawaban' => $request->jawaban[$index],
                    // Tambahkan kolom lain jika ada di tabel kuis Anda, misalnya 'koin_didapatkan'
                    // 'koin_didapatkan' => 10, // Contoh nilai default
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Error saving quiz question: ' . $e->getMessage());
            return back()->withErrors(['database' => 'Gagal menyimpan soal kuis.'])->withInput();
        }


        // Redirect kembali ke halaman daftar kuis untuk materi ini dengan pesan sukses
        return redirect()->route('kuis.index', $materi->id)->with('success', 'Soal kuis berhasil ditambahkan!');
    }

    public function edit(Materi $materi, Kuis $kuis)
    {
        if ($kuis->materi_id !== $materi->id) {
            abort(404);
        }
        return view('admin.kuis.edit', compact('materi', 'kuis'));
    }

    public function update(Request $request, Materi $materi, Kuis $kuis)
    {
        if ($kuis->materi_id !== $materi->id) {
            abort(404);
        }

        $request->validate([
            'pertanyaan' => 'required|string',
            'opsi_a' => 'required|string|max:255',
            'opsi_b' => 'required|string|max:255',
            'opsi_c' => 'required|string|max:255',
            'opsi_d' => 'required|string|max:255',
            'jawaban' => 'required|in:A,B,C,D',
        ]);

        try {
            $kuis->update([
                'pertanyaan' => $request->pertanyaan,
                'opsi_a' => $request->opsi_a,
                'opsi_b' => $request->opsi_b,
                'opsi_c' => $request->opsi_c,
                'opsi_d' => $request->opsi_d,
                'jawaban' => $request->jawaban,
            ]);
        } catch (\Exception $e) {
            Log::error('Error updating quiz question: ' . $e->getMessage());
            return back()->withErrors(['database' => 'Gagal memperbarui soal kuis.'])->withInput();
        }

        return redirect()->route('kuis.index', $materi->id)->with('success', 'Soal kuis berhasil diperbarui!');
    }

    public function destroy(Materi $materi, Kuis $kuis)
    {
        if ($kuis->materi_id !== $materi->id) {
            abort(404);
        }

        try {
            $kuis->delete();
        } catch (\Exception $e) {
            Log::error('Error deleting quiz question: ' . $e->getMessage());
            return back()->with('error', 'Gagal menghapus soal kuis: ' . $e->getMessage());
        }

        return redirect()->route('kuis.index', $materi->id)->with('success', 'Soal kuis berhasil dihapus!');
    }

    public function showQuizForStudent(Materi $materi)
    {
        Log::info('--- Memulai KuisController@showQuizForStudent ---');
        Log::info('Materi ID yang diterima: ' . $materi->id);

        try {
            $soalKuis = $materi->kuis()
                ->inRandomOrder()
                ->get();

            Log::info('Jumlah soal kuis yang ditemukan dari DB untuk Materi ID ' . $materi->id . ': ' . $soalKuis->count());
            Log::info('Data mentah soal kuis: ' . $soalKuis->toJson());

            if ($soalKuis->isEmpty()) {
                Log::warning('Tidak ada soal kuis ditemukan untuk materi ini. Redirecting atau menampilkan pesan.');
                return view('kerangka.cubacuba', ['daftarSoalUntukJs' => collect(), 'materi' => $materi]);
            }

            $daftarSoalUntukJs = $soalKuis->map(function ($soal) {
                $jawabanBenarTeks = '';
                switch ($soal->jawaban) {
                    case 'A':
                        $jawabanBenarTeks = $soal->opsi_a;
                        break;
                    case 'B':
                        $jawabanBenarTeks = $soal->opsi_b;
                        break;
                    case 'C':
                        $jawabanBenarTeks = $soal->opsi_c;
                        break;
                    case 'D':
                        $jawabanBenarTeks = $soal->opsi_d;
                        break;
                    default:
                        $jawabanBenarTeks = '';
                        Log::warning('Jawaban tidak valid untuk soal ID: ' . $soal->id . ' Jawaban: ' . $soal->jawaban);
                        break;
                }

                return [
                    'id'        => $soal->id,
                    'teks'      => $soal->pertanyaan,
                    'jawaban'   => $jawabanBenarTeks,
                    'pilihan'   => [
                        $soal->opsi_a,
                        $soal->opsi_b,
                        $soal->opsi_c,
                        $soal->opsi_d,
                    ],
                ];
            });

            Log::info('Data daftarSoalUntukJs setelah diformat (siap untuk JS): ' . $daftarSoalUntukJs->toJson());

            return view('kerangka.cubacuba', compact('daftarSoalUntukJs', 'materi'));
        } catch (\Exception $e) {
            Log::error('Kesalahan fatal di KuisController@showQuizForStudent: ' . $e->getMessage());
            Log::error('Stack Trace: ' . $e->getTraceAsString());
            return response('Terjadi kesalahan saat memuat kuis. Silakan periksa log server.', 500);
        }
    }

    public function simpanHasilKuis(Request $request)
    {
        try {
            $validated = $request->validate([
                'materi_id' => 'required|exists:tb_materi,id',
                'skor_benar' => 'nullable|integer|min:0',
                'skor_salah' => 'nullable|integer|min:0',
                'nilai_kuis' => 'nullable|numeric|min:0|max:100', // Ubah ke numeric jika nilai bisa float
                'jawaban_siswa' => 'required|array',
                'jawaban_siswa.*.soal_id' => 'required|exists:tb_kuis,id',
                'jawaban_siswa.*.jawaban_user' => 'required|string',
                'jawaban_siswa.*.is_correct' => 'required|boolean',
            ], [
                'jawaban_siswa.required' => 'Setidaknya ada satu jawaban siswa yang harus disimpan.',
                'jawaban_siswa.*.soal_id.required' => 'ID soal wajib diisi.',
                'jawaban_siswa.*.soal_id.exists' => 'Soal tidak ditemukan.',
                'jawaban_siswa.*.jawaban_user.required' => 'Jawaban user wajib diisi.',
                'jawaban_siswa.*.is_correct.required' => 'Status kebenaran jawaban wajib diisi.',
            ]);

            $userId = Auth::id();
            // <<< MODIFIKASI INI: Dapatkan objek user dari ModelUser
            $user = ModelUser::find($userId);
            if (!$user) {
                throw new \Exception("User tidak ditemukan.");
            }
            // >>> AKHIR MODIFIKASI

            $currentMateriId = $validated['materi_id'];

            $skorBenarBackend = 0;
            $skorSalahBackend = 0;
            $totalSoalDijawab = count($validated['jawaban_siswa']);

            $koinDariJawabanBenar = 0; // Koin dari jawaban benar saja
            $koinBonus = 0;           // Koin bonus karena lulus kuis
            $totalKoinDidapatkanDariKuis = 0; // Total koin untuk kuis ini

            foreach ($validated['jawaban_siswa'] as $jawabanData) {
                $soal = Kuis::find($jawabanData['soal_id']);

                if ($soal) {
                    $jawabanBenarAsli = '';
                    switch ($soal->jawaban) {
                        case 'A':
                            $jawabanBenarAsli = $soal->opsi_a;
                            break;
                        case 'B':
                            $jawabanBenarAsli = $soal->opsi_b;
                            break;
                        case 'C':
                            $jawabanBenarAsli = $soal->opsi_c;
                            break;
                        case 'D':
                            $jawabanBenarAsli = $soal->opsi_d;
                            break;
                    }

                    if ($jawabanData['jawaban_user'] === $jawabanBenarAsli) {
                        $skorBenarBackend++;
                        $koinDariJawabanBenar += 10; // Setiap jawaban benar memberi 10 koin
                    } else {
                        $skorSalahBackend++;
                    }
                } else {
                    Log::warning("Soal ID {$jawabanData['soal_id']} tidak ditemukan di database saat validasi backend.");
                }
            }

            $nilaiKuisBackend = $totalSoalDijawab > 0 ? ($skorBenarBackend / $totalSoalDijawab) * 100 : 0;
            $batasKelulusan = 70;
            $canContinue = false;

            // Koin dari jawaban benar selalu dihitung
            $totalKoinDidapatkanDariKuis = $koinDariJawabanBenar;

            if ($nilaiKuisBackend >= $batasKelulusan) {
                $canContinue = true;
                $koinBonus = 50; // Bonus tetap 50 untuk lulus kuis
                $totalKoinDidapatkanDariKuis += $koinBonus;

                UserMateriProgress::updateOrCreate(
                    ['user_id' => $userId, 'materi_id' => $currentMateriId],
                    ['status' => 'completed']
                );

                $currentMateri = Materi::with('level')->find($currentMateriId);

                if ($currentMateri) {
                    $currentLevelId = $currentMateri->level_id;
                    $currentUrutan = $currentMateri->urutan;

                    $nextMateri = Materi::where('level_id', $currentLevelId)
                        ->where('urutan', $currentUrutan + 1)
                        ->first();

                    if ($nextMateri) {
                        UserMateriProgress::updateOrCreate(
                            ['user_id' => $userId, 'materi_id' => $nextMateri->id],
                            ['status' => 'unlocked']
                        );
                        Log::info("User {$userId} membuka materi: {$nextMateri->judul_materi} (ID: {$nextMateri->id}) di level yang sama.");
                        // Tidak ada penambahan koin di sini lagi karena sudah dihitung sebagai 'koinBonus'
                    } else {
                        $nextLevel = Level::where('id', '>', $currentLevelId)
                            ->orderBy('id', 'asc')
                            ->first();

                        if ($nextLevel) {
                            $firstMateriInNextLevel = Materi::where('level_id', $nextLevel->id)
                                ->orderBy('urutan', 'asc')
                                ->first();
                            if ($firstMateriInNextLevel) {
                                UserMateriProgress::updateOrCreate(
                                    ['user_id' => $userId, 'materi_id' => $firstMateriInNextLevel->id],
                                    ['status' => 'unlocked']
                                );
                                Log::info("User {$userId} menyelesaikan Level {$currentLevelId} dan membuka materi pertama Level {$nextLevel->nama_level}: {$firstMateriInNextLevel->judul_materi} (ID: {$firstMateriInNextLevel->id}).");
                                // Tidak ada penambahan koin di sini lagi karena sudah dihitung sebagai 'koinBonus'
                            } else {
                                Log::warning("Level {$nextLevel->nama_level} (ID: {$nextLevel->id}) tidak memiliki materi.");
                            }
                        } else {
                            Log::info("User {$userId} telah menyelesaikan materi terakhir dari level terakhir.");
                        }
                    }
                } else {
                    Log::warning("Materi dengan ID {$currentMateriId} tidak ditemukan saat menyimpan hasil kuis dan mencoba membuka materi berikutnya.");
                }
            }

            // HasilKuis akan selalu disimpan
            $hasilKuis = HasilKuis::create([
                'user_id' => $userId,
                'materi_id' => $currentMateriId,
                'skor_benar' => $skorBenarBackend,
                'skor_salah' => $skorSalahBackend,
                'nilai_kuis' => $nilaiKuisBackend,
                'koin_didapatkan' => $totalKoinDidapatkanDariKuis, // Total koin untuk kuis ini
                'waktu_mengerjakan' => now(),
            ]);

            foreach ($validated['jawaban_siswa'] as $jawabanData) {
                JawabanSiswa::create([
                    'hasil_kuis_id' => $hasilKuis->id,
                    'soal_id' => $jawabanData['soal_id'],
                    'jawaban_user' => $jawabanData['jawaban_user'],
                    'is_correct' => $jawabanData['is_correct'],
                ]);
            }

            // Update total koin user
            // <<< MODIFIKASI INI: Gunakan objek $user yang sudah didapatkan di awal
            $user->total_koin += $totalKoinDidapatkanDariKuis; // Tambahkan total koin yang didapatkan dari kuis ini
            $user->save(); // Simpan perubahan pada objek user
            Log::info("User {$userId} total koin diupdate menjadi: {$user->total_koin} (dari kuis).");
            // >>> AKHIR MODIFIKASI

            // <<< TAMBAHKAN INI: Panggil BadgeService untuk mengecek lencana "Si Paling Pintar"
            $newlyAwardedBadge = $this->badgeService->checkSiPalingPintarBadge($user, $nilaiKuisBackend);
            if ($newlyAwardedBadge) {
                Log::info("Lencana Si Paling Pintar berhasil diberikan dan akan dikirim ke frontend.");
            } else {
                Log::info("Tidak ada lencana Si Paling Pintar baru yang diberikan atau sudah dimiliki.");
            }
            // >>> AKHIR TAMBAHAN

            return response()->json([
                'success' => true,
                'message' => 'Hasil kuis berhasil disimpan.',
                'koin_didapatkan' => $totalKoinDidapatkanDariKuis, // Total koin dari kuis ini (untuk frontend)
                'koin_dari_jawaban' => $koinDariJawabanBenar, // Koin dari jawaban benar (untuk frontend)
                'koin_bonus' => $koinBonus, // Koin bonus (untuk frontend)
                'can_continue' => $canContinue,
                'hasil_kuis_id' => $hasilKuis->id,
                'nilai_kuis' => $nilaiKuisBackend,
                'total_koin_user' => $user->total_koin, // Pastikan ini mengambil dari $user yang sudah diupdate
                'newly_awarded_badge' => $newlyAwardedBadge, // <<< TAMBAHKAN INI: Kirim data lencana baru ke frontend
            ]);
        } catch (ValidationException $e) {
            Log::error('Validasi gagal saat menyimpan hasil kuis: ' . $e->getMessage(), ['errors' => $e->errors()]);
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal: ' . $e->getMessage(),
                'errors' => $e->errors()
            ], 422);
        } catch (\Throwable $e) {
            Log::error('Error menyimpan hasil kuis: ' . $e->getMessage(), ['exception' => $e]);
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan server: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function showDetailHasilKuis($hasilKuisId)
    {
        $user = Auth::user();

        $hasilKuis = HasilKuis::where('id', $hasilKuisId)
            ->where('user_id', $user->id)
            ->with(['materi', 'jawabanSiswa.soal'])
            ->firstOrFail();

        $materi = $hasilKuis->materi;

        return view('riwayat.detail_kuis', compact('hasilKuis', 'materi'));
    }

    public function showQuizHistory(Materi $materi)
    {
        $userId = Auth::id();

        $userHasilKuis = HasilKuis::where('user_id', $userId)
            ->where('materi_id', $materi->id)
            ->orderBy('created_at', 'desc')
            ->get();

        $highestScore = $userHasilKuis->max('nilai_kuis');

        return view('kerangka.riwayat_kuis', compact('materi', 'userHasilKuis', 'highestScore'));
    }
}
