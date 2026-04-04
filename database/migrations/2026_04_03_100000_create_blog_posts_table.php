<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('blog_posts', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('category', 80)->index();
            $table->text('excerpt');
            $table->string('author_name', 120)->default('SettleANZ Team');
            $table->string('reading_time', 40)->nullable();
            $table->string('image')->nullable();
            $table->string('image_class', 120)->nullable();
            $table->text('intro_content')->nullable();
            $table->text('checks_content')->nullable();
            $table->text('next_steps_content')->nullable();
            $table->boolean('is_published')->default(true)->index();
            $table->boolean('is_featured_home')->default(false)->index();
            $table->timestamp('published_at')->nullable()->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('blog_posts');
    }
};
