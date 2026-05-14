<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('page_seo', function (Blueprint $table) {
            $table->id();
            $table->string('page_key', 80)->unique()->index();
            $table->string('page_label', 120);
            $table->string('meta_title', 120)->nullable();
            $table->string('meta_description', 160)->nullable();
            $table->string('og_title', 120)->nullable();
            $table->string('og_description', 200)->nullable();
            $table->string('og_image', 255)->nullable();
            $table->string('canonical_url', 255)->nullable();
            $table->boolean('no_index')->default(false);
            $table->string('schema_type', 60)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('page_seo');
    }
};
