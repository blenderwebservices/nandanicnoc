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
        Schema::create('nanda_nic', function (Blueprint $table) {
            $table->id();
            $table->foreignId('nanda_id')->constrained('nandas')->cascadeOnDelete();
            $table->foreignId('nic_id')->constrained('nics')->cascadeOnDelete();
            $table->string('type')->default('suggested'); // e.g., major, suggested, optional
            $table->timestamps();
        });

        Schema::create('nanda_noc', function (Blueprint $table) {
            $table->id();
            $table->foreignId('nanda_id')->constrained('nandas')->cascadeOnDelete();
            $table->foreignId('noc_id')->constrained('nocs')->cascadeOnDelete();
            $table->string('type')->default('suggested'); // e.g., major, suggested, optional
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nanda_noc');
        Schema::dropIfExists('nanda_nic');
    }
};
