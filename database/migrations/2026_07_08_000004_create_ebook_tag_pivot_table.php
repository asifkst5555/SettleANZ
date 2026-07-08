<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ebook_ebook_tag', function (Blueprint $table) {
            $table->foreignId('ebook_id')->constrained()->cascadeOnDelete();
            $table->foreignId('ebook_tag_id')->constrained('ebook_tags')->cascadeOnDelete();
            $table->primary(['ebook_id', 'ebook_tag_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ebook_ebook_tag');
    }
};
