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
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('title')->nullable();
            $table->string('message')->nullable();
            $table->string('category')->nullable();
            $table->string('priority')->nullable();
            $table->string('action_url')->nullable();
            $table->string('action_type')->nullable();
            $table->boolean('is_read')->nullable()->default(false);
            $table->date('read_at')->nullable();
            $table->date('expires_at')->nullable();

            $table->bigInteger('action_id')->unsigned()->nullable();
            $table->foreignId('sender_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('receiver_id')->nullable()->constrained('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
