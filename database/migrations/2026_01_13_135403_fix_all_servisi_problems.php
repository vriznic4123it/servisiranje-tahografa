<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Prvo proverimo da li imamo sve potrebne kolone
        Schema::table('servisi', function (Blueprint $table) {
            // 1. Proveri da li postoji serviser_id, ako ne postoji - dodaj
            if (!Schema::hasColumn('servisi', 'serviser_id')) {
                $table->foreignId('serviser_id')->nullable()->constrained('users')->onDelete('set null');
            }
            
            // 2. Ako postoji klijent_id, preimenuj ga nazad u user_id (radi kompatibilnosti)
            if (Schema::hasColumn('servisi', 'klijent_id')) {
                $table->renameColumn('klijent_id', 'user_id');
            }
            
            // 3. Proveri dodatna polja
            if (!Schema::hasColumn('servisi', 'vozilo')) {
                $table->string('vozilo')->nullable()->after('vozilo_id');
            }
            
            if (!Schema::hasColumn('servisi', 'opis_problema')) {
                $table->text('opis_problema')->nullable();
            }
            
            if (!Schema::hasColumn('servisi', 'telefon')) {
                $table->string('telefon', 20)->nullable();
            }
            
            // 4. Ažuriraj status vrednosti ako su stari
            // (ovo ćemo uraditi nakon migracije)
        });
    }

    public function down(): void
    {
        // Vraćanje nazad je komplikovano, pa možemo ostaviti prazno
        // ili uraditi rollback ručno ako bude potrebno
    }
};