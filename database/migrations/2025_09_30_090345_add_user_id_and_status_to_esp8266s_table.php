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
        Schema::table('esp8266s', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->nullable()->after('id');
            $table->string('device_status')->nullable();
            $table->timestamp('last_seen')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('esp8266s', function (Blueprint $table) {
            $table->dropColumn('user_id');
            $table->dropColumn('device_status');
            $table->dropColumn('last_seen');
        });
    }
};
