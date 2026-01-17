<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class NocSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Noc::create([
            'code' => '1604',
            'label' => 'Leisure Participation',
            'label_es' => 'Participación en el ocio',
            'definition' => 'Use of restful or relaxing activities to promote well-being.',
            'definition_es' => 'Uso de actividades relajantes o de descanso para fomentar el bienestar.',
            'indicators' => [
                'Reports relaxation',
                'Reports enjoyment',
                'Uses leisure time effectively'
            ],
            'indicators_es' => [
                'Refiere relajación',
                'Refiere disfrute',
                'Utiliza el tiempo libre de forma eficaz'
            ],
        ]);

        \App\Models\Noc::create([
            'code' => '2004',
            'label' => 'Physical Fitness',
            'label_es' => 'Forma física',
            'definition' => 'Performance of physical activities with vigor.',
            'definition_es' => 'Realización de actividades físicas con vigor.',
            'indicators' => [
                'Muscle strength',
                'Cardiorespiratory endurance',
                'Flexibility'
            ],
            'indicators_es' => [
                'Fuerza muscular',
                'Resistencia cardiorrespiratoria',
                'Flexibilidad'
            ],
        ]);
    }
}
