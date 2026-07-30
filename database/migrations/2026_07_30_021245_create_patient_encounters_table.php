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
        Schema::create('patient_encounters', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('case_nr')->unique();
            $table->date('encounter_date')->nullable();
            $table->string('patient_type')->nullable();
            $table->string('official_receipt_nr')->nullable();
            $table->string('admitting_diagnosis')->nullable();
            $table->string('chief_complaint')->nullable();
            $table->boolean('is_confidential')->default(false);
            $table->dateTime('discharge_datetime')->nullable();
            $table->boolean('iswaitlisted')->default(false);
            $table->boolean('is_still_in')->default(true);
            $table->date('consultation_date')->nullable();
            $table->time('consultation_time')->nullable();
            $table->time('time_of_arrival')->nullable();

            $table->foreignId('nurse_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('doctor_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('patient_id')->nullable()->constrained('patients')->onDelete('set null');
            $table->foreignId('ward_id')->nullable()->constrained('wards')->onDelete('set null');
            

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patient_encounters');
    }
};
