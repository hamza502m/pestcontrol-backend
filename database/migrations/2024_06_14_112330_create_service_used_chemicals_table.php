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
        Schema::create('service_used_chemicals', function (Blueprint $table) {
            $table->id();
            $table->integer('report_id')->nullable();
            $table->string('item_id')->nullable();
            $table->string('item_name')->nullable();
            $table->string('infestation_level')->nullable();
            $table->string('qty')->nullable();
            $table->string('type')->nullable();
            $table->string('price')->nullable();
            $table->integer('service_id');
            $table->integer('job_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_used_chemicals');
    }
};
