<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Servis extends Model
{
    use HasFactory;

    protected $table = 'servisi';

    protected $fillable = [
        'user_id',
        'serviser_id',
        'vozilo_id',
        'vozilo',
        'tip_tahografa',
        'opis_problema',
        'termin',
        'telefon',
        'status',
    ];

    protected $casts = [
        'termin' => 'datetime',
    ];

    // Relacija ka klijentu
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Alias za klijenta (radi jasnoće)
    public function klijent()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relacija ka serviseru
    public function serviser()
    {
        return $this->belongsTo(User::class, 'serviser_id');
    }

    // Relacija ka vozilu
    public function voziloRelacija()
    {
        return $this->belongsTo(Vozilo::class, 'vozilo_id');
    }

    // Scope za aktivne servise
    public function scopeAktivni($query)
    {
        return $query->where('status', '!=', 'zavrseno')
            ->where('status', '!=', 'otkazano');
    }

    // Scope za servise na čekanju
    public function scopeNaCekanju($query)
    {
        return $query->where('status', 'zakazano');
    }

    // Accessor za formatirani termin
    public function getTerminFormatiranAttribute()
    {
        return $this->termin ? $this->termin->format('d.m.Y H:i') : 'Nije uneto';
    }

    // Accessor za status boju
    public function getStatusBojaAttribute()
    {
        $boje = [
            'zakazano' => 'bg-yellow-100 text-yellow-800',
            'u dijagnostici' => 'bg-blue-100 text-blue-800',
            'u popravci' => 'bg-orange-100 text-orange-800',
            'zavrseno' => 'bg-green-100 text-green-800',
            'otkazano' => 'bg-red-100 text-red-800',
        ];

        return $boje[$this->status] ?? 'bg-gray-100 text-gray-800';
    }

    // Mutator za telefon
    public function setTelefonAttribute($value)
    {
        $this->attributes['telefon'] = preg_replace('/[^0-9+]/', '', $value);
    }

    // Provera da li je servis aktivan
    public function getJeAktivanAttribute()
    {
        return !in_array($this->status, ['zavrseno', 'otkazano']);
    }
}