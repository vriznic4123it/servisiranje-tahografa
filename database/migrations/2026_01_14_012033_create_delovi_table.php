<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('delovi', function (Blueprint $table) {
            $table->id();
            $table->string('naziv');
            $table->string('kod')->unique();
            $table->integer('kolicina');
            $table->string('dobavljac')->nullable();
            $table->decimal('cena', 10, 2)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('delovi');
    }
};