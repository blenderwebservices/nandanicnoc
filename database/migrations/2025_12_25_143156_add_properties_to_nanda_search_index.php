<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement('DROP TABLE IF EXISTS nanda_search_index');

        DB::statement('CREATE VIRTUAL TABLE nanda_search_index USING fts5(
            diagnosis_id UNINDEXED, 
            class_id UNINDEXED, 
            domain_name, 
            domain_name_es,
            class_name, 
            class_name_es,
            class_definition,
            class_definition_es,
            diagnosis_code,
            diagnosis_label,
            diagnosis_label_es,
            diagnosis_definition,
            diagnosis_definition_es,
            properties,
            properties_es
        );');
    }

    public function down(): void
    {
        DB::statement('DROP TABLE IF EXISTS nanda_search_index');

        // Restore previous state
        DB::statement('CREATE VIRTUAL TABLE nanda_search_index USING fts5(
            diagnosis_id UNINDEXED, 
            class_id UNINDEXED, 
            domain_name, 
            domain_name_es,
            class_name, 
            class_name_es,
            class_definition,
            class_definition_es,
            diagnosis_code,
            diagnosis_label,
            diagnosis_label_es,
            diagnosis_definition,
            diagnosis_definition_es
        );');
    }
};
