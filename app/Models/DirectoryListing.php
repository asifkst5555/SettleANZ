<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DirectoryListing extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'category',
        'city',
        'rating',
        'featured',
        'description',
        'full_description',
        'services',
        'phone',
        'email',
        'website',
        'whatsapp',
        'booking_url',
        'logo',
        'is_published',
    ];

    protected $casts = [
        'services' => 'array',
        'featured' => 'boolean',
        'is_published' => 'boolean',
        'rating' => 'decimal:1',
    ];

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function approvedReviews(): HasMany
    {
        return $this->hasMany(Review::class)->where('status', 'approved');
    }
}
