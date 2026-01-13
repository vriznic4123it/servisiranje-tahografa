<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('servisi', function (Blueprint $table) {
            $table->string('opis_problema')->nullable();
            $table->string('telefon', 20)->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('servisi', function (Blueprint $table) {
            $table->dropColumn(['opis_problema', 'telefon']);
        });
    }
};

