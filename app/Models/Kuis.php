<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kuis extends Model
{
    use HasFactory;
    protected $table = 'tb_kuis'; // Sesuaikan dengan nama tabel di database
    protected $primaryKey = 'id';
    protected $fillable = [
        'materi_id',
        'pertanyaan',
        'opsi_a',
        'opsi_b',
        'opsi_c',
        'opsi_d',
        'jawaban', // Ini akan menyimpan 'A', 'B', 'C', atau 'D'
    ];

    public function materi()
    {
        return $this->belongsTo(Materi::class, 'materi_id', 'id');
    }

    // Relasi: Sebuah Kuis bisa memiliki banyak HasilKuis
    public function hasilKuis()
    {
        return $this->hasMany(HasilKuis::class, 'kuis_id');
    }
}
