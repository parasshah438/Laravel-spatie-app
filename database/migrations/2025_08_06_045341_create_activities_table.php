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
        Schema::create('activities', function (Blueprint $table) {
            $table->id();
            $table->string('subject_type'); // User or Admin model
            $table->unsignedBigInteger('subject_id'); // User or Admin ID
            $table->string('causer_type')->nullable(); // Who caused the activity
            $table->unsignedBigInteger('causer_id')->nullable(); // ID of who caused it
            $table->string('description'); // Activity description
            $table->json('properties')->nullable(); // Additional data
            $table->timestamps();
            
            $table->index(['subject_type', 'subject_id']);
            $table->index(['causer_type', 'causer_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activities');
    }
};
