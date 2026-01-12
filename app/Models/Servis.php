<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Servis extends Model
{
    use HasFactory;

    protected $table = 'servisi'; // <-- dodaj ovo da Laravel zna tačno ime tabele

    protected $fillable = [
        'vozilo_id',
        'user_id',
        'tip_tahografa',
        'termin',
        'status',
    ];
}
