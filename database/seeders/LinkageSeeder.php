<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LinkageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Find NANDA: Excessive sedentary behaviors (00355)
        $nanda = \App\Models\Nanda::where('code', '00355')->first();

        // Find NICs
        $nicExercise = \App\Models\Nic::where('code', '0200')->first();
        $nicActivity = \App\Models\Nic::where('code', '4310')->first();

        // Find NOCs
        $nocLeisure = \App\Models\Noc::where('code', '1604')->first();
        $nocFitness = \App\Models\Noc::where('code', '2004')->first();

        if ($nanda) {
            if ($nicExercise) $nanda->nics()->attach($nicExercise->id, ['type' => 'major']);
            if ($nicActivity) $nanda->nics()->attach($nicActivity->id, ['type' => 'suggested']);

            if ($nocLeisure) $nanda->nocs()->attach($nocLeisure->id, ['type' => 'suggested']);
            if ($nocFitness) $nanda->nocs()->attach($nocFitness->id, ['type' => 'major']);
        }
    }
}
