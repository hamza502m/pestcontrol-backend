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
        Schema::create('quotes', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->integer('client_id');
            $table->string('contract_reference');
            $table->string('job_type');
            $table->string('quote_title');
            $table->string('date');
            $table->string('due_date');
            $table->string('trn')->nullable();
            $table->string('firm')->nullable();
            $table->string('subject');
            $table->string('food_watch_account');
            $table->json('service_ids');
            $table->json('tm_ids');
            $table->string('description');
            $table->string('duration');
            $table->string('discount_type');
            $table->double('discount', 15, 2);
            $table->double('vat', 15, 2);
            $table->double('grand_total', 15, 2);
            $table->string('follow_up_date')->nullable();
            $table->string('remarks')->nullable();
            $table->integer('no_of_invoive')->nullable();
            $table->integer('invoice_type')->comment('1: No of Installments 2: Service Based 3: Monthly 4: 3 Months 5: 6 Months 6: 1 Year');
            $table->integer('status')->default('0');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quotes');
    }
};
