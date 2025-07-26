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
        Schema::create('payments', function (Blueprint $table) {
            $table->id()->autoIncrement();

            $table->foreignId('invoice_id')
                ->constrained('invoices')
                ->cascadeOnDelete();

            $table->bigInteger('amount');

            $table->enum('pay_type', ['cash', 'cheque'])
                ->comment('Payment method');

            $table->date('due_date')
                ->nullable()
                ->comment('Due date for cheque payments');

            $table->string('receipt', 191)
                ->nullable()
                ->comment('Receipt image path');

            $table->text('description')->nullable();

            $table->boolean('status')->default(false);
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
