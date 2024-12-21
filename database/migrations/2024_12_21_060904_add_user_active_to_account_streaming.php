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
            $table->unsignedInteger('user_active')->default(0);
            $table->unsignedInteger('user_max')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('account_streaming', function (Blueprint $table) {
            $table->dropColumn('user_active');
        });
    }
};
