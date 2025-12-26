<?php

require __DIR__ . '/../../vendor/autoload.php';
$app = require_once __DIR__ . '/../../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Domain;
use App\Models\NandaClass;
use App\Models\Nanda;

$missing = [
    'classes' => [],
    'labels' => [],
    'definitions' => [],
    'properties' => []
];

foreach (NandaClass::whereNull('name_es')->orWhere('name_es', '')->get() as $item) {
    if (!in_array($item->name, $missing['classes']))
        $missing['classes'][] = $item->name;
}

foreach (Nanda::whereNull('label_es')->orWhere('label_es', '')->get() as $item) {
    if (!in_array($item->label, $missing['labels']))
        $missing['labels'][] = $item->label;
}

foreach (Nanda::whereNull('description_es')->orWhere('description_es', '')->get() as $item) {
    $missing['definitions'][$item->code] = $item->description;
}

$fields = ['risk_factors', 'at_risk_population', 'associated_conditions', 'defining_characteristics', 'related_factors'];
foreach (Nanda::all() as $nanda) {
    foreach ($fields as $field) {
        $en = $nanda->getAttributes()[$field] ?? '[]';
        $es = $nanda->getAttributes()[$field . '_es'] ?? '[]';
        $enArr = json_decode($en, true) ?: [];
        $esArr = json_decode($es, true) ?: [];

        if (count($enArr) > 0 && ($en === $es || count($esArr) === 0)) {
            foreach ($enArr as $str) {
                if (!in_array($str, $missing['properties']))
                    $missing['properties'][] = $str;
            }
        }
    }
}

echo json_encode($missing, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
