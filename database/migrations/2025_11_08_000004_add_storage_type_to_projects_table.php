<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            if (!Schema::hasColumn('projects', 'storage_type')) {
                $table->string('storage_type', 20)
                    ->default('local')
                    ->after('source_file');
            }
        });

        // Ensure existing records default to local
        DB::table('projects')
            ->whereNull('storage_type')
            ->update(['storage_type' => 'local']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            if (Schema::hasColumn('projects', 'storage_type')) {
                $table->dropColumn('storage_type');
            }
        });
    }
};

