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
        Schema::create('surgeries', function (Blueprint $table) {
            $table->id()->autoIncrement();
            // patient info
            $table->string('patient_name', 100);
            $table->string('patient_national_code', 20);
            // insurance info
            $table->foreignId('basic_insurance_id')
                ->nullable()
                ->constrained('insurances')
                ->comment('Reference to basic insurance');

            $table->foreignId('supp_insurance_id')
                ->nullable()
                ->constrained('insurances')
                ->comment('Reference to supplementary insurance');
            // Surgery details
            $table->integer('document_number')->unique();
            $table->text('description')->nullable();
            // Date fields
            $table->date('surgeried_at')->comment('Date when surgery was performed');
            $table->date('released_at')->nullable()->comment('Date when patient was released');
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('surgeries');
    }
};
