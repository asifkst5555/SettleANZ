<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}
