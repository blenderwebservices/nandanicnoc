<?php

$files = glob(__DIR__ . '/nanda_data_domain*_v3*');
sort($files);

$codeMap = [];

foreach ($files as $file) {
    if (is_dir($file))
        continue;
    $json = json_decode(file_get_contents($file), true);
    if (!isset($json['dominio']))
        continue;

    $domainNum = $json['dominio']['numero'];
    foreach ($json['dominio']['clases'] as $class) {
        foreach ($class['diagnosticos'] as $diag) {
            $code = $diag['codigo'] ?? null;
            if ($code) {
                if (!isset($codeMap[$code])) {
                    $codeMap[$code] = [];
                }
                $codeMap[$code][] = [
                    'file' => basename($file),
                    'domain' => $domainNum,
                    'name' => $diag['nombre'] ?? 'N/A'
                ];
            }
        }
    }
}

foreach ($codeMap as $code => $occurrences) {
    if (count($occurrences) > 1) {
        echo "Code $code appears in:\n";
        foreach ($occurrences as $occ) {
            echo "  - {$occ['file']} (Domain {$occ['domain']}): {$occ['name']}\n";
        }
    }
}
