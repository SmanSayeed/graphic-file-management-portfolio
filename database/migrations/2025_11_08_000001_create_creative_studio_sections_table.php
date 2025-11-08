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
        Schema::create('creative_studio_sections', function (Blueprint $table) {
            $table->id();
            $table->string('section_title')->nullable();
            $table->string('section_subtitle')->nullable();
            $table->string('profile_name')->nullable();
            $table->string('profile_role')->nullable();
            $table->string('image_path')->nullable();
            $table->text('description')->nullable();
            $table->string('highlight_one')->nullable();
            $table->string('highlight_two')->nullable();
            $table->string('highlight_three')->nullable();
            $table->string('highlight_four')->nullable();
            $table->string('cta_text')->nullable();
            $table->string('cta_link')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('creative_studio_sections');
    }
};

