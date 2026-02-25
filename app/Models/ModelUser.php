<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Model; // Ini sudah ada
use App\Models\HasilKuis; // Ini sudah ada
// use App\Models\Lencana; // Ini sudah ada, tapi jika ada error, coba pakai namespace lengkap di bawah

/**
 * @property \Illuminate\Database\Eloquent\Collection|\App\Models\Lencana[] $lencana
 */

class ModelUser extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'tb_user';

    protected $fillable = [
        'nama',
        'kelas',
        'no_telepon',
        'alamat',
        'email',
        'password',
        'foto',
        'total_koin',
        'quiz_win_streak',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function hasilKuis()
    {
        return $this->hasMany(HasilKuis::class, 'user_id');
    }

    public function poin()
    {
        // Pastikan model Poin ada dan benar, atau hapus jika tidak digunakan
        return $this->hasOne(Poin::class, 'user_id');
    }

    public function materiProgress()
    {
        // Pastikan model UserMateriProgress ada dan benar
        return $this->hasMany(UserMateriProgress::class, 'user_id');
    }

    public function getTotalKoinAttribute()
    {
        return $this->hasilKuis()->sum('koin_didapatkan');
    }

    // Relasi ke tabel tb_siswa_lencana (lencana yang dimiliki user)
    public function lencana()
    {
        return $this->belongsToMany(Lencana::class, 'tb_siswa_lencana', 'siswa_id', 'lencana_id')
            ->withPivot('tanggal_dicapai')
            ->withTimestamps(); // <<< TAMBAHKAN BARIS INI
    }

    /**
     * Mendefinisikan relasi banyak-ke-satu dengan Level.
     * Seorang user hanya memiliki satu level.
     */
    public function level()
    {
        return $this->belongsTo(Level::class, 'level_id');
    }

    public function getProfilePictureUrlAttribute()
    {
        // Path untuk foto yang diunggah user
        // Pastikan 'img/profil/' sesuai dengan folder di storage/app/public/
        if ($this->foto && $this->foto !== 'default_profile.png' && Storage::disk('public')->exists('img/profil/' . $this->foto)) {
            return asset('storage/img/profil/' . $this->foto);
        }

        // Jika foto tidak ada atau default_profile.png, gunakan default_avatar.png
        // Pastikan file default_avatar.png ada di storage/app/public/img/
        if (Storage::disk('public')->exists('img/default_avatar.png')) {
            return asset('storage/img/default_avatar.png');
        }

        // Fallback jika default_avatar.png juga tidak ada di storage/app/public/img/
        // Anda bisa menaruh default_profile.png di public/img/profil/
        return asset('img/profil/default_profile.png');
    }
}
