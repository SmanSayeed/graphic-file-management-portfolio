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
        Schema::create('storage_settings', function (Blueprint $table) {
            $table->id();
            $table->enum('default_storage_type', ['local', 's3'])->default('local');
            $table->boolean('avoid_s3')->default(false);
            $table->string('s3_access_key')->nullable();
            $table->string('s3_secret_key')->nullable();
            $table->string('s3_region')->nullable();
            $table->string('s3_bucket')->nullable();
            $table->string('s3_prefix')->nullable();
            $table->string('s3_endpoint')->nullable();
            $table->boolean('s3_use_path_style_endpoint')->default(false);
            $table->boolean('s3_enable_usage_guard')->default(true);
            $table->enum('queue_connection', ['sync', 'database'])->default('database');
            $table->unsignedTinyInteger('queue_max_attempts')->default(3);
            $table->unsignedSmallInteger('queue_backoff')->default(5);
            $table->boolean('analytics_enabled')->default(true);
            $table->timestamps();
        });

        \Illuminate\Support\Facades\DB::table('storage_settings')->insert([
            'default_storage_type' => 'local',
            'avoid_s3' => false,
            's3_access_key' => null,
            's3_secret_key' => null,
            's3_region' => null,
            's3_bucket' => null,
            's3_prefix' => null,
            's3_endpoint' => null,
            's3_use_path_style_endpoint' => false,
            's3_enable_usage_guard' => true,
            'queue_connection' => 'database',
            'queue_max_attempts' => 3,
            'queue_backoff' => 5,
            'analytics_enabled' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('storage_settings');
    }
};

