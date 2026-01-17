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
        Schema::create('nics', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('label');
            $table->string('label_es')->nullable();
            $table->text('definition')->nullable();
            $table->text('definition_es')->nullable();
            $table->json('activities')->nullable(); // List of activities in English
            $table->json('activities_es')->nullable(); // List of activities in Spanish
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nics');
    }
};
