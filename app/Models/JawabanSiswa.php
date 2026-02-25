<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JawabanSiswa extends Model
{
    use HasFactory;

    protected $table = 'tb_jawaban_siswa'; // Nama tabel di database

    protected $fillable = [
        'hasil_kuis_id',
        'soal_id',
        'pilihan_terpilih', // Kolom untuk menyimpan pilihan siswa (A, B, C, D)
        'is_correct',
    ];

    // Relasi ke HasilKuis
    public function hasilKuis()
    {
        return $this->belongsTo(HasilKuis::class, 'hasil_kuis_id');
    }

    // Relasi ke soal kuis (Model Kuis Anda)
    public function soal()
    {
        return $this->belongsTo(Kuis::class, 'soal_id'); // Mengacu ke Model Kuis (tb_kuis)
    }
}
