<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class NandaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $translations = require __DIR__ . '/NandaTranslations.php';
        $propTranslations = require __DIR__ . '/NandaPropertyTranslations.php';
        $defTranslations = require __DIR__ . '/NandaDefinitionTranslations.php';

        $translateArray = function($items) use ($propTranslations) {
            if (!is_array($items)) return [];
            return array_map(function($item) use ($propTranslations) {
                return $propTranslations[$item] ?? $item;
            }, $items);
        };

        // Get all nanda_data_domain* files
        $files = glob(__DIR__ . '/nanda_data_domain*');
        sort($files); 

        foreach ($files as $file) {
            if (is_dir($file)) continue;

            $json = file_get_contents($file);
            if (empty($json)) continue; // Skip empty files

            $decoded = json_decode($json, true);
            if (!$decoded || !isset($decoded['domain'])) continue; // Skip invalid JSON

            $domainData = $decoded['domain'];

            // 1. Create/Update Domain
            $domain = \App\Models\Domain::updateOrCreate(
                ['code' => (string) $domainData['number']],
                [
                    'name' => $domainData['name'],
                    // Lookup Domain Translation
                    'name_es' => $translations[$domainData['name']] ?? null,
                ]
            );

            foreach ($domainData['classes'] as $classData) {
                // 2. Create/Update Class
                $class = \App\Models\NandaClass::updateOrCreate(
                    [
                        'domain_id' => $domain->id,
                        'code' => (string) $classData['number']
                    ],
                    [
                        'name' => $classData['name'],
                        // Lookup Class Translation
                        'name_es' => $translations[$classData['name']] ?? null,
                        'definition' => $classData['description'] ?? '',
                        'definition_es' => null, 
                    ]
                );

                // Check key 'diagnosticos' (Spanish in JSON) or 'diagnostics'
                $diagnostics = $classData['diagnosticos'] ?? $classData['diagnostics'] ?? [];

                foreach ($diagnostics as $diagData) {
                    // Extract fields from new JSON structure
                    $code = $diagData['codigo_de_diagnostico'] ?? null;
                    
                    if (!$code) continue;

                    // Generate a label since it's not explicit in the new JSON
                    // We combine Focus + Judgment for a reasonable default
                    $focus = $diagData['foco_conceptual'] ?? '';
                    $judgment = $diagData['juicio'] ?? '';
                    $generatedLabel = trim("$judgment $focus");
                    
                    // Cleanup label (e.g. "Impaired Sleep" vs "Sleep Impaired" - NANDA usually puts Focus then Qualifier or vice versa depending on language, 
                    // ideally we'd map this but for now we construct it). 
                    // Actually, looking at the JSON, "Definicion" is distinct. 
                    // Let's try to find an existing translation to get the "Real Name" if possible, 
                    // otherwise use the constructed one.
                    // The translation key is often the English Name.
                    
                    // The old JSON had names. The new one doesn't. 
                    // We will just use the Constructed Label as the Primary Label for now.
                    
                    // Attempt to find Spanish label from translations using the "English" generated label? 
                    // Or maybe we can't easily without the exact key.
                    // Let's store the English Label as constructed.
                    
                    $updateData = [
                        'class_id' => $class->id,
                        'label' => $generatedLabel ?: "Diagnosis $code", 
                        'label_es' => null, // We'll try to find it below or leave null
                        'description' => $diagData['Definicion'] ?? '',
                        'description_es' => $defTranslations[$code] ?? $diagData['Definicion'] ?? '', 
                        
                        // New Fields
                        'approval_year' => $diagData['ano_de_aprobacion'] ?? null,
                        'evidence_level' => $diagData['nivel_de_evidencia'] ?? null,
                        'mesh_term' => $diagData['MeSH'] ?? null,
                        'focus' => $diagData['foco_conceptual'] ?? null,
                        'symptoms_context' => $diagData['foco_en_contexto_sintomas'] ?? null,
                        'care_subject' => $diagData['sujeto_del_cuidado'] ?? null,
                        'judgment' => $diagData['juicio'] ?? null,
                        'anatomical_location' => $diagData['localizacion_anatomica'] ?? null,
                        'age_limit_lower' => $diagData['limite_inferior_de_edad'] ?? null,
                        'age_limit_upper' => $diagData['limite_superior_de_edad'] ?? null,
                        'clinical_course' => $diagData['curso_clinico'] ?? null,
                        'diagnosis_status' => $diagData['estado_del_diagnostico'] ?? null,
                        'situational_limitation' => $diagData['limitacion_situacional'] ?? null,
                        'risk_factors' => $diagData['Factores_de_Riesgo'] ?? [],
                        'at_risk_population' => $diagData['Poblacion_de_Riesgo'] ?? [],
                        'associated_conditions' => $diagData['Condiciones_asociadas'] ?? [],
                        
                        // Spanish Placeholders (Duplicating English data as per lack of source)
                        'focus_es' => $propTranslations[$diagData['foco_conceptual']] ?? $diagData['foco_conceptual'] ?? null,
                        'judgment_es' => $propTranslations[$diagData['juicio']] ?? $diagData['juicio'] ?? null,
                        'diagnosis_status_es' => $propTranslations[$diagData['estado_del_diagnostico']] ?? $diagData['estado_del_diagnostico'] ?? null,
                        'risk_factors_es' => $translateArray($diagData['Factores_de_Riesgo'] ?? []),
                        'at_risk_population_es' => $translateArray($diagData['Poblacion_de_Riesgo'] ?? []),
                        'associated_conditions_es' => $translateArray($diagData['Condiciones_asociadas'] ?? []),
                    ];


                    // 3. Translation Lookup
                    // Priority 1: Lookup by Code
                    if (isset($translations[$code])) {
                         $updateData['label_es'] = $translations[$code];
                    } 
                    // Priority 2: Lookup by English Label (Generated)
                    elseif (isset($translations[$generatedLabel])) {
                         $updateData['label_es'] = $translations[$generatedLabel];
                    }
                    // Priority 3: Lookup by JSON explicit 'Definicion' key if it was actually a name (rare in this dataset)


                    // Try to match with translations if possible by guessing the english key... 
                    // It's hard because the keys in NandaTranslations.php are like "Decreased diversional activity engagement"
                    // checking if that key exists in our generated label is risky.
                    
                    // However, we can reverse lookup? No.
                    // Let's iterate translations? Too slow.
                    
                    // The old seeder logic used exact strings from the old JSON.
                    // Ideally we should preserve the old JSON names if we could link them by Code, 
                    // but the old JSON didn't have codes! It was hierarchical only.
                    
                    // So we are starting fresh with Codes.
                    
                    \App\Models\Nanda::updateOrCreate(
                        ['code' => $code],
                        $updateData
                    );
                }
            }
        }
    }
}
