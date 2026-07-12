<?php

use App\Support\SystemEmailTemplates;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('email_templates')
            ->where('type', 'download')
            ->where('is_active', true)
            ->orderBy('id')
            ->limit(1)
            ->update([
                'name' => 'Download Delivery - Final',
                'subject' => SystemEmailTemplates::downloadSubject(),
                'body_html' => SystemEmailTemplates::downloadHtml(),
                'body_text' => SystemEmailTemplates::downloadText(),
                'builder_json' => json_encode(SystemEmailTemplates::downloadBuilderJson()),
                'updated_at' => now(),
            ]);
    }

    public function down(): void
    {
        // Keep the current editable template if this migration is rolled back.
    }
};
