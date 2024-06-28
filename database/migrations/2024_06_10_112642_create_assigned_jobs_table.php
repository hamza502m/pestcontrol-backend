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
        Schema::create('assigned_jobs', function (Blueprint $table) {
            $table->id();
            $table->integer('job_id')->nullable();
            $table->integer('captain_id')->nullable();
            $table->json('team')->nullable();
            $table->string('job_instruction')->nullable();
            $table->integer('assigned_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assigned_jobs');
    }
};
