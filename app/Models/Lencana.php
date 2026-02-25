<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lencana extends Model
{
    use HasFactory;

    protected $table = 'tb_lencana'; // Pastikan nama tabelnya benar
    protected $fillable = ['nama_lencana', 'deskripsi', 'gambar'];

    // Relasi: Sebuah lencana bisa dimiliki oleh banyak siswa
    public function siswa()
    {
        return $this->belongsToMany(ModelUser::class, 'tb_siswa_lencana', 'lencana_id', 'siswa_id')
            ->withPivot('tanggal_dicapai')
            ->withTimestamps();
    }
}
