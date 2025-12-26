<?php

require __DIR__ . '/../../vendor/autoload.php';
$app = require_once __DIR__ . '/../../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Domain;
use App\Models\NandaClass;
use App\Models\Nanda;

echo "--- Domains missing Spanish names ---\n";
foreach (Domain::whereNull('name_es')->orWhere('name_es', '')->get() as $item) {
    echo "Domain {$item->code}: \"{$item->name}\"\n";
}

echo "\n--- Classes missing Spanish names ---\n";
foreach (NandaClass::whereNull('name_es')->orWhere('name_es', '')->get() as $item) {
    echo "Class {$item->code} (Domain {$item->domain->code}): \"{$item->name}\"\n";
}

echo "\n--- Diagnoses missing Spanish labels ---\n";
foreach (Nanda::whereNull('label_es')->orWhere('label_es', '')->get() as $item) {
    echo "Nanda {$item->code}: \"{$item->label}\"\n";
}

echo "\n--- Diagnoses missing Spanish descriptions ---\n";
foreach (Nanda::whereNull('description_es')->orWhere('description_es', '')->get() as $item) {
    echo "Nanda {$item->code}: \"{$item->label}\" (description: \"{$item->description}\")\n";
}

echo "\n--- Checking Property Lists for English content in Spanish columns ---\n";
// (This is harder to check automatically but we can check if they are identical to English or empty)
$fields = ['risk_factors', 'at_risk_population', 'associated_conditions', 'defining_characteristics', 'related_factors'];
foreach (Nanda::all() as $nanda) {
    foreach ($fields as $field) {
        $esField = $field . '_es';
        $en = $nanda->getAttributes()[$field] ?? '[]';
        $es = $nanda->getAttributes()[$esField] ?? '[]';

        $enArr = json_decode($en, true) ?: [];
        $esArr = json_decode($es, true) ?: [];

        if (count($enArr) > 0 && count($esArr) === 0) {
            echo "Nanda {$nanda->code}: Missing ES for $field (" . count($enArr) . " items)\n";
        } elseif (count($enArr) > 0 && $en === $es) {
            // Sometimes it's okay if translation is same (rare), but usually indicates missing translation
            // Let's just list them
            echo "Nanda {$nanda->code}: ES same as EN for $field\n";
        }
    }
}
