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
        Schema::create('doctor_surgery', function (Blueprint $table) {
            $table->id();
            // Foreign keys
            $table->foreignId('doctor_id')
                ->constrained('doctors')
                ->cascadeOnDelete();

            $table->foreignId('surgery_id')
                ->constrained('surgeries')
                ->cascadeOnDelete();

            $table->bigInteger('doctor_role_id');
            $table->bigInteger('invoice_id')->nullable();
            $table->bigInteger('amount');
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('doctor_surgery');
    }
};
