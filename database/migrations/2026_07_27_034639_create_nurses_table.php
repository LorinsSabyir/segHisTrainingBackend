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
        Schema::create('nurses', function (Blueprint $table) {
            $table->id('uid');
            $table->timestamps();
            $table->string('case_number');
            $table->date('consultation_date');
            $table->time('consultation_time');
            $table->string('family_name');
            $table->string('given_name');
            $table->string('suffix')->nullable();
            $table->date('date_of_birth');
            $table->string('sex');
            $table->string('blood_group');
            $table->time('time_of_arrival');


        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nurses');
    }
};
