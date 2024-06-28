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
        Schema::create('client_jobs', function (Blueprint $table) {
            $table->id();
            $table->string('customer_id')->nullable();
            $table->integer('contract_id')->nullable();
            $table->integer('quote_id')->nullable();
            $table->string('job_title')->nullable();
            $table->string('contact_person_name')->nullable();
            $table->string('contact_person_number')->nullable();
            $table->string('assign_by')->nullable();
            $table->string('date')->nullable();
            $table->string('time')->nullable();
            $table->json('services_ids')->nullable();
            $table->string('priority')->nullable();
            $table->string('description')->nullable();
            $table->string('duration')->nullable();
            $table->string('job_type')->nullable();
            $table->string('status')->comment('1: Open 2: Assigned 3: Pending 4: completed 5: Cancelled 6: Reschedual');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('client_jobs');
    }
};
