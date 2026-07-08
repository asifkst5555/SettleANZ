<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('download_tokens', function (Blueprint $table) {
            $table->id();
            $table->uuid('token')->unique();
            $table->foreignId('ebook_id')->constrained()->cascadeOnDelete();
            $table->foreignId('lead_id')->constrained()->cascadeOnDelete();
            $table->string('status', 20)->default('active')->index();
            $table->integer('max_downloads')->default(5);
            $table->integer('download_count')->default(0);
            $table->timestamp('expires_at');
            $table->timestamp('last_downloaded_at')->nullable();
            $table->timestamps();

            $table->index(['token', 'status', 'expires_at']);
            $table->index(['ebook_id', 'lead_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('download_tokens');
    }
};
