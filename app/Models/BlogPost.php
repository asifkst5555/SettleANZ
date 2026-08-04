<?php

namespace App\Models;

use App\Support\BlogMedia;
use Illuminate\Database\Eloquent\Casts\Attribute;
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

    protected static function booted(): void
    {
        static::deleting(function (BlogPost $post) {
            if ($post->image) {
                BlogMedia::delete($post->image);
            }
        });

        static::updating(function (BlogPost $post) {
            if ($post->isDirty('image')) {
                $oldImage = $post->getOriginal('image');
                if ($oldImage && $oldImage !== $post->image) {
                    BlogMedia::delete($oldImage);
                }
            }
        });
    }

    protected function imageUrl(): Attribute
    {
        return Attribute::get(fn (): ?string => BlogMedia::url($this->image));
    }

    protected function imagePath(): Attribute
    {
        return Attribute::get(fn (): ?string => BlogMedia::path($this->image));
    }

    protected function hasImageFile(): Attribute
    {
        return Attribute::get(fn (): bool => BlogMedia::exists($this->image));
    }
}
