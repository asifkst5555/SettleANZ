<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Role extends Model
{
    use SoftDeletes;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'name',
        'slug',
        'description',
        'is_super',
        'is_default',
        'is_active',
        'color',
        'icon',
        'landing_page',
        'priority',
    ];

    protected function casts(): array
    {
        return [
            'is_super' => 'boolean',
            'is_default' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    protected static function boot(): void
    {
        parent::boot();
        static::creating(function ($model): void {
            if (empty($model->id)) {
                $model->id = (string) Str::uuid();
            }
        });
    }

    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'role_permissions')
            ->withPivot('is_allowed')
            ->withTimestamps();
    }

    public function allowedPermissions()
    {
        return $this->belongsToMany(Permission::class, 'role_permissions')
            ->withPivot('is_allowed')
            ->withTimestamps()
            ->wherePivot('is_allowed', true);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_roles')
            ->withTimestamps();
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeSuper($query)
    {
        return $query->where('is_super', true);
    }

    public function scopeNotSuper($query)
    {
        return $query->where('is_super', false);
    }
}
