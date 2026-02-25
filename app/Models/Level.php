<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Level extends Model
{
    use HasFactory;
    protected $table = 'tb_level';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'nama_level',
        'deskripsi',
    ];

    public function materi()
    {
        return $this->hasMany(Materi::class, 'level_id');
    }

    public function users()
    {
        return $this->hasMany(ModelUser::class, 'level_id');
    }
}
