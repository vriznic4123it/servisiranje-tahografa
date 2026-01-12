<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('servisi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vozilo_id')->constrained('vozila')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Serviser ili klijent
            $table->enum('tip_tahografa', ['analogni', 'digitalni']);
            $table->dateTime('termin');
            $table->enum('status', ['zakazano', 'u_dijagnostici', 'u_popravci', 'zavrseno'])->default('zakazano');
            $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('servisi');
    }
};
