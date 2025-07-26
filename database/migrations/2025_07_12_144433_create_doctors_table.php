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
        Schema::create('doctors', function (Blueprint $table) {
            $table->id()->autoIncrement();
            $table->string('name', 100)->unique();
            $table->foreignId('speciality_id')->constrained('specialities')->cascadeOnDelete();
            $table->string('national_code', 20)->nullable();
            $table->string('medical_number', 191)->nullable();
            $table->string('phone', 20)->unique();
            $table->string('password', 191);
            $table->boolean('status')->default(true);
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('doctors');
    }
};
