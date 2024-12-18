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
        Schema::table('account_streaming', function (Blueprint $table) {
            $table->string('prices')->change();
            $table->string('type_payment')->change();
            $table->string('bank_name')->change()->nullable();
            $table->string('card_number')->change()->nullable();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('account_streaming', function (Blueprint $table) {

        });
    }
};
