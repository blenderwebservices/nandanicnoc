<?php

$translations = require 'database/seeders/NandaTranslations.php';
$files = glob('database/seeders/nanda_data_domain*');
sort($files);

$missing = [];
$found = 0;

foreach ($files as $file) {
    if (is_dir($file)) continue;
    $json = file_get_contents($file);
    if (empty($json)) continue;
    $decoded = json_decode($json, true);
    if (!$decoded || !isset($decoded['domain'])) continue;

    foreach ($decoded['domain']['classes'] as $classData) {
        $diagnostics = $classData['diagnosticos'] ?? $classData['diagnostics'] ?? [];
        foreach ($diagnostics as $diagData) {
            $code = $diagData['codigo_de_diagnostico'] ?? null;
            if (!$code) continue;

            $focus = $diagData['foco_conceptual'] ?? '';
            $judgment = $diagData['juicio'] ?? '';
            $generatedLabel = trim("$judgment $focus");
            
            // Allow for some normalization if needed, but strict for now
            if (isset($translations[$generatedLabel])) {
                $found++;
            } else {
                // Try to find if maybe the Focus + Judgment order is swapped in translations?
                // NANDA usually is Focus, Judgment (e.g. Anxiety) or Judgment Focus (Impaired Gas Exchange)
                // Our generator uses "$judgment $focus".
                
                $missing[$code] = $generatedLabel;
            }
        }
    }
}

echo "Found: $found\n";
echo "Missing: " . count($missing) . "\n";
print_r($missing);
