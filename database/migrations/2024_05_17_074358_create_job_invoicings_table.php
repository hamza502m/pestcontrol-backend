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
        Schema::create('job_invoicings', function (Blueprint $table) {
            $table->id();
            $table->integer('job_id')->nullable();
            $table->integer('client_id')->nullable();
            $table->string('billing_frequency')->nullable();
            $table->string('billing_method')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_invoicings');
    }
};
