<?php

namespace App\Providers;

use App\Models\Campaign;
use App\Models\DownloadToken;
use App\Models\Ebook;
use App\Models\EmailTemplate;
use App\Models\Role;
use App\Models\User;
use App\Policies\CampaignPolicy;
use App\Policies\DownloadTokenPolicy;
use App\Policies\EbookPolicy;
use App\Policies\EmailTemplatePolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Ebook::class => EbookPolicy::class,
        DownloadToken::class => DownloadTokenPolicy::class,
        EmailTemplate::class => EmailTemplatePolicy::class,
        Campaign::class => CampaignPolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();

        Gate::before(function (User $user, string $ability) {
            if ($user->isSuperAdmin()) {
                return true;
            }

            if ($user->isSuspended()) {
                return false;
            }
        });

        Gate::define('admin.access', function (User $user): bool {
            return $user->isSuperAdmin() || $user->roles()->exists();
        });

        Gate::define('admin.settings', function (User $user): bool {
            return $user->hasPermission('settings.view');
        });

        Gate::define('admin.users', function (User $user): bool {
            return $user->hasPermission('user_management.view');
        });

        Gate::define('admin.roles', function (User $user): bool {
            return $user->hasPermission('roles_management.view');
        });

        Gate::define('admin.permissions', function (User $user): bool {
            return $user->hasPermission('permissions_management.view');
        });

        Gate::define('admin.activity-logs', function (User $user): bool {
            return $user->hasPermission('activity_logs.view');
        });

        Gate::define('admin.impersonate', function (User $user): bool {
            return $user->hasPermission('user_management.impersonate');
        });

        // Lead Center permissions (used by @can in Blade)
        Gate::define('lead_center.view', fn(User $user): bool => $user->hasPermission('lead_center.view'));
        Gate::define('lead_center.create', fn(User $user): bool => $user->hasPermission('lead_center.create'));
        Gate::define('lead_center.edit', fn(User $user): bool => $user->hasPermission('lead_center.edit'));
        Gate::define('lead_center.delete', fn(User $user): bool => $user->hasPermission('lead_center.delete'));
        Gate::define('lead_center.export', fn(User $user): bool => $user->hasPermission('lead_center.export'));
    }
}
