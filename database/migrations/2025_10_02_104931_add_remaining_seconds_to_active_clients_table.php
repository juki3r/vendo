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
        Schema::table('active_clients', function (Blueprint $table) {
            $table->integer('remaining_seconds')->default(0)->after('uptime');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('active_clients', function (Blueprint $table) {
            $table->dropColumn('remaining_seconds');
        });
    }
};
