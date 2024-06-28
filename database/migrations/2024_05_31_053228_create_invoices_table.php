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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->integer('client_id')->nullable();
            $table->integer('contract_id')->nullable();
            $table->integer('user_id')->nullable();
            $table->string('customer_name')->nullable();
            $table->string('customer_number')->nullable();
            $table->string('reference')->nullable();
            $table->string('subject')->nullable();
            $table->string('priority')->nullable();
            $table->string('invoiced_by')->nullable();
            $table->string('due_on')->nullable();
            $table->string('due_amount')->nullable();
            $table->string('discount_type')->nullable();
            $table->double('discount', 15, 2)->nullable();
            $table->double('vat', 15, 2)->nullable();
            $table->integer('grand_total')->nullable();
            $table->string('status')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
