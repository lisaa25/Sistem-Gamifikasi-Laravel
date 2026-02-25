<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Materi extends Model
{
    use HasFactory;

    protected $table = 'tb_materi';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'level_id',
        'judul_materi',
        'deskripsi_materi',
        'file_pdf',
        'urutan',
    ];
    public function level()
    {
        return $this->belongsTo(Level::class, 'level_id');
    }

    // Relasi baru: Sebuah Materi bisa memiliki banyak Kuis (soal)
    public function kuis()
    {
        return $this->hasMany(Kuis::class, 'materi_id');
    }

    public function userProgress()
    {
        return $this->hasMany(UserMateriProgress::class, 'materi_id');
    }
}
