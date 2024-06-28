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
        Schema::create('quotes_frequencies', function (Blueprint $table) {
            $table->id();
            $table->integer('quote_id')->nullable();
            $table->integer('service_id')->nullable();
            $table->integer('turn')->nullable();
            $table->string('frequencey')->nullable();
            $table->string('date')->nullable();
            $table->string('day')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quotes_frequencies');
    }
};
