<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserModel extends Model
{
    use HasFactory;
    protected $table = "kategori";
    protected $fillable = [
        'nama',
        'deskripsi',
    ];
}
