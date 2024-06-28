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
        Schema::create('client_job_details', function (Blueprint $table) {
            $table->id();
            $table->integer('job_id')->nullable();
            $table->integer('client_id')->nullable();
            $table->string('job_type')->nullable();
            $table->string('job_nature')->nullable();
            $table->json('dates')->nullable('time_duration_to');
            $table->string('time')->nullable();
            $table->string('job_priority')->nullable();
            $table->string('job_instructions')->nullable();
            $table->string('working_remainder')->nullable();
            $table->string('job_details_description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('client_job_details');
    }
};
