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
        Schema::create('service_reports', function (Blueprint $table) {
            $table->id();
            $table->string('client_name')->nullable();
            $table->string('contact_number')->nullable();
            $table->string('date')->nullable();
            $table->json('type_of_visit')->nullable();
            $table->json('service_ids');
            $table->json('tm_ids');
            $table->string('status')->nullable();
            $table->string('recommandation_remarks')->nullable();
            $table->string('extra_total')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_reports');
    }
};
