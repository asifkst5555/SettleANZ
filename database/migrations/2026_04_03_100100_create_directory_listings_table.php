<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('directory_listings', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('category', 120)->index();
            $table->string('city', 120)->index();
            $table->decimal('rating', 3, 1)->default(0);
            $table->boolean('featured')->default(false)->index();
            $table->string('description', 255);
            $table->text('full_description')->nullable();
            $table->json('services')->nullable();
            $table->string('phone', 50)->nullable();
            $table->string('email', 150)->nullable();
            $table->string('website')->nullable();
            $table->string('whatsapp')->nullable();
            $table->string('booking_url')->nullable();
            $table->string('logo')->nullable();
            $table->boolean('is_published')->default(true)->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('directory_listings');
    }
};
