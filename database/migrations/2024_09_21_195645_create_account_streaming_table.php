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
        Schema::create('account_streaming', function (Blueprint $table) {
            $table->id();
            $table->string('name_service'); 
            $table->string('type_account');
            $table->date('date_payment');
            $table->date('prices');
            $table->date('type_payment')->nullable();
            $table->date('bank_name')->nullable();
            $table->date('card_number')->nullable();
            $table->date('date_create')->nullable();
            $table->date('date_expire')->nullable();
            $table->string('status')->default('active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('account_streaming');
    }
};
