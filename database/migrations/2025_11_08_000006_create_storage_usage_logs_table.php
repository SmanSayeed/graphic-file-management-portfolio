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
        Schema::create('storage_usage_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->nullable()->constrained()->cascadeOnDelete();
            $table->enum('storage_type', ['local', 's3'])->default('local');
            $table->string('action', 50); // upload, delete, download, etc.
            $table->string('request_type', 20)->nullable(); // GET, PUT, etc.
            $table->string('path')->nullable();
            $table->unsignedBigInteger('bytes')->default(0);
            $table->enum('status', ['success', 'warning', 'failed'])->default('success');
            $table->text('message')->nullable();
            $table->timestamps();

            $table->index(['storage_type', 'action']);
            $table->index(['created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('storage_usage_logs');
    }
};

