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
        Schema::create('employee_insurances', function (Blueprint $table) {
            $table->id();
            $table->integer('emp_id')->nullable();
            $table->integer('hi_status')->nullable();
            $table->string('hi_start')->nullable();
            $table->string('hi_expiry')->nullable();
            $table->string('ui_status')->nullable();
            $table->string('ui_start')->nullable();
            $table->string('ui_expiry')->nullable();
            $table->string('dm_card')->nullable();
            $table->string('dm_start')->nullable();
            $table->string('dm_expiry')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_insurances');
    }
};
