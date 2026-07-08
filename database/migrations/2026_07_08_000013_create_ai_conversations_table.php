<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ai_conversations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('title', 200)->nullable();
            $table->string('context_type', 50)->nullable(); // email_generation, admin_assistant, follow_up
            $table->json('context_data')->nullable();
            $table->string('status', 30)->default('active');
            $table->timestamps();

            $table->index(['user_id', 'status']);
            $table->index('context_type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ai_conversations');
    }
};
