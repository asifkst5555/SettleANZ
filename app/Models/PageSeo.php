<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PageSeo extends Model
{
    protected $table = 'page_seo';

    protected $fillable = [
        'page_key',
        'page_label',
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
    ];

    protected $casts = [
        'no_index' => 'boolean',
    ];

    public static function forPage(string $pageKey): ?static
    {
        return static::query()->where('page_key', $pageKey)->first();
    }

    public static function pages(): array
    {
        return [
            'home'                => ['label' => 'Home', 'url' => '/', 'default_title' => 'SettleANZ | Move To Australia With Confidence', 'default_description' => 'Practical migration guides, relocation support, trusted partners, and useful resources for people starting a new life in Australia and New Zealand.'],
            'new-to-australia'    => ['label' => 'New to Australia', 'url' => '/new-to-australia', 'default_title' => 'New to Australia Guide 2026 | SettleANZ', 'default_description' => 'Your complete guide to settling in Australia. Housing, banking, Medicare, schools, and community — step by step for new arrivals.'],
            'settlement-services' => ['label' => 'Settlement Services', 'url' => '/settlement-services', 'default_title' => 'Settlement Services for Expats & New Arrivals in Australia | SettleANZ', 'default_description' => 'From airport pickup to your first rental, get real settlement support for new arrivals in Australia with personalised help at every stage.'],
            'blog'                => ['label' => 'Blog', 'url' => '/blog', 'default_title' => 'The SettleANZ Blog | Practical Guides for Expats', 'default_description' => 'Practical guides, honest advice, and real insights for expats in Australia and New Zealand.'],
            'about'               => ['label' => 'About', 'url' => '/about', 'default_title' => "About SettleANZ | Entel's Story and Mission", 'default_description' => "Read Entel's journey of settling in Australia and learn why SettleANZ was built to help new arrivals avoid expensive mistakes and settle with confidence."],
            'contact'             => ['label' => 'Contact', 'url' => '/contact', 'default_title' => 'Contact SettleANZ | Get In Touch', 'default_description' => 'Contact SettleANZ for general enquiries, business listings, partnerships, media, or relocation support.'],
            'directory'           => ['label' => 'Directory', 'url' => '/directory', 'default_title' => 'Expat Services Directory Australia | SettleANZ', 'default_description' => 'Find trusted immigration lawyers, relocation companies, financial advisors, and more. Every listing is vetted for expats in Australia.'],
            'privacy-policy'      => ['label' => 'Privacy Policy', 'url' => '/privacy-policy', 'default_title' => 'Privacy Policy | SettleANZ', 'default_description' => 'How SettleANZ collects, uses, and protects your personal information when using our migration and settlement services.'],
            'terms-of-service'    => ['label' => 'Terms of Service', 'url' => '/terms-of-service', 'default_title' => 'Terms of Service | SettleANZ', 'default_description' => 'Terms and conditions for using SettleANZ settlement services, guides, and directory.'],
        ];
    }

    public function resolvedTitle(): string
    {
        return $this->meta_title ?: (static::pages()[$this->page_key]['default_title'] ?? $this->page_label);
    }

    public function resolvedDescription(): string
    {
        return $this->meta_description ?: (static::pages()[$this->page_key]['default_description'] ?? '');
    }
}
