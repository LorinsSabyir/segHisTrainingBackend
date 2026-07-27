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
            $table->string('phn')->unique();
            $table->string('case_number');
            $table->date('consultation_date')->nullable();
            $table->time('consultation_time')->nullable();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('suffix')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('sex');
            $table->string('blood_group')->nullable();
            $table->time('time_of_arrival')->nullable();
            $table->string('phone_number')->nullable();
            $table->string('address_street')->nullable();
            $table->string('address_brgy')->nullable();
            $table->string('address_city')->nullable();
            $table->string('address_province')->nullable();
            $table->foreignId('nurse_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('doctor_id')->nullable()->constrained('users')->onDelete('set null');
            
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
