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
        DB::statement('CREATE VIRTUAL TABLE nanda_search_index USING fts5(
            diagnosis_id UNINDEXED, 
            class_id UNINDEXED, 
            domain_name, 
            class_name, 
            class_definition,
            diagnosis_code,
            diagnosis_label,
            diagnosis_definition
        );');

        // Triggers would go here to keep it updated, OR we handle it in Model events.
        // For simplicity and robustness with Filament/Laravel, Model events are easier to debug, 
        // but SQL triggers are closer to the "Database Design" request. 
        // Let's rely on an "Observer" or model events to populate this for now as it's more Laravel-way,
        // or just insert into it manually. 
        // ACTUALLY, the user requested a specific structure.
        // Let's implement the trigger approach if possible or just use it as a search index we write to.

        // Let's stick to the requested structure "nanda_busqueda" but mapped to our schema.
        // Since we have separate tables, a VIEW is not enough for FTS5 (needs to be a real virtual table).
        // WE WILL USE A TRIGGER APPROACH IN A FOLLOW-UP STEP OR JUST POPULATE IT. 
        // For this migration, just create the table.
    }

    public function down(): void
    {
        Schema::dropIfExists('nanda_search_index');
    }
};
