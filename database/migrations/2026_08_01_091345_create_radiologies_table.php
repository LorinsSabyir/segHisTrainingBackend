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
        Schema::create('radiologies', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('code')->unique();
            $table->string('test');
            $table->string('group_code');
            $table->string('group');
            $table->string('section_code');
            $table->string('section');
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('radiologies');
    }
};
