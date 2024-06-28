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
        Schema::create('contracts', function (Blueprint $table) {
            $table->id();
            $table->string('quote_id');
            $table->string('client_id');
            $table->string('user_id');
            $table->string('contracted_by');
            $table->string('firm');
            $table->string('contract_reference');
            $table->string('food_watch_account');
            $table->string('job_type');
            $table->string('contract_title');
            $table->string('contract_subject');
            $table->string('date');
            $table->string('due_date');
            $table->json('service_ids');
            $table->string('trn');
            $table->json('tm_ids');
            $table->string('discount_type');
            $table->double('discount', 15, 2);
            $table->double('vat', 15, 2);
            $table->double('grand_total', 15, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contracts');
    }
};
