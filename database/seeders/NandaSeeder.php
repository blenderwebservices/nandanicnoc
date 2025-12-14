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

        // Spanish mappings for Domains
        $domainsEs = [
            1 => 'Promoción de la salud',
            2 => 'Nutrición',
            3 => 'Eliminación e intercambio',
            4 => 'Actividad / Reposo',
            5 => 'Percepción / Cognición',
            6 => 'Autopercepción',
            7 => 'Rol / Relaciones',
            8 => 'Sexualidad',
            9 => 'Afrontamiento / Tolerancia al estrés',
            10 => 'Principios vitales',
            11 => 'Seguridad / Protección',
            12 => 'Confort',
            13 => 'Crecimiento / Desarrollo',
        ];

        // Partial Spanish mappings for Classes (based on Domain-Class Structure)
        // Key is "DomainNumber-ClassNumber"
        $classesEs = [
            '1-1' => 'Toma de conciencia de la salud',
            '1-2' => 'Gestión de la salud',
            '2-1' => 'Ingestión',
            '2-2' => 'Digestión',
            '2-3' => 'Absorción',
            '2-4' => 'Metabolismo',
            '2-5' => 'Hidratación',
            '3-1' => 'Función urinaria',
            '3-2' => 'Función gastrointestinal',
            '3-3' => 'Función integumentaria',
            '3-4' => 'Función respiratoria',
            '4-1' => 'Sueño / Reposo',
            '4-2' => 'Actividad / Ejercicio',
            '4-3' => 'Equilibrio de la energía',
            '4-4' => 'Respuestas cardiovasculares / pulmonares',
            '4-5' => 'Autocuidado',
            '5-1' => 'Atención',
            '5-2' => 'Orientación',
            '5-3' => 'Sensación / Percepción',
            '5-4' => 'Cognición',
            '5-5' => 'Comunicación',
            '6-1' => 'Autoconcepto',
            '6-2' => 'Autoestima',
            '6-3' => 'Imagen corporal',
            '7-1' => 'Roles de cuidador',
            '7-2' => 'Relaciones familiares',
            '7-3' => 'Desempeño del rol',
            '8-1' => 'Identidad sexual',
            '8-2' => 'Función sexual',
            '8-3' => 'Reproducción',
            '9-1' => 'Respuestas postraumáticas',
            '9-2' => 'Respuestas de afrontamiento',
            '9-3' => 'Estrés neurocomportamental',
            '10-1' => 'Valores',
            '10-2' => 'Creencias',
            '10-3' => 'Congruencia de las acciones con los valores / creencias',
            '11-1' => 'Infección',
            '11-2' => 'Lesión física',
            '11-3' => 'Violencia',
            '11-4' => 'Peligros ambientales',
            '11-5' => 'Procesos defensivos',
            '11-6' => 'Termorregulación',
            '12-1' => 'Confort físico',
            '12-2' => 'Confort del entorno',
            '12-3' => 'Confort social',
            '12-4' => 'Confort psicológico',
            '13-1' => 'Crecimiento',
            '13-2' => 'Desarrollo',
        ];

        foreach ($data['domains'] as $domainData) {
            $domain = \App\Models\Domain::updateOrCreate(
                ['code' => (string) $domainData['number']],
                [
                    'name' => $domainData['name'],
                    'name_es' => $domainsEs[$domainData['number']] ?? null,
                ]
            );

            foreach ($domainData['classes'] as $classData) {
                $classKey = $domainData['number'] . '-' . $classData['number'];

                $class = \App\Models\NandaClass::updateOrCreate(
                    [
                        'domain_id' => $domain->id,
                        'code' => (string) $classData['number']
                    ],
                    [
                        'name' => $classData['name'],
                        'name_es' => $classesEs[$classKey] ?? null,
                        'definition' => $classData['description'] ?? '',
                        'definition_es' => null, // Definition translations not available yet
                    ]
                );

                foreach ($classData['diagnostics'] as $index => $diagnosticLabel) {
                    // Generate a synthetic code: DomainCode-ClassCode-Index
                    $code = sprintf('%s-%s-%03d', $domain->code, $class->code, $index + 1);

                    // Simple prefix for Spanish label demonstration since we don't have full translations
                    $labelEs = '[ES] ' . $diagnosticLabel;

                    \App\Models\Nanda::updateOrCreate(
                        ['code' => $code],
                        [
                            'class_id' => $class->id,
                            'label' => $diagnosticLabel,
                            'label_es' => $labelEs,
                            'description' => null,
                        ]
                    );
                }
            }
        }
    }
}
