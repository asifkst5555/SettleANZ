<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlogPost extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'category',
        'excerpt',
        'author_name',
        'reading_time',
        'image',
        'image_class',
        'intro_content',
        'checks_content',
        'next_steps_content',
        'is_published',
        'is_featured_home',
        'published_at',
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'is_published' => 'boolean',
        'is_featured_home' => 'boolean',
    ];
}
