<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('servisi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vozilo_id')->nullable()->constrained('vozila')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('serviser_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('vozilo')->nullable();
            $table->enum('tip_tahografa', ['analogni', 'digitalni']);
            $table->text('opis_problema')->nullable();
            $table->string('telefon', 20)->nullable();
            $table->dateTime('termin');
            $table->enum('status', ['zakazano', 'u_dijagnostici', 'u_popravci', 'zavrseno', 'otkazano'])->default('zakazano');
            $table->string('broj_plombe')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('servisi');
    }
};