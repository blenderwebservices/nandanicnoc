<?php

require __DIR__ . '/../../vendor/autoload.php';
$app = require_once __DIR__ . '/../../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Domain;
use App\Models\Nanda;

$files = glob(__DIR__ . '/nanda_data_domain*_v3*');
sort($files);

echo str_pad("File", 40) . " | " . str_pad("Count in File", 15) . " | " . str_pad("Count in DB (Domain)", 20) . "\n";
echo str_repeat("-", 80) . "\n";

foreach ($files as $file) {
    if (is_dir($file))
        continue;
    $json = json_decode(file_get_contents($file), true);
    if (!isset($json['dominio']))
        continue;

    $domainNum = $json['dominio']['numero'];
    $fileCodes = [];
    foreach ($json['dominio']['clases'] as $class) {
        foreach ($class['diagnosticos'] as $diag) {
            if (!empty($diag['codigo'])) {
                $fileCodes[] = $diag['codigo'];
            }
        }
    }

    $countInFile = count($fileCodes);

    $countInDB = Nanda::whereHas('nandaClass.domain', function ($q) use ($domainNum) {
        $q->where('code', $domainNum);
    })->count();

    echo str_pad(basename($file), 40) . " | " . str_pad($countInFile, 15) . " | " . str_pad($countInDB, 20) . "\n";
}
