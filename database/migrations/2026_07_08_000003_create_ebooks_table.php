<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ebooks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->nullable()->constrained('ebook_categories')->nullOnDelete();
            $table->string('title', 200);
            $table->string('slug', 220)->unique();
            $table->text('description')->nullable();
            $table->string('file_path', 500);
            $table->string('file_name', 200);
            $table->string('file_type', 20); // pdf, zip, docx, etc.
            $table->unsignedBigInteger('file_size')->default(0); // bytes
            $table->string('thumbnail_path', 500)->nullable();
            $table->string('status', 20)->default('draft')->index();
            $table->string('author', 150)->nullable();
            $table->integer('current_version')->default(1);
            $table->string('isbn', 20)->nullable();
            $table->integer('page_count')->nullable();
            $table->string('language', 10)->default('en');
            $table->integer('download_count')->default(0);
            $table->integer('lead_count')->default(0);
            $table->json('metadata')->nullable();
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['status', 'published_at']);
            $table->index('author');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ebooks');
    }
};
