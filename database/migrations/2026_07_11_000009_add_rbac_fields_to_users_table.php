<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('is_active')->default(true)->after('is_admin');
            $table->boolean('is_suspended')->default(false)->after('is_active');
            $table->timestamp('last_login_at')->nullable()->after('is_suspended');
            $table->timestamp('suspended_at')->nullable()->after('last_login_at');
            $table->text('suspension_reason')->nullable()->after('suspended_at');
            $table->timestamp('locked_until')->nullable()->after('suspension_reason');
            $table->timestamp('impersonated_at')->nullable()->after('locked_until');
            $table->foreignId('impersonated_by')->nullable()->constrained('users')->after('impersonated_at');

            $table->index('is_active');
            $table->index('is_suspended');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['impersonated_by']);
            $table->dropColumn([
                'is_active',
                'is_suspended',
                'last_login_at',
                'suspended_at',
                'suspension_reason',
                'locked_until',
                'impersonated_at',
                'impersonated_by',
            ]);
        });
    }
};
