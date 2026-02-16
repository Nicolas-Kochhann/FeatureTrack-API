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
        Schema::create('invites', function (Blueprint $table) {
            $table->unsignedBigInteger('sender_id');
            $table->unsignedBigInteger('receiver_id');
            $table->unsignedBigInteger('project_id');
            $table->enum('status', ['rejected', 'pending', 'accepted']);
            $table->timestamps();

            $table->foreign('sender_id')->references('id')->on('users')->cascadeOnDelete();
            $table->foreign('receiver_id')->references('id')->on('users')->cascadeOnDelete();
            $table->foreign('project_id')->references('id')->on('projects')->cascadeOnDelete();

            $table->primary(['sender_id', 'receiver_id', 'project_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invites');
    }
};
