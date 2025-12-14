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
        Schema::table('domains', function (Blueprint $table) {
            $table->string('name_es')->nullable()->after('name');
        });

        Schema::table('nanda_classes', function (Blueprint $table) {
            $table->string('name_es')->nullable()->after('name');
            $table->text('definition_es')->nullable()->after('definition');
        });

        Schema::table('nandas', function (Blueprint $table) {
            $table->string('label_es')->nullable()->after('label');
            $table->text('description_es')->nullable()->after('description');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('domains', function (Blueprint $table) {
            $table->dropColumn('name_es');
        });

        Schema::table('nanda_classes', function (Blueprint $table) {
            $table->dropColumn(['name_es', 'definition_es']);
        });

        Schema::table('nandas', function (Blueprint $table) {
            $table->dropColumn(['label_es', 'description_es']);
        });
    }
};
