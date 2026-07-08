<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->foreignId('ebook_id')->nullable()->constrained()->nullOnDelete();
            $table->string('phone', 30)->nullable()->after('email');
            $table->string('company', 200)->nullable()->after('phone');
            $table->string('country', 100)->nullable()->after('company');
            $table->boolean('consent')->default(false)->after('country');
            $table->string('utm_source', 200)->nullable()->after('consent');
            $table->string('utm_medium', 200)->nullable()->after('utm_source');
            $table->string('utm_campaign', 200)->nullable()->after('utm_medium');
            $table->string('utm_term', 200)->nullable()->after('utm_campaign');
            $table->string('utm_content', 200)->nullable()->after('utm_term');
        });
    }

    public function down(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->dropForeign(['ebook_id']);
            $table->dropColumn([
                'ebook_id', 'phone', 'company', 'country', 'consent',
                'utm_source', 'utm_medium', 'utm_campaign',
                'utm_term', 'utm_content',
            ]);
        });
    }
};
