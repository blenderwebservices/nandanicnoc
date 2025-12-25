<?php

use Illuminate\Support\Facades\File;

require __DIR__ . '/../../vendor/autoload.php';

$sourcePath = __DIR__ . '/nanda_data_domain03';
$targetPath = __DIR__ . '/nanda_data_domain03_v3';

// Manual Map of Code => English Label
$labels = [
    "00016" => "Impaired urinary elimination",
    "00322" => "Risk for urinary retention",
    "00297" => "Disability-associated urinary incontinence",
    "00310" => "Mixed urinary incontinence",
    "00017" => "Stress urinary incontinence",
    "00019" => "Urge urinary incontinence",
    "00022" => "Risk for urge urinary incontinence",
    "00423" => "Impaired gastrointestinal motility",
    "00422" => "Risk for impaired gastrointestinal motility",
    "00344" => "Impaired intestinal elimination",
    "00346" => "Risk for impaired intestinal elimination",
    "00235" => "Chronic functional constipation",
    "00236" => "Risk for chronic functional constipation",
    "00424" => "Impaired fecal continence",
    "00345" => "Risk for impaired fecal continence",
    "00030" => "Impaired gas exchange",
];

$sourceJson = json_decode(file_get_contents($sourcePath), true);
$targetJson = json_decode(file_get_contents($targetPath), true);

if (!$sourceJson) {
    die("Source JSON invalid\n");
}
if (!$targetJson) {
    die("Target JSON invalid\n");
}

// Helper to find diag in Target (v3) by MeSH
function findInTarget($targetClasses, $mesh)
{
    foreach ($targetClasses as $class) {
        foreach ($class['diagnosticos'] ?? [] as $diag) {
            if (isset($diag['mesh']) && $diag['mesh'] === $mesh) {
                return $diag;
            }
        }
    }
    return null;
}

$newClasses = [];

// Iterate Source (Mixed File) as the Master Structure
foreach ($sourceJson['domain']['classes'] as $sClass) {
    $newClass = [
        'numero' => $sClass['number'],
        'nombre' => $sClass['name'],
        'definicion' => $sClass['description'],
        'diagnosticos' => []
    ];

    foreach ($sClass['diagnosticos'] ?? [] as $sDiag) {
        $code = $sDiag['codigo_de_diagnostico'] ?? '';
        $mesh = $sDiag['MeSH'] ?? '';

        // Find matching diag in Target to get Defining Characteristics
        $tDiag = findInTarget($targetJson['dominio']['clases'], $mesh);

        $newDiag = [
            'codigo' => $code,
            'nombre' => $labels[$code] ?? "Diagnosis $code",
            'definicion' => $sDiag['Definicion'] ?? '',
            'aprobado' => $sDiag['ano_de_aprobacion'] ?? '',
            'revisado' => '', // Not in source
            'nivel_evidencia' => $sDiag['nivel_de_evidencia'] ?? '',
            'mesh' => $mesh,
            'concept_focus' => $sDiag['foco_conceptual'] ?? '',
            'context_symptom_focus' => $sDiag['foco_en_contexto_sintomas'] ?? '',
            'subject_of_care' => $sDiag['sujeto_del_cuidado'] ?? '',
            'judgment' => $sDiag['juicio'] ?? '',
            'anatomical_site' => $sDiag['localizacion_anatomica'] ?? '',
            'age_lower_limit' => $sDiag['limite_inferior_de_edad'] ?? '',
            'age_upper_limit' => $sDiag['limite_superior_de_edad'] ?? '',
            'clinical_course' => $sDiag['curso_clinico'] ?? '',
            'status_of_the_diagnosis' => $sDiag['estado_del_diagnostico'] ?? '',
            'situational_constraint' => $sDiag['limitacion_situacional'] ?? '',

            // Lists from SOURCE (trusted categorization)
            'risk_factors' => $sDiag['Factores_de_Riesgo'] ?? [],
            'at_risk_population' => $sDiag['Poblacion_de_Riesgo'] ?? [],
            'associated_conditions' => $sDiag['Condiciones_asociadas'] ?? [],
            'related_factors' => $sDiag['Factores_relacionados'] ?? [], // Source might not have this populated, usually it's risk OR related

            // Defining Characteristics from TARGET (since Source lacks them)
            'defining_characteristics' => $tDiag['defining_characteristics'] ?? [],
        ];

        // If Source lacked related_factors but Target has them (and they aren't actually risk factors miscategorized), consider merging?
        // But we saw v3 put Risk Factors into Related Factors for 00016.
        // Let's stick to Source for Risk/Related separation if Source has data.
        // If Source has empty Risk/Related, checking Target might be safer?
        // 00016: Source Risk has items. Target Related has same items. 
        // So Target miscategorized. We stick to Source.

        $newClass['diagnosticos'][] = $newDiag;
    }
    $newClasses[] = $newClass;
}

// Construct Final JSON
$finalJson = [
    'dominio' => [
        'numero' => $sourceJson['domain']['number'],
        'nombre' => $sourceJson['domain']['name'],
        'definicion' => $sourceJson['domain']['description'],
        'clases' => $newClasses
    ]
];

file_put_contents($targetPath, json_encode($finalJson, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));

echo "Merged Domain 03 data successfully.\n";
