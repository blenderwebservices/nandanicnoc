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
        $json = file_get_contents(__DIR__ . '/nanda_data.json');
        $data = json_decode($json, true);

        foreach ($data['domains'] as $domainData) {
            $domain = \App\Models\Domain::create([
                'code' => (string) $domainData['number'],
                'name' => $domainData['name'],
                // 'description' => $domainData['description'] ?? null, // Domain model has no description
            ]);

            foreach ($domainData['classes'] as $classData) {
                $class = \App\Models\NandaClass::create([
                    'domain_id' => $domain->id,
                    'code' => (string) $classData['number'],
                    'name' => $classData['name'],
                    'definition' => $classData['description'] ?? '',
                ]);

                foreach ($classData['diagnostics'] as $index => $diagnosticLabel) {
                    // Generate a synthetic code: DomainCode-ClassCode-Index
                    // Pad index to 3 digits
                    $code = sprintf('%s-%s-%03d', $domain->code, $class->code, $index + 1);

                    \App\Models\Nanda::create([
                        'class_id' => $class->id,
                        'code' => $code,
                        'label' => $diagnosticLabel,
                        'description' => null, // Missing in JSON
                    ]);
                }
            }
        }
    }
}
