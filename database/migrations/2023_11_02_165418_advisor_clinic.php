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
        Schema::create('advisor_clinic', function (Blueprint $table) {
            $table->foreignId('clinic_id');
            $table->foreign('clinic_id')->references('id')->on('clinics');
            $table->foreignId('advisor_id');
            $table->foreign('advisor_id')->references('id')->on('advisors');
            $table->primary(['clinic_id','advisor_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('advisor_clinic');
    }
};
