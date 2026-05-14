<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('blog_posts', function (Blueprint $table) {
            if (!Schema::hasColumn('blog_posts', 'meta_title')) {
                $table->string('meta_title', 70)->nullable()->after('excerpt');
            }

            if (!Schema::hasColumn('blog_posts', 'meta_description')) {
                $table->string('meta_description', 200)->nullable()->after('meta_title');
            }

            if (!Schema::hasColumn('blog_posts', 'og_title')) {
                $table->string('og_title', 120)->nullable()->after('meta_description');
            }

            if (!Schema::hasColumn('blog_posts', 'og_description')) {
                $table->string('og_description', 200)->nullable()->after('og_title');
            }

            if (!Schema::hasColumn('blog_posts', 'og_image')) {
                $table->string('og_image', 255)->nullable()->after('og_description');
            }

            if (!Schema::hasColumn('blog_posts', 'canonical_url')) {
                $table->string('canonical_url', 255)->nullable()->after('og_image');
            }

            if (!Schema::hasColumn('blog_posts', 'no_index')) {
                $table->boolean('no_index')->default(false)->after('canonical_url');
            }

            if (!Schema::hasColumn('blog_posts', 'schema_type')) {
                $table->string('schema_type', 60)->nullable()->after('no_index');
            }
        });
    }

    public function down(): void
    {
        Schema::table('blog_posts', function (Blueprint $table) {
            foreach ([
                'schema_type',
                'no_index',
                'canonical_url',
                'og_image',
                'og_description',
                'og_title',
                'meta_description',
                'meta_title',
            ] as $column) {
                if (Schema::hasColumn('blog_posts', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
