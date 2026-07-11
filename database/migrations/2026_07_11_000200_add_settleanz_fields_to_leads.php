<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            if (!Schema::hasColumn('leads', 'visa_type')) {
                $table->string('visa_type', 100)->nullable()->after('goal');
            }
            if (!Schema::hasColumn('leads', 'landing_page_name')) {
                $table->string('landing_page_name', 100)->nullable()->after('source_page');
            }
            if (!Schema::hasColumn('leads', 'package_name')) {
                $table->string('package_name', 200)->nullable()->after('landing_page_name');
            }
            if (!Schema::hasColumn('leads', 'ebook_title')) {
                $table->string('ebook_title', 200)->nullable()->after('package_name');
            }
            if (!Schema::hasColumn('leads', 'conversation_summary')) {
                $table->text('conversation_summary')->nullable()->after('ebook_title');
            }
            if (!Schema::hasColumn('leads', 'preferred_date')) {
                $table->string('preferred_date', 50)->nullable()->after('conversation_summary');
            }
            if (!Schema::hasColumn('leads', 'preferred_time')) {
                $table->string('preferred_time', 30)->nullable()->after('preferred_date');
            }
            if (!Schema::hasColumn('leads', 'preferred_contact_method')) {
                $table->string('preferred_contact_method', 30)->nullable()->after('preferred_time');
            }
            if (!Schema::hasColumn('leads', 'referral_url')) {
                $table->string('referral_url', 500)->nullable()->after('user_agent');
            }
            if (!Schema::hasColumn('leads', 'form_name')) {
                $table->string('form_name', 100)->nullable()->after('form_type');
            }
            if (!Schema::hasColumn('leads', 'converted_at')) {
                $table->timestamp('converted_at')->nullable()->after('updated_at');
            }
            if (!Schema::hasColumn('leads', 'is_archived')) {
                $table->boolean('is_archived')->default(false)->after('converted_at');
            }
            if (!Schema::hasColumn('leads', 'last_activity_at')) {
                $table->timestamp('last_activity_at')->nullable()->after('is_archived');
            }
        });

        if (!Schema::hasTable('lead_notes')) {
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
        }

        if (!Schema::hasTable('lead_tasks')) {
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
        }

        if (!Schema::hasTable('lead_activities')) {
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
            });
        }

        if (!Schema::hasTable('lead_files')) {
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

        if (!Schema::hasTable('tags')) {
            Schema::create('tags', function (Blueprint $table) {
                $table->id();
                $table->string('name', 100);
                $table->string('slug', 100)->unique();
                $table->string('color', 20)->default('#6366f1');
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('lead_tag')) {
            Schema::create('lead_tag', function (Blueprint $table) {
                $table->foreignId('lead_id')->constrained()->cascadeOnDelete();
                $table->foreignId('tag_id')->constrained('tags')->cascadeOnDelete();
                $table->primary(['lead_id', 'tag_id']);
            });
        }
    }

    public function down(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            $columns = [
                'visa_type', 'landing_page_name', 'package_name', 'ebook_title',
                'conversation_summary', 'preferred_date', 'preferred_time',
                'preferred_contact_method', 'referral_url', 'form_name',
                'converted_at', 'is_archived', 'last_activity_at',
            ];
            foreach ($columns as $col) {
                if (Schema::hasColumn('leads', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
