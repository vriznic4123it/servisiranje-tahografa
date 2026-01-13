<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Prvo proverimo da li kolona postoji
        if (Schema::hasColumn('servisi', 'vozilo_id')) {
            Schema::table('servisi', function (Blueprint $table) {
                // Ukloni foreign key
                $table->dropForeign(['vozilo_id']);
                // Postavi kolonu kao nullable
                $table->foreignId('vozilo_id')->nullable()->change();
                // Vrati foreign key
                $table->foreign('vozilo_id')->references('id')->on('vozila')->onDelete('cascade');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('servisi', 'vozilo_id')) {
            Schema::table('servisi', function (Blueprint $table) {
                $table->dropForeign(['vozilo_id']);
                $table->foreignId('vozilo_id')->nullable(false)->change();
                $table->foreign('vozilo_id')->references('id')->on('vozila')->onDelete('cascade');
            });
        }
    }
};