<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserMateriProgress extends Model
{
    use HasFactory;

    protected $table = 'user_materi_progress';
    protected $fillable = [
        'user_id',
        'materi_id',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(ModelUser::class, 'user_id'); // Pastikan ini mengarah ke ModelUser Anda
    }

    public function materi()
    {
        return $this->belongsTo(Materi::class, 'materi_id');
    }
}
