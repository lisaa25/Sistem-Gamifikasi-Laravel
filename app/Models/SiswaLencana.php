<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SiswaLencana extends Model
{
    use HasFactory;

    protected $table = 'tb_siswa_lencana'; // Pastikan nama tabelnya benar
    protected $fillable = ['siswa_id', 'lencana_id', 'tanggal_dicapai'];

    // Relasi: Setiap entri SiswaLencana terkait dengan satu ModelUser
    public function siswa()
    {
        return $this->belongsTo(ModelUser::class, 'siswa_id');
    }

    // Relasi: Setiap entri SiswaLencana terkait dengan satu Lencana
    public function lencana()
    {
        return $this->belongsTo(Lencana::class, 'lencana_id');
    }

    protected $casts = [
        'tanggal_dicapai' => 'date', // Cast ke tipe date
    ];
}
