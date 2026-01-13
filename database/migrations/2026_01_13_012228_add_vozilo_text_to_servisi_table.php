<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('servisi', function (Blueprint $table) {
            $table->string('vozilo')->nullable()->after('vozilo_id');
            // Ako želiš, možeš drop-ovati vozilo_id
            // $table->dropColumn('vozilo_id');
        });
    }

    public function down(): void
    {
        Schema::table('servisi', function (Blueprint $table) {
            $table->dropColumn('vozilo');
            // Ako si drop-ovao vozilo_id u up(), ovde ga vrati
            // $table->foreignId('vozilo_id')->constrained()->onDelete('cascade');
        });
    }
};
