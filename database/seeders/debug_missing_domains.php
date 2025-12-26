<?php

require __DIR__ . '/../../vendor/autoload.php';
$app = require_once __DIR__ . '/../../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Nanda;

$domainsToCheck = ['01', '03', '04', '05', '09', '10', '11', '12'];

foreach ($domainsToCheck as $dNum) {
    $file = __DIR__ . "/nanda_data_domain{$dNum}_v3";
    if (!file_exists($file))
        continue;

    $json = json_decode(file_get_contents($file), true);
    $fileCodes = [];
    foreach ($json['dominio']['clases'] as $class) {
        foreach ($class['diagnosticos'] as $diag) {
            if (!empty($diag['codigo'])) {
                $fileCodes[$diag['codigo']] = $diag['nombre'];
            }
        }
    }

    $dbCodes = Nanda::whereHas('nandaClass.domain', function ($q) use ($dNum) {
        $q->where('code', ltrim($dNum, '0'));
    })->pluck('code')->toArray();

    $missing = array_diff(array_keys($fileCodes), $dbCodes);

    echo "Domain $dNum Missing:\n";
    foreach ($missing as $mCode) {
        $existing = Nanda::where('code', $mCode)->first();
        echo "  - $mCode: \"{$fileCodes[$mCode]}\"\n";
        if ($existing) {
            echo "    (Collision: In DB it is \"{$existing->label}\" in Domain " . $existing->nandaClass->domain->code . ")\n";
        }
    }
    echo "\n";
}
