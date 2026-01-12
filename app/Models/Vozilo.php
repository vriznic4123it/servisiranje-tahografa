<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vozilo extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'registracija', 'marka', 'model'];

    // Ovo forsira tačno ime tabele
    protected $table = 'vozila';
}
