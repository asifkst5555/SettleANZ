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
        'meta_title',
        'meta_description',
        'og_title',
        'og_description',
        'og_image',
        'canonical_url',
        'no_index',
        'schema_type',
        'focus_keyword',
        'secondary_keywords',
        'author_name',
        'author_url',
        'faq_items',
        'reading_time',
        'image',
        'image_class',
        'intro_content',
        'checks_content',
        'next_steps_content',
        'body_html',
        'is_published',
        'is_featured_home',
        'published_at',
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'is_published' => 'boolean',
        'is_featured_home' => 'boolean',
        'no_index' => 'boolean',
        'faq_items' => 'array',
    ];
}
