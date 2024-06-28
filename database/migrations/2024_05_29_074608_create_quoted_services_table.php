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
        Schema::create('quoted_services', function (Blueprint $table) {
            $table->id();
            $table->integer('quotedServices_id');
            $table->integer('service_id');
            $table->string('service_name');
            $table->string('no_of_months')->nullable();
            $table->double('rate', 15, 2);
            $table->double('sub_total', 15, 2);
            $table->integer('job_type')->nullable();
            $table->integer('month_gap')->nullable();
            $table->string('quotedServices_type')->nullable();
            $table->string('date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quoted_services');
    }
};
