<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'is_admin',
        'is_active',
        'is_suspended',
        'last_login_at',
        'suspended_at',
        'suspension_reason',
        'locked_until',
        'impersonated_at',
        'impersonated_by',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_admin' => 'boolean',
            'is_active' => 'boolean',
            'is_suspended' => 'boolean',
            'last_login_at' => 'datetime',
            'suspended_at' => 'datetime',
            'locked_until' => 'datetime',
            'impersonated_at' => 'datetime',
        ];
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'user_roles')
            ->withTimestamps();
    }

    public function loginHistory()
    {
        return $this->hasMany(LoginHistory::class);
    }

    public function activityLogs()
    {
        return $this->hasMany(ActivityLog::class);
    }

    public function impersonatedBy()
    {
        return $this->belongsTo(User::class, 'impersonated_by');
    }

    public function isSuperAdmin(): bool
    {
        if ($this->is_admin) {
            return true;
        }
        return $this->roles()->where('is_super', true)->exists();
    }

    public function hasRole(string $slug): bool
    {
        return $this->roles()->where('slug', $slug)->exists();
    }

    public function hasAnyRole(array $slugs): bool
    {
        return $this->roles()->whereIn('slug', $slugs)->exists();
    }

    public function hasPermission(string $permissionSlug): bool
    {
        if ($this->isSuperAdmin()) {
            return true;
        }

        return $this->roles()
            ->where('is_active', true)
            ->whereHas('permissions', function ($query) use ($permissionSlug) {
                $query->where('slug', $permissionSlug)
                    ->where('role_permissions.is_allowed', true);
            })->exists();
    }

    public function hasAnyPermission(array $permissionSlugs): bool
    {
        if ($this->isSuperAdmin()) {
            return true;
        }

        return $this->roles()
            ->where('is_active', true)
            ->whereHas('permissions', function ($query) use ($permissionSlugs) {
                $query->whereIn('slug', $permissionSlugs)
                    ->where('role_permissions.is_allowed', true);
            })->exists();
    }

    public function getAllPermissions(): array
    {
        if ($this->isSuperAdmin()) {
            return Permission::pluck('slug')->toArray();
        }

        return $this->roles()
            ->where('is_active', true)
            ->with(['permissions' => function ($query) {
                $query->where('role_permissions.is_allowed', true);
            }])
            ->get()
            ->pluck('permissions.*.slug')
            ->flatten()
            ->unique()
            ->values()
            ->toArray();
    }

    public function isSuspended(): bool
    {
        return $this->is_suspended || $this->is_active === false;
    }

    public function isLocked(): bool
    {
        return $this->locked_until && $this->locked_until->isFuture();
    }
}
