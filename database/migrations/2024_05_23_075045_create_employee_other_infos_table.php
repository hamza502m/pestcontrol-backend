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
        Schema::create('employee_other_infos', function (Blueprint $table) {
            $table->id();
            $table->integer('emp_id')->nullable();
            $table->string('relative_name')->nullable();
            $table->string('relation')->nullable();
            $table->string('emergency_contact')->nullable();
            $table->string('basic_salary')->nullable();
            $table->string('allowance')->nullable();
            $table->string('other')->nullable();
            $table->string('total_salary')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_other_infos');
    }
};
