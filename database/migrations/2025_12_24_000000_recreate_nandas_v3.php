<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::dropIfExists('nandas');

        Schema::create('nandas', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique()->index();
            $table->foreignId('class_id')->constrained('nanda_classes')->cascadeOnDelete();

            // Labels
            $table->string('label')->nullable(); // English Name (constructed or explicit)
            $table->string('label_es')->nullable(); // Spanish Name

            // Definitions
            $table->text('description')->nullable(); // English Definition
            $table->text('description_es')->nullable(); // Spanish Definition

            // Metadata
            $table->string('approval_year')->nullable();
            $table->string('year_revised')->nullable();
            $table->string('evidence_level')->nullable();

            // Taxonomy / Classifications
            $table->string('mesh_term')->nullable();

            // English / Spanish Pairs for structured fields
            $table->string('focus')->nullable();
            $table->string('focus_es')->nullable();

            $table->string('symptoms_context')->nullable();
            // $table->string('symptoms_context_es')->nullable(); // If needed later

            $table->string('care_subject')->nullable();
            // $table->string('care_subject_es')->nullable();

            $table->string('judgment')->nullable();
            $table->string('judgment_es')->nullable();

            $table->string('diagnosis_status')->nullable();
            $table->string('diagnosis_status_es')->nullable();

            $table->string('anatomical_location')->nullable();
            $table->string('age_limit_lower')->nullable();
            $table->string('age_limit_upper')->nullable();
            $table->string('clinical_course')->nullable();
            $table->string('situational_limitation')->nullable();

            // JSON Lists
            $table->json('risk_factors')->nullable(); // English
            $table->json('risk_factors_es')->nullable(); // Spanish

            $table->json('at_risk_population')->nullable(); // English
            $table->json('at_risk_population_es')->nullable(); // Spanish

            $table->json('associated_conditions')->nullable(); // English
            $table->json('associated_conditions_es')->nullable(); // Spanish

            $table->json('defining_characteristics')->nullable(); // English
            $table->json('defining_characteristics_es')->nullable(); // Spanish

            $table->json('related_factors')->nullable(); // English
            $table->json('related_factors_es')->nullable(); // Spanish

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nandas');
    }
};
