<?php

namespace App\Console\Commands;

use App\Models\Nanda;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class RebuildNandaIndex extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'nanda:rebuild-index';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Rebuild the Nanda search index entirely from the database models';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting index rebuild...');

        $count = Nanda::count();
        $bar = $this->output->createProgressBar($count);

        DB::beginTransaction();

        try {
            // clear existing index
            DB::table('nanda_search_index')->delete();
            
            // Chunk results to avoid memory issues
            Nanda::with(['nandaClass.domain'])->chunk(100, function ($nandas) use ($bar) {
                foreach ($nandas as $model) {
                    if (!$model->nandaClass) continue;

                    DB::table('nanda_search_index')->insert([
                        'diagnosis_id' => $model->id,
                        'class_id' => $model->class_id,
                        'domain_name' => $model->nandaClass->domain->name ?? '',
                        'domain_name_es' => $model->nandaClass->domain->name_es ?? '',
                        'class_name' => $model->nandaClass->name,
                        'class_name_es' => $model->nandaClass->name_es ?? '',
                        'class_definition' => $model->nandaClass->definition,
                        'class_definition_es' => $model->nandaClass->definition_es ?? '',
                        'diagnosis_code' => $model->code,
                        'diagnosis_label' => $model->label,
                        'diagnosis_label_es' => $model->label_es ?? '',
                        'diagnosis_definition' => $model->description,
                        'diagnosis_definition_es' => $model->description_es ?? '',
                    ]);
                    
                    $bar->advance();
                }
            });

            DB::commit();
            $bar->finish();
            $this->newLine();
            $this->info('Index rebuild completed successfully.');
            
        } catch (\Exception $e) {
            DB::rollBack();
            $this->error('An error occurred: ' . $e->getMessage());
            return 1;
        }

        return 0;
    }
}
