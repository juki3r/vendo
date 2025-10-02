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
        Schema::create('active_clients', function (Blueprint $table) {
            $table->id();
            $table->string('device_id');
            $table->string('user_id');
            $table->string('username');
            $table->string('ip');
            $table->string('mac');
            $table->string('uptime');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('active_clients');
    }
};
