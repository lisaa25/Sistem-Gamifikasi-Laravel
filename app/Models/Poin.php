<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Poin extends Model
{
    use HasFactory;

    protected $table = 'tb_poin'; // Sesuaikan jika nama tabel berbeda

    protected $fillable = [
        'user_id',
        'total_poin',
        // ... kolom lain jika ada
    ];

    // Relasi ke User
    public function user()
    {
        return $this->belongsTo(ModelUser::class);
    }
}
