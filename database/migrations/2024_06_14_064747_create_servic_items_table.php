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
        Schema::create('servic_items', function (Blueprint $table) {
            $table->id();
            $table->integer('service_id')->nullable();
            $table->integer('item_id')->nullable();
            $table->string('item_name')->nullable();
            $table->string('high')->nullable();
            $table->string('medium')->nullable();
            $table->string('low')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('servic_items');
    }
};
