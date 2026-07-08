<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ebook_versions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ebook_id')->constrained()->cascadeOnDelete();
            $table->integer('version_number');
            $table->string('file_path', 500);
            $table->string('file_name', 200);
            $table->string('file_type', 20);
            $table->unsignedBigInteger('file_size')->default(0);
            $table->text('change_log')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->unique(['ebook_id', 'version_number']);
            $table->index('created_by');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ebook_versions');
    }
};
