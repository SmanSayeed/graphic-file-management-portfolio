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
        Schema::table('footer_contents', function (Blueprint $table) {
            if (! Schema::hasColumn('footer_contents', 'description')) {
                $table->text('description')->nullable()->after('id');
            }

            if (! Schema::hasColumn('footer_contents', 'copyright_text')) {
                $table->string('copyright_text')->nullable();
            }
        });

        // Migrate data from about_text to description if needed
        if (Schema::hasColumn('footer_contents', 'about_text')) {
            \Illuminate\Support\Facades\DB::table('footer_contents')
                ->whereNotNull('about_text')
                ->update([
                    'description' => \Illuminate\Support\Facades\DB::raw('about_text'),
                ]);
        }

        Schema::table('footer_contents', function (Blueprint $table) {
            if (Schema::hasColumn('footer_contents', 'about_text')) {
                $table->dropColumn('about_text');
            }
            if (Schema::hasColumn('footer_contents', 'services')) {
                $table->dropColumn('services');
            }
            if (Schema::hasColumn('footer_contents', 'privacy_policy_url')) {
                $table->dropColumn('privacy_policy_url');
            }
            if (Schema::hasColumn('footer_contents', 'terms_of_service_url')) {
                $table->dropColumn('terms_of_service_url');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('footer_contents', function (Blueprint $table) {
            if (! Schema::hasColumn('footer_contents', 'about_text')) {
                $table->text('about_text')->nullable()->after('id');
            }
            if (! Schema::hasColumn('footer_contents', 'services')) {
                $table->text('services')->nullable();
            }
            if (! Schema::hasColumn('footer_contents', 'privacy_policy_url')) {
                $table->string('privacy_policy_url')->nullable();
            }
            if (! Schema::hasColumn('footer_contents', 'terms_of_service_url')) {
                $table->string('terms_of_service_url')->nullable();
            }
        });

        if (Schema::hasColumn('footer_contents', 'description')) {
            \Illuminate\Support\Facades\DB::table('footer_contents')
                ->whereNotNull('description')
                ->update([
                    'about_text' => \Illuminate\Support\Facades\DB::raw('description'),
                ]);
        }

        Schema::table('footer_contents', function (Blueprint $table) {
            if (Schema::hasColumn('footer_contents', 'description')) {
                $table->dropColumn('description');
            }
        });
    }
};

