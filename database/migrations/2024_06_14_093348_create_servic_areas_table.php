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
        Schema::create('servic_areas', function (Blueprint $table) {
            $table->id();
            $table->integer('report_id')->nullable();
            $table->string('inspected_area')->nullable();
            $table->string('infestation_level')->nullable();
            $table->string('manifested_area')->nullable();
            $table->string('report_follow_up')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('servic_areas');
    }
};
