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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->nullable();
            $table->string('eid_no')->nullable();
            $table->string('eid_start')->nullable();
            $table->string('eid_expiry')->nullable();
            $table->string('profession')->nullable();
            $table->string('passport_no')->nullable();
            $table->string('passport_start')->nullable();
            $table->string('passport_expiry')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
