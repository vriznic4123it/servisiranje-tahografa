<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up()
{
    Schema::table('users', function (Blueprint $table) {
        // Dodajemo nove kolone, ali prvo proveravamo da li već postoje
        if (!Schema::hasColumn('users', 'first_name')) {
            $table->string('first_name')->after('name');
        }
        if (!Schema::hasColumn('users', 'last_name')) {
            $table->string('last_name')->after('first_name');
        }
        if (!Schema::hasColumn('users', 'phone')) {
            $table->string('phone')->after('email')->nullable();
        }
        if (!Schema::hasColumn('users', 'role')) {
            $table->string('role')->default('client')->after('phone');
        }
    });
}

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            // Uklanjamo kolone koje smo dodali
            $table->dropColumn(['first_name', 'last_name', 'phone', 'role']);
        });
    }
};
