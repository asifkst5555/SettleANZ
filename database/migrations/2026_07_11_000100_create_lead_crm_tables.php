<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->string('phone', 60)->nullable()->change();
            $table->string('company', 200)->nullable()->change();
            $table->string('country', 100)->nullable()->change();

            $table->string('priority', 20)->default('medium')->after('status');
            $table->integer('lead_score')->default(0)->after('priority');
            $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete()->after('lead_score');
            $table->string('source', 60)->nullable()->after('form_type');
            $table->string('interested_service', 200)->nullable()->after('source');
            $table->string('website', 200)->nullable()->after('company');
            $table->string('company_size', 50)->nullable()->after('website');
            $table->string('timezone', 60)->nullable()->after('country');
            $table->decimal('budget', 12, 2)->nullable()->after('timezone');
            $table->string('social_profile', 200)->nullable()->after('budget');
            $table->boolean('is_archived')->default(false)->after('consent');
            $table->boolean('is_spam')->default(false)->after('is_archived');
            $table->timestamp('last_activity_at')->nullable()->after('updated_at');
            $table->timestamp('converted_at')->nullable()->after('last_activity_at');
            $table->string('converted_to', 100)->nullable()->after('converted_at');
            $table->foreignId('merged_into_id')->nullable()->constrained('leads')->nullOnDelete()->after('converted_to');
        });

        Schema::create('lead_activities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lead_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('type', 60);
            $table->string('label', 200)->nullable();
            $table->text('description')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();

            $table->index(['lead_id', 'created_at']);
            $table->index('type');
        });

        Schema::create('lead_notes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lead_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->text('content');
            $table->boolean('is_private')->default(false);
            $table->boolean('is_pinned')->default(false);
            $table->json('mentions')->nullable();
            $table->timestamps();

            $table->index(['lead_id', 'is_pinned', 'created_at']);
        });

        Schema::create('lead_tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lead_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();
            $table->string('title', 200);
            $table->text('description')->nullable();
            $table->string('type', 30)->default('follow_up');
            $table->string('status', 30)->default('pending');
            $table->string('priority', 20)->default('medium');
            $table->timestamp('due_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();

            $table->index(['lead_id', 'status', 'due_at']);
        });

        Schema::create('tags', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('slug', 100)->unique();
            $table->string('color', 20)->default('#6366f1');
            $table->timestamps();
        });

        Schema::create('lead_tag', function (Blueprint $table) {
            $table->foreignId('lead_id')->constrained()->cascadeOnDelete();
            $table->foreignId('tag_id')->constrained('tags')->cascadeOnDelete();
            $table->primary(['lead_id', 'tag_id']);
        });

        Schema::create('lead_status_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lead_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('from_status', 30)->nullable();
            $table->string('to_status', 30);
            $table->timestamps();

            $table->index(['lead_id', 'created_at']);
        });

        Schema::create('lead_emails', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lead_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('subject', 200);
            $table->text('body')->nullable();
            $table->foreignId('email_template_id')->nullable()->constrained('email_templates')->nullOnDelete();
            $table->timestamp('sent_at')->nullable();
            $table->timestamp('opened_at')->nullable();
            $table->timestamp('clicked_at')->nullable();
            $table->timestamps();

            $table->index(['lead_id', 'sent_at']);
        });

        Schema::create('lead_files', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lead_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('filename', 200);
            $table->string('original_filename', 200);
            $table->string('path', 500);
            $table->string('mime_type', 100)->nullable();
            $table->integer('size')->nullable();
            $table->timestamps();

            $table->index(['lead_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lead_files');
        Schema::dropIfExists('lead_emails');
        Schema::dropIfExists('lead_status_history');
        Schema::dropIfExists('lead_tag');
        Schema::dropIfExists('tags');
        Schema::dropIfExists('lead_tasks');
        Schema::dropIfExists('lead_notes');
        Schema::dropIfExists('lead_activities');

        Schema::table('leads', function (Blueprint $table) {
            $table->dropForeign(['assigned_to']);
            $table->dropForeign(['merged_into_id']);
            $table->dropColumn([
                'priority', 'lead_score', 'assigned_to', 'source', 'interested_service',
                'website', 'company_size', 'timezone', 'budget', 'social_profile',
                'is_archived', 'is_spam', 'last_activity_at', 'converted_at',
                'converted_to', 'merged_into_id',
            ]);
        });
    }
};
