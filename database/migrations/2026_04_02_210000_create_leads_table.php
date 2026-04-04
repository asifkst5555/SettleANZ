<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('leads', function (Blueprint $table) {
            $table->id();
            $table->string('first_name', 100)->nullable();
            $table->string('full_name', 150)->nullable();
            $table->string('email', 150)->index();
            $table->string('goal', 255)->nullable();
            $table->string('form_type', 40)->default('general')->index();
            $table->string('source_page', 100)->nullable()->index();
            $table->string('status', 30)->default('new')->index();
            $table->timestamp('subscribed_at')->nullable();
            $table->text('notes')->nullable();
            $table->json('metadata')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('leads');
    }
};
