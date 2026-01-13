<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Servis extends Model
{
    use HasFactory;

    protected $table = 'servisi'; // <-- dodaj ovo da Laravel zna tačno ime tabele

    public function vozilo()
    {
        return $this->belongsTo(Vozilo::class);
    }

    public function serviser()
    {
        return $this->belongsTo(User::class, 'serviser_id');
    }

    protected $fillable = [
        'user_id',
        'vozilo_id',
        'vozilo',
        'tip_tahografa',
        'opis_problema',
        'termin',
        'telefon',
        'status',
    ];
}
