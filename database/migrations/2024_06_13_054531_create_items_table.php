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
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->string('item_name')->nullable();
            $table->string('batch_number')->nullable();
            $table->integer('brand_id')->nullable();
            $table->string('mfg')->nullable();
            $table->string('ed')->nullable();
            $table->integer('item_type')->nullable();
            $table->integer('unit')->nullable();
            $table->string('active_ingredients')->nullable();
            $table->string('others_ingredients')->nullable();
            $table->json('service_ids')->nullable();
            $table->string('moccae_approval')->nullable();
            $table->string('moccae_strat_date')->nullable();
            $table->string('moccae_exp')->nullable();
            $table->string('vat')->nullable();
            $table->integer('supplier_id')->nullable();
            $table->string('total_qty')->nullable();
            $table->string('per_item_qty')->nullable();
            $table->string('per_item_price')->nullable();
            $table->string('price')->nullable();
            $table->string('descriptions')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
