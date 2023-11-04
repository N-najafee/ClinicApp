<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('advisor_patient', function (Blueprint $table) {
            $table->id();
            $table->foreignId('advisor_id');
            $table->foreign('advisor_id')->references('id')->on('advisors');
            $table->foreignId('patient_id');
            $table->foreign('patient_id')->references('id')->on('patients');
            $table->primary(['advisor_id', 'patient_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('advisor_patient');
    }
};
