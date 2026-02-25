<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ModelUser;
use App\Models\Materi;

class HasilKuis extends Model
{
    use HasFactory;

    protected $table = 'tb_hasil_kuis';

    protected $fillable = [
        'user_id', //awalya user_id
        'materi_id',
        'kuis_id',
        'skor_benar',
        'skor_salah',
        'nilai_kuis',
        'koin_didapatkan',
        'waktu_mengerjakan',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'waktu_mengerjakan' => 'datetime', // <<<=== TAMBAHKAN BARIS INI ===>>>
        'created_at' => 'datetime', // Biasanya sudah otomatis, tapi bisa ditambahkan untuk eksplisit
        'updated_at' => 'datetime', // Biasanya sudah otomatis, tapi bisa ditambahkan untuk eksplisit
    ];

    // Relasi ke User
    public function user()
    {
        return $this->belongsTo(ModelUser::class, 'user_id');
    }

    // Relasi ke Materi
    public function materi()
    {
        return $this->belongsTo(Materi::class, 'materi_id', 'id');
    }

    // Tambahkan relasi ke Kuis
    public function kuis()
    {
        return $this->belongsTo(Kuis::class, 'kuis_id');
    }

    public function jawabanSiswa()
    {
        return $this->hasMany(JawabanSiswa::class, 'hasil_kuis_id');
    }
}
