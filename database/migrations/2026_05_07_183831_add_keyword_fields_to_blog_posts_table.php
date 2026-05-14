<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('blog_posts', function (Blueprint $table) {
            if (!Schema::hasColumn('blog_posts', 'focus_keyword')) {
                $table->string('focus_keyword', 120)->nullable()->after('schema_type');
            }

            if (!Schema::hasColumn('blog_posts', 'secondary_keywords')) {
                $table->text('secondary_keywords')->nullable()->after('focus_keyword');
            }
        });
    }

    public function down(): void
    {
        Schema::table('blog_posts', function (Blueprint $table) {
            foreach (['secondary_keywords', 'focus_keyword'] as $column) {
                if (Schema::hasColumn('blog_posts', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
