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
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->string('voucher');          // voucher code
            $table->integer('minutes');         // minutes granted
            $table->integer('coins');           // coins inserted
            $table->string('ip')->nullable();   // client IP
            $table->string('mac')->nullable();  // client MAC
            $table->string('device_id');        // vending device ID
            $table->unsignedBigInteger('user_id'); // owner/admin
            $table->timestamps();

            // optional: foreign key if you have users table
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};
