<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->enum('processing_status', ['pending', 'processing', 'completed', 'failed'])->default('completed')->after('is_active');
            $table->unsignedBigInteger('processing_job_id')->nullable()->after('processing_status');
            $table->text('processing_error')->nullable()->after('processing_job_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn(['processing_status', 'processing_job_id', 'processing_error']);
        });
    }
};
