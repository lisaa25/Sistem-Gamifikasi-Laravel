<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class ModelAdmin extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'tb_admin'; //tabel admin
    protected $primaryKey = 'id_admin';
    public $timestamps = true;

    protected $fillable = [
        'nama',
        'email', 
        'password'
    ];

}