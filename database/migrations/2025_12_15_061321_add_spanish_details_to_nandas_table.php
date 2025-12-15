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
        Schema::table('nandas', function (Blueprint $table) {
            $table->string('focus_es')->nullable();
            $table->string('judgment_es')->nullable();
            $table->string('diagnosis_status_es')->nullable();
            $table->json('risk_factors_es')->nullable();
            $table->json('at_risk_population_es')->nullable();
            $table->json('associated_conditions_es')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('nandas', function (Blueprint $table) {
            $table->dropColumn([
                'focus_es',
                'judgment_es',
                'diagnosis_status_es',
                'risk_factors_es',
                'at_risk_population_es',
                'associated_conditions_es',
            ]);
        });
    }
};
