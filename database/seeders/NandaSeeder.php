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
        $additionalTranslations = require __DIR__ . '/NandaAdditionalTranslations.php';

        // Merge additional translations into relevant buckets
        $propertyTranslations = array_merge($propertyTranslations, $additionalTranslations);
        $definitionTranslations = array_merge($definitionTranslations, $additionalTranslations);
        $labelTranslations = array_merge($labelTranslations, $additionalTranslations);


        // Helper to translate array items
        $translateArray = function ($items) use ($propertyTranslations) {
            if (!is_array($items))
                return [];
            $lowerProps = array_change_key_case($propertyTranslations, CASE_LOWER);
            return array_map(function ($item) use ($propertyTranslations, $lowerProps) {
                if (isset($propertyTranslations[$item]))
                    return $propertyTranslations[$item];
                return $lowerProps[strtolower($item)] ?? $item;
            }, $items);
        };

        // Helper to translate single string property
        $translateString = function ($item) use ($propertyTranslations) {
            if (!$item)
                return null;
            if (isset($propertyTranslations[$item]))
                return $propertyTranslations[$item];
            $lowerProps = array_change_key_case($propertyTranslations, CASE_LOWER);
            return $lowerProps[strtolower($item)] ?? null;
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

            // Helper for case-insensitive lookup
            $translateLabel = function ($label) use ($labelTranslations) {
                if (!$label)
                    return null;
                // Try exact match first
                if (isset($labelTranslations[$label]))
                    return $labelTranslations[$label];
                // Try lowercase match
                $lower = array_change_key_case($labelTranslations, CASE_LOWER);
                return $lower[strtolower($label)] ?? null;
            };

            // Helper for case-insensitive definition lookup
            $translateDefinition = function ($codeOrText) use ($definitionTranslations) {
                if (!$codeOrText)
                    return null;
                // Try code match or exact text match first
                if (isset($definitionTranslations[$codeOrText]))
                    return $definitionTranslations[$codeOrText];
                // Try lowercase match
                $lower = array_change_key_case($definitionTranslations, CASE_LOWER);
                return $lower[strtolower($codeOrText)] ?? null;
            };

            // Domain
            $domain = Domain::firstOrCreate(
                ['code' => (string) $domainData['numero']],
                [
                    'name' => $domainData['nombre'], // English Name
                    'name_es' => $translateLabel($domainData['nombre']),
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
                        'name_es' => $translateLabel($classData['nombre']),
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
                    $labelEs = $translateLabel($code) ?? $translateLabel($diagData['nombre']);
                    $defEs = $translateDefinition($code) ?? $translateDefinition($diagData['definicion']);

                    // Lists
                    $riskFactors = $diagData['risk_factors'] ?? [];
                    $atRiskPop = $diagData['at_risk_population'] ?? [];
                    $assocCond = $diagData['associated_conditions'] ?? [];
                    $defChars = $diagData['defining_characteristics'] ?? [];
                    $relFactors = $diagData['related_factors'] ?? [];

                    Nanda::firstOrCreate(
                        [
                            'code' => $code,
                            'class_id' => $class->id,
                        ],
                        [
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
