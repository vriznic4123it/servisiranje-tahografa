<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('diagnostike', function (Blueprint $table) {
            $table->id();
            $table->foreignId('servis_id')->constrained('servisi')->onDelete('cascade');
            $table->foreignId('serviser_id')->nullable()->constrained('users')->onDelete('set null');
            $table->text('opis_problema')->nullable();
            $table->text('rezultati_dijagnostike')->nullable();
            $table->text('preporuceni_radovi')->nullable();
            $table->text('izvrseni_radovi')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('diagnostike');
    }
};