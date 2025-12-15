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
            $table->string('approval_year')->nullable();
            $table->string('evidence_level')->nullable();
            $table->string('mesh_term')->nullable();
            $table->string('focus')->nullable(); // foco_conceptual
            $table->string('symptoms_context')->nullable(); // foco_en_contexto_sintomas
            $table->string('care_subject')->nullable(); // sujeto_del_cuidado
            $table->string('judgment')->nullable(); // juicio
            $table->string('anatomical_location')->nullable(); // localizacion_anatomica
            $table->string('age_limit_lower')->nullable();
            $table->string('age_limit_upper')->nullable();
            $table->string('clinical_course')->nullable();
            $table->string('diagnosis_status')->nullable(); // estado_del_diagnostico
            $table->string('situational_limitation')->nullable();
            $table->json('risk_factors')->nullable();
            $table->json('at_risk_population')->nullable();
            $table->json('associated_conditions')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('nandas', function (Blueprint $table) {
            $table->dropColumn([
                'approval_year',
                'evidence_level',
                'mesh_term',
                'focus',
                'symptoms_context',
                'care_subject',
                'judgment',
                'anatomical_location',
                'age_limit_lower',
                'age_limit_upper',
                'clinical_course',
                'diagnosis_status',
                'situational_limitation',
                'risk_factors',
                'at_risk_population',
                'associated_conditions',
            ]);
        });
    }
};
