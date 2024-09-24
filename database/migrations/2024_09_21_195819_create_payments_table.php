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
            $table->id();
            $table->foreignId('customers_account_id')->constrained('customers_account')->onDelete('cascade');
            $table->date('date_payment');
            $table->decimal('amount_pay', 8, 2);
            $table->date('next_payment_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations_
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
