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
        Schema::create('analytics_metrics', function (Blueprint $table) {
            $table->id();
            $table->date('metric_date')->unique();
            $table->unsignedBigInteger('visitors_total')->default(0);
            $table->unsignedBigInteger('downloads_total')->default(0);
            $table->unsignedBigInteger('bandwidth_local_bytes')->default(0);
            $table->unsignedBigInteger('bandwidth_s3_bytes')->default(0);
            $table->unsignedInteger('s3_get_requests')->default(0);
            $table->unsignedInteger('s3_put_requests')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('analytics_metrics');
    }
};

