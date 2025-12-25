<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Domain;
use App\Models\NandaClass;
use App\Models\Nanda;

class NandaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Truncate tables to ensure clean slate
        \Illuminate\Support\Facades\Schema::disableForeignKeyConstraints();
        DB::table('nandas')->truncate();
        DB::table('nanda_classes')->truncate();
        DB::table('domains')->truncate();
        // nanda_search_index is handled by model events usually, but good to clear
        DB::table('nanda_search_index')->truncate();
        \Illuminate\Support\Facades\Schema::enableForeignKeyConstraints();

        // 2. Load Translations
        $labelTranslations = require __DIR__ . '/NandaTranslations.php';
        $propertyTranslations = require __DIR__ . '/NandaPropertyTranslations.php';
        $definitionTranslations = require __DIR__ . '/NandaDefinitionTranslations.php';

        // Helper to translate array items
        $translateArray = function ($items) use ($propertyTranslations) {
            if (!is_array($items))
                return [];
            return array_map(function ($item) use ($propertyTranslations) {
                // Try to find translation, otherwise keep original
                return $propertyTranslations[$item] ?? $item;
            }, $items);
        };

        // Helper to translate single string property
        $translateString = function ($item) use ($propertyTranslations) {
            if (!$item)
                return null;
            return $propertyTranslations[$item] ?? null;
        };

        // 3. Process Files
        // Get all nanda_data_domain*_v3 files 
        $files = glob(__DIR__ . '/nanda_data_domain*_v3*');
        sort($files);

        foreach ($files as $file) {
            if (is_dir($file))
                continue;

            // Only process V3 files
            if (!str_contains($file, '_v3'))
                continue;

            $json = file_get_contents($file);
            if (empty($json))
                continue;

            $decoded = json_decode($json, true);
            if (!$decoded || !isset($decoded['dominio']))
                continue;

            // Spanish keys in JSON structure: dominio, clases, diagnosticos
            $domainData = $decoded['dominio'];

            // Domain
            $domain = Domain::firstOrCreate(
                ['code' => (string) $domainData['numero']],
                [
                    'name' => $domainData['nombre'], // English Name
                    'name_es' => $labelTranslations[$domainData['nombre']] ?? null,
                ]
            );

            foreach ($domainData['clases'] as $classData) {
                // Class
                $class = NandaClass::firstOrCreate(
                    [
                        'domain_id' => $domain->id,
                        'code' => (string) $classData['numero']
                    ],
                    [
                        'name' => $classData['nombre'], // English Name
                        'name_es' => $labelTranslations[$classData['nombre']] ?? null,
                        'definition' => $classData['definicion'] ?? '',
                        'definition_es' => null, // No source for class def translation currently
                    ]
                );

                $diagnostics = $classData['diagnosticos'] ?? [];

                foreach ($diagnostics as $diagData) {
                    $code = $diagData['codigo'] ?? null;
                    if (!$code)
                        continue;

                    // Translations
                    $labelEs = $labelTranslations[$code] ?? $labelTranslations[$diagData['nombre']] ?? null;
                    $defEs = $definitionTranslations[$code] ?? null;

                    // Lists
                    $riskFactors = $diagData['risk_factors'] ?? [];
                    $atRiskPop = $diagData['at_risk_population'] ?? [];
                    $assocCond = $diagData['associated_conditions'] ?? [];
                    $defChars = $diagData['defining_characteristics'] ?? [];
                    $relFactors = $diagData['related_factors'] ?? [];

                    Nanda::updateOrCreate(
                        ['code' => $code],
                        [
                            'class_id' => $class->id,
                            'label' => $diagData['nombre'] ?? "Diagnosis $code",
                            'label_es' => $labelEs,
                            'description' => $diagData['definicion'] ?? '',
                            'description_es' => $defEs,

                            'approval_year' => $diagData['aprobado'] ?? null,
                            'year_revised' => $diagData['revisado'] ?? null,
                            'evidence_level' => $diagData['nivel_evidencia'] ?? null,
                            'mesh_term' => $diagData['mesh'] ?? null,

                            'focus' => $diagData['concept_focus'] ?? null,
                            'focus_es' => $translateString($diagData['concept_focus'] ?? null),

                            'symptoms_context' => $diagData['context_symptom_focus'] ?? null,
                            'care_subject' => $diagData['subject_of_care'] ?? null,

                            'judgment' => $diagData['judgment'] ?? null,
                            'judgment_es' => $translateString($diagData['judgment'] ?? null),

                            'anatomical_location' => $diagData['anatomical_site'] ?? null,
                            'age_limit_lower' => $diagData['age_lower_limit'] ?? null,
                            'age_limit_upper' => $diagData['age_upper_limit'] ?? null,
                            'clinical_course' => $diagData['clinical_course'] ?? null,

                            'diagnosis_status' => $diagData['status_of_the_diagnosis'] ?? null,
                            'diagnosis_status_es' => $translateString($diagData['status_of_the_diagnosis'] ?? null),

                            'situational_limitation' => $diagData['situational_constraint'] ?? null,

                            'risk_factors' => $riskFactors,
                            'risk_factors_es' => $translateArray($riskFactors),

                            'at_risk_population' => $atRiskPop,
                            'at_risk_population_es' => $translateArray($atRiskPop),

                            'associated_conditions' => $assocCond,
                            'associated_conditions_es' => $translateArray($assocCond),

                            'defining_characteristics' => $defChars,
                            'defining_characteristics_es' => $translateArray($defChars),

                            'related_factors' => $relFactors,
                            'related_factors_es' => $translateArray($relFactors),
                        ]
                    );
                }
            }
        }
    }
}
