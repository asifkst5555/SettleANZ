<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class LoginHistory extends Model
{
    public $incrementing = false;
    protected $keyType = 'string';
    const UPDATED_AT = null;

    protected $table = 'login_history';

    protected $fillable = [
        'user_id',
        'event',
        'ip_address',
        'user_agent',
        'browser',
        'platform',
        'device',
        'location',
    ];

    protected static function boot(): void
    {
        parent::boot();
        static::creating(function ($model): void {
            if (empty($model->id)) {
                $model->id = (string) Str::uuid();
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
