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
        Schema::create('patients', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('pid')->unique();
            $table->date('date_registered')->nullable();
            $table->string('name_first');
            $table->string('name_last');
            $table->string('name_middle')->nullable();
            $table->string('name_suffix')->nullable();
            $table->string('sex');
            $table->string('phone_number')->nullable();
            $table->string('blood_group')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->integer('age')->nullable();
            $table->string('civil_status')->nullable();
            $table->string('place_of_birth')->nullable();
            $table->string('religion')->nullable();
            $table->string('ethnicity')->nullable();
            $table->string('address_street')->nullable();
            $table->string('address_brgy')->nullable();
            $table->string('address_city')->nullable();
            $table->string('address_province')->nullable();
            $table->string('address_country')->nullable();
            $table->string('address_zipcode')->nullable();
            $table->string('patient_mother_name')->nullable();
            $table->string('patient_father_name')->nullable();
            $table->string('patient_guardian_name')->nullable();
            $table->string('patient_guardian_relationship')->nullable();
            $table->string('patient_spouse_name')->nullable();
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patients');
    }
};
