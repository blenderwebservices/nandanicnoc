<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class NicSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Nic::create([
            'code' => '0200',
            'label' => 'Exercise Promotion',
            'label_es' => 'Fomento del ejercicio',
            'definition' => 'Facilitation of regular physical activity to maintain or improve the higher level of fitness and health.',
            'definition_es' => 'Facilitación de la actividad física regular para mantener o mejorar el nivel superior de forma física y salud.',
            'activities' => [
                'Assess individual\'s health beliefs about physical exercise',
                'Investigate past experiences with exercise',
                'Determine individual\'s motivation to start/continue exercise program'
            ],
            'activities_es' => [
                'Evaluar las creencias de salud del individuo sobre el ejercicio físico',
                'Investigar experiencias pasadas con el ejercicio',
                'Determinar la motivación del individuo para empezar/continuar el programa de ejercicios'
            ],
        ]);

        \App\Models\Nic::create([
            'code' => '4310',
            'label' => 'Activity Therapy',
            'label_es' => 'Terapia de actividad',
            'definition' => 'Prescription of and assistance with specific physical, cognitive, social, and spiritual activities to increase the range, frequency, or duration of an individual\'s (or group\'s) activity.',
            'definition_es' => 'Prescripción y asistencia con actividades físicas, cognitivas, sociales y espirituales específicas para aumentar el rango, la frecuencia o la duración de la actividad de un individuo (o grupo).',
            'activities' => [
                'Determine client\'s capability to participate in specific activities',
                'Collaborate with occupational, physical, and/or recreational therapists',
                'Assist client to explore personal perception of strength performance'
            ],
            'activities_es' => [
                'Determinar la capacidad del cliente para participar en actividades específicas',
                'Colaborar con terapeutas ocupacionales, físicos y/o recreativos',
                'Ayudar al cliente a explorar la percepción personal del rendimiento de la fuerza'
            ]
        ]);
    }
}
