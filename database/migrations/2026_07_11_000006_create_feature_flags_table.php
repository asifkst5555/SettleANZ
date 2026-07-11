<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('feature_flags', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('module_key')->unique();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('group')->default('general');
            $table->boolean('is_enabled')->default(true);
            $table->boolean('is_visible')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->index('group');
            $table->index('is_enabled');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('feature_flags');
    }
};
