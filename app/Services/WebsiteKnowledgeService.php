<?php

namespace App\Services;

use App\Models\BlogPost;
use App\Models\DirectoryListing;
use App\Models\SiteSetting;
use App\Support\SiteDefaults;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class WebsiteKnowledgeService
{
    public function buildAssistantContext(string $query, int $limit = 10): string
    {
        $matches = $this->search($query, $limit);

        $lines = [
            'SettleANZ website overview:',
            '- SettleANZ helps people settling in Australia and New Zealand with migration guidance, housing help, banking guidance, healthcare guidance, relocation checklists, blog guides, trusted directory listings, and direct contact pathways.',
            '- Core public sections include Home (/), New to Australia (/new-to-australia), Housing (/housing), Banking (/banking), Migration Services (/migration-services), Blog (/blog), Directory (/directory), and Contact (/contact).',
            '- Businesses can apply to be featured through the directory apply link. Human support is available by email or WhatsApp from the Contact page.',
        ];

        if ($matches->isEmpty()) {
            $lines[] = '- No highly relevant page match was found for this exact question, so answer carefully, stay practical, and only direct the visitor to Contact when truly needed.';
            return implode("\n", $lines);
        }

        $lines[] = 'Relevant SettleANZ website knowledge:';

        foreach ($matches as $item) {
            $route = $item['route'] !== '' ? ' [' . $item['route'] . ']' : '';
            $lines[] = '- ' . $item['title'] . $route . ': ' . $item['summary'];
        }

        return implode("\n", $lines);
    }

    public function groundedFallbackReply(string $query): ?string
    {
        if (!$this->looksLikeSiteOrRelocationIntent($query)) {
            return null;
        }

        $matches = $this->search($query, 3);
        $top = $matches->first();

        if (!$top || ($top['score'] ?? 0) < 4) {
            return null;
        }

        $reply = 'A useful place to start is ' . $top['title'] . '. ' . $top['summary'];

        if (!empty($top['route'])) {
            $reply .= ' You can find it on ' . $top['route'] . '.';
        }

        $second = $matches->skip(1)->first();
        if ($second && ($second['score'] ?? 0) >= 4 && $second['route'] !== $top['route']) {
            $reply .= ' If you want a second helpful page, look at ' . $second['title'] . ' on ' . $second['route'] . '.';
        }

        return $reply;
    }

    public function search(string $query, int $limit = 10): Collection
    {
        $tokens = collect(preg_split('/[^a-z0-9]+/i', Str::lower($query)) ?: [])
            ->filter(fn ($token) => strlen($token) > 2)
            ->reject(fn ($token) => in_array($token, $this->stopWords(), true))
            ->unique()
            ->values();

        if ($tokens->isEmpty()) {
            return collect();
        }

        return $this->knowledgeItems()
            ->map(function (array $item) use ($tokens): array {
                $haystack = Str::lower($item['search']);
                $title = Str::lower($item['title']);
                $score = 0;

                foreach ($tokens as $token) {
                    if (Str::contains($title, $token)) {
                        $score += 5;
                    }

                    if (Str::contains($haystack, $token)) {
                        $score += 2;
                    }
                }

                if (($item['kind'] ?? '') === 'primary_page' && $score > 0) {
                    $score += 2;
                }

                if (($item['kind'] ?? '') === 'faq' && $score > 0) {
                    $score += 3;
                }

                $item['score'] = $score;

                return $item;
            })
            ->filter(fn (array $item) => ($item['score'] ?? 0) > 0)
            ->sortByDesc('score')
            ->values()
            ->take($limit);
    }

    private function looksLikeSiteOrRelocationIntent(string $query): bool
    {
        $text = Str::lower($query);

        return Str::contains($text, [
            'australia', 'new zealand', 'settleanz', 'visa', 'migration', 'housing', 'rent', 'suburb',
            'bank', 'banking', 'transfer', 'health', 'insurance', 'medicare', 'directory', 'contact',
            'moving', 'relocation', 'guide', 'checklist', 'blog', 'expat', 'migrant', 'new arrival',
            'school', 'work', 'job', 'tfn', 'super', 'partner', 'whatsapp', 'consultation', 'listing',
            'services', 'support', 'healthcare', 'student visa', 'working holiday', 'partner visa',
            'email', 'apply', 'business', 'agent', 'lawyer', 'directory', 'article', 'post', 'category',
        ]);
    }

    private function stopWords(): array
    {
        return [
            'what', 'whats', 'your', 'name', 'who', 'are', 'you', 'tell', 'me', 'about',
            'please', 'need', 'help', 'with', 'this', 'that', 'from', 'into', 'want',
            'have', 'just', 'like', 'some', 'more', 'than', 'when', 'where', 'which',
            'could', 'would', 'should', 'there', 'their', 'them', 'then', 'also', 'very',
            'does', 'work', 'works', 'give', 'know', 'show', 'make', 'find', 'best',
        ];
    }

    private function knowledgeItems(): Collection
    {
        $settings = Schema::hasTable('site_settings') ? SiteSetting::keyValueMap() : SiteDefaults::siteSettings();
        $blogPosts = $this->blogPosts();
        $directoryListings = $this->directoryListings();

        $primaryPages = collect([
            [
                'kind' => 'primary_page',
                'title' => 'Homepage',
                'route' => '/',
                'summary' => 'Homepage introduces SettleANZ as a practical relocation and migration support platform for Australia and New Zealand with guides, trusted partners, latest blog posts, and support pathways.',
                'search' => 'home homepage settleanz starter support guide settling australia new zealand trusted partners latest posts support pathways expats helped',
            ],
            [
                'kind' => 'primary_page',
                'title' => 'New to Australia Guide',
                'route' => '/new-to-australia',
                'summary' => 'Checklist-led support covering before arrival, first week admin, culture, where to live, banking and finance, healthcare, working in Australia, staying connected, and useful contacts.',
                'search' => 'new to australia arrival guide checklist before arrival first week culture where to live banking finance healthcare working staying connected useful contacts expat migrant newcomer',
            ],
            [
                'kind' => 'primary_page',
                'title' => 'Housing Guide',
                'route' => '/housing',
                'summary' => 'Practical housing help for rentals, suburbs, furnished stays, leases, common mistakes, and relocation partners such as Harbour Move Co., Anchor Relocation, and Southern Cross Settling.',
                'search' => 'housing guide rentals suburbs lease apartment home short-term stay relocation accommodation rent suburb move harbour move anchor relocation southern cross settling',
            ],
            [
                'kind' => 'primary_page',
                'title' => 'Banking Guide',
                'route' => '/banking',
                'summary' => 'Banking guidance covering account setup, transfers, tax file numbers, superannuation, newcomer-friendly banks, and transfer tools like Wise, OFX, and WorldRemit.',
                'search' => 'banking guide bank account transfer wise ofx worldremit airwallex commbank westpac tfn tax file number superannuation fees money transfer',
            ],
            [
                'kind' => 'primary_page',
                'title' => 'Migration Services',
                'route' => '/migration-services',
                'summary' => 'Migration page connects visitors with immigration lawyers and visa support, including common pathways like skilled migrant, student, partner, and working holiday visas.',
                'search' => 'migration services visa migration pr residency skilled partner student working holiday immigration lawyer registered agent visa eligibility consultation',
            ],
            [
                'kind' => 'primary_page',
                'title' => 'Blog Index',
                'route' => '/blog',
                'summary' => 'Blog page contains practical guides, honest advice, and real insights for expats in Australia and New Zealand across banking, housing, moving, healthcare, working, and lifestyle.',
                'search' => 'blog practical guides honest advice real insights expats australia new zealand banking housing moving healthcare working lifestyle',
            ],
            [
                'kind' => 'primary_page',
                'title' => 'Directory',
                'route' => '/directory',
                'summary' => 'Directory page lists curated expat-friendly services across migration, relocation, schools, finance, real estate, healthcare, banking, and insurance with city and category filters.',
                'search' => 'directory partners services immigration relocation finance healthcare schools real estate insurance banking category city filters curated expat-friendly businesses',
            ],
            [
                'kind' => 'primary_page',
                'title' => 'Contact SettleANZ',
                'route' => '/contact',
                'summary' => 'Contact page handles general enquiries, business listings, partnerships, and media enquiries by email at ' . ($settings['contact_email'] ?? 'hello@settleanz.com') . ' or WhatsApp.',
                'search' => 'contact email whatsapp support business listing partnership media enquiry hello settleanz general enquiry contact us',
            ],
            [
                'kind' => 'primary_page',
                'title' => 'Business Listing CTA',
                'route' => (string) ($settings['directory_apply_link'] ?? '/contact'),
                'summary' => 'Businesses can apply for directory inclusion through the configured listing link and contact flow.',
                'search' => 'business listing directory apply partner business promote service submit listing apply link',
            ],
            [
                'kind' => 'system',
                'title' => 'SettleANZ Platform Capabilities',
                'route' => '',
                'summary' => 'SettleANZ combines guides, blog content, directory listings, contact support, business listing applications, WhatsApp contact, and an AI assistant to help newcomers navigate settlement tasks.',
                'search' => 'platform system whole website capabilities guides blog directory listings contact support business listing whatsapp ai assistant whole system',
            ],
        ]);

        $faqItems = collect([
            [
                'kind' => 'faq',
                'title' => 'FAQ: What does SettleANZ help with?',
                'route' => '/',
                'summary' => 'SettleANZ helps with migration guidance, housing, banking, healthcare, relocation planning, expat blog guidance, directory discovery, and human contact support for Australia and New Zealand.',
                'search' => 'what does settleanz help with overall whole website services support migration housing banking healthcare relocation blog directory contact',
            ],
            [
                'kind' => 'faq',
                'title' => 'FAQ: How should visitors use the site?',
                'route' => '/new-to-australia',
                'summary' => 'A good visitor flow is to start with New to Australia for checklists, then go to Housing, Banking, Migration Services, Blog, Directory, or Contact depending on the next need.',
                'search' => 'how use site where start visitor flow new to australia checklist housing banking migration services blog directory contact',
            ],
            [
                'kind' => 'faq',
                'title' => 'FAQ: When should visitors contact a human?',
                'route' => '/contact',
                'summary' => 'Visitors should use Contact for direct support, business listings, partnerships, media enquiries, or when they need human follow-up by email or WhatsApp.',
                'search' => 'when contact human support email whatsapp direct support business listing partnership media enquiry follow-up',
            ],
            [
                'kind' => 'faq',
                'title' => 'FAQ: How do business listings work?',
                'route' => (string) ($settings['directory_apply_link'] ?? '/contact'),
                'summary' => 'Businesses can use the configured directory apply link or contact pathway to start a directory listing enquiry for inclusion in the SettleANZ directory.',
                'search' => 'business listing apply directory how does it work directory apply link featured listing promote services',
            ],
            [
                'kind' => 'faq',
                'title' => 'FAQ: What is on the migration page?',
                'route' => '/migration-services',
                'summary' => 'The migration page explains common visa types, why registered migration agents matter, featured agents, and consultation request forms for visa support.',
                'search' => 'migration page visa types registered migration agent featured migration agents consultation request forms skilled student partner working holiday',
            ],
            [
                'kind' => 'faq',
                'title' => 'FAQ: What is in the directory?',
                'route' => '/directory',
                'summary' => 'The directory contains curated settlement-related services including immigration lawyers, relocation companies, financial advisors, healthcare, international schools, and real estate agents, with city filters.',
                'search' => 'what is in directory directory categories immigration lawyers relocation companies financial advisors healthcare international schools real estate agents city filters',
            ],
            [
                'kind' => 'faq',
                'title' => 'FAQ: What topics are covered in the blog?',
                'route' => '/blog',
                'summary' => 'The blog covers banking, housing, moving, healthcare, working, and lifestyle topics with practical guidance for expats and newcomers.',
                'search' => 'blog topics banking housing moving healthcare working lifestyle practical guides expats newcomers',
            ],
            [
                'kind' => 'faq',
                'title' => 'FAQ: What do the main guides cover?',
                'route' => '/new-to-australia',
                'summary' => 'The main guides cover arrival checklists, housing setup, banking setup, migration pathways, money transfers, SIM and mobile setup, tax basics, schools, and early healthcare planning.',
                'search' => 'main guides cover arrival checklist housing banking migration money transfers sim mobile tax schools healthcare',
            ],
            [
                'kind' => 'faq',
                'title' => 'FAQ: How fast does SettleANZ respond?',
                'route' => '/contact',
                'summary' => (string) ($settings['contact_response_time'] ?? 'We usually respond within 24 hours.'),
                'search' => 'response time reply how fast respond contact response time 24 hours',
            ],
            [
                'kind' => 'faq',
                'title' => 'FAQ: How can someone book migration help?',
                'route' => '/migration-services',
                'summary' => 'Visitors can use the migration services page to review visa types, choose a featured migration agent, request a consultation, or submit their situation through the migration form.',
                'search' => 'book migration help consultation visa support featured migration agent request consultation migration form',
            ],
        ]);

        $blogCategorySummary = collect([
            [
                'kind' => 'system',
                'title' => 'Blog Categories',
                'route' => '/blog',
                'summary' => 'Blog topics include ' . $blogPosts->pluck('category')->filter()->unique()->sort()->values()->implode(', ') . '.',
                'search' => 'blog categories ' . $blogPosts->pluck('category')->filter()->unique()->implode(' '),
            ],
        ]);

        $directoryCategorySummary = collect([
            [
                'kind' => 'system',
                'title' => 'Directory Categories and Cities',
                'route' => '/directory',
                'summary' => 'Directory categories include ' . $directoryListings->pluck('category')->filter()->unique()->sort()->values()->implode(', ') . '. Cities include ' . $directoryListings->pluck('city')->filter()->unique()->sort()->values()->implode(', ') . '.',
                'search' => 'directory categories cities ' . $directoryListings->pluck('category')->filter()->unique()->implode(' ') . ' ' . $directoryListings->pluck('city')->filter()->unique()->implode(' '),
            ],
        ]);

        $blogItems = $blogPosts->map(function ($post): array {
            return [
                'kind' => 'blog_post',
                'title' => 'Blog: ' . $post->title,
                'route' => '/blog/' . $post->slug,
                'summary' => trim(implode(' ', array_filter([
                    $post->excerpt ?? '',
                    $post->intro_content ?? '',
                    $post->checks_content ?? '',
                    $post->next_steps_content ?? '',
                ]))),
                'search' => implode(' ', array_filter([
                    $post->title ?? '',
                    $post->category ?? '',
                    $post->excerpt ?? '',
                    $post->intro_content ?? '',
                    $post->checks_content ?? '',
                    $post->next_steps_content ?? '',
                    $post->author_name ?? '',
                ])),
            ];
        });

        $directoryItems = $directoryListings->map(function ($listing): array {
            $services = is_array($listing->services ?? null) ? implode(', ', $listing->services) : '';

            return [
                'kind' => 'directory_listing',
                'title' => 'Directory Listing: ' . $listing->name,
                'route' => '/directory/' . $listing->slug,
                'summary' => trim(implode(' ', array_filter([
                    ($listing->category ?? '') . ' in ' . ($listing->city ?? ''),
                    $listing->description ?? '',
                    $services,
                ]))),
                'search' => implode(' ', array_filter([
                    $listing->name ?? '',
                    $listing->category ?? '',
                    $listing->city ?? '',
                    $listing->description ?? '',
                    $listing->full_description ?? '',
                    $services,
                    $listing->phone ?? '',
                    $listing->email ?? '',
                ])),
            ];
        });

        return $primaryPages
            ->concat($faqItems)
            ->concat($blogCategorySummary)
            ->concat($directoryCategorySummary)
            ->concat($blogItems)
            ->concat($directoryItems)
            ->values();
    }

    private function blogPosts(): Collection
    {
        if (Schema::hasTable('blog_posts')) {
            return BlogPost::query()->where('is_published', true)->latest('published_at')->take(20)->get();
        }

        return collect(SiteDefaults::blogPosts())
            ->map(fn (array $post) => (object) $post)
            ->sortByDesc('published_at')
            ->values()
            ->take(20);
    }

    private function directoryListings(): Collection
    {
        if (Schema::hasTable('directory_listings')) {
            return DirectoryListing::query()->where('is_published', true)->orderByDesc('featured')->orderBy('name')->take(30)->get();
        }

        return collect(SiteDefaults::directoryListings())
            ->map(fn (array $listing) => (object) $listing)
            ->sortByDesc('featured')
            ->values()
            ->take(30);
    }
}

