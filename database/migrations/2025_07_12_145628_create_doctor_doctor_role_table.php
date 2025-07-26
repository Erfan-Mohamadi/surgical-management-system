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
        // First check if the table exists
        if (! Schema::hasTable('doctor_doctor_role')) {
            Schema::create('doctor_doctor_role', function (Blueprint $table) {
                $table->id();

                // Use unsignedBigInteger instead of foreignId first
                $table->unsignedBigInteger('doctor_id');
                $table->unsignedBigInteger('role_id');

                $table->timestamps();

                // Add foreign key constraints separately after table creation
                $table->foreign('doctor_id')
                    ->references('id')
                    ->on('doctors')
                    ->onDelete('cascade');

                $table->foreign('role_id')
                    ->references('id')
                    ->on('doctor_roles') // Changed from 'roles' to match your table name
                    ->onDelete('cascade');

                // Composite unique key
                $table->unique(['doctor_id', 'role_id']);

            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('doctor_doctor_role', function (Blueprint $table) {
            // Drop foreign keys first
            $table->dropForeign(['doctor_id']);
            $table->dropForeign(['role_id']);
        });

        Schema::dropIfExists('doctor_doctor_role');
    }
};
