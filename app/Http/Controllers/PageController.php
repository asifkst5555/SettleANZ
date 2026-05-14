<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use App\Models\DirectoryListing;
use App\Models\PageSeo;
use App\Models\SiteSetting;
use App\Support\SiteDefaults;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class PageController extends Controller
{
    public function home(): View
    {
        $latestPosts = $this->blogPosts()->take(3);

        return view('home', array_merge($this->seo('home', 'SettleANZ | Move To Australia With Confidence', 'Practical migration guides, relocation support, trusted partners, and useful resources for people starting a new life in Australia and New Zealand.'), [
            'latestPosts' => $latestPosts,
            'heroStats' => [
                ['value' => '10,000+', 'label' => 'Expats helped'],
                ['value' => 'Trusted', 'label' => 'Partner network'],
                ['value' => 'Expert-reviewed', 'label' => 'Content and guides'],
            ],
            'partnerLogos' => ['Wise', 'SafetyWing', 'Booking.com', 'Cigna Global', 'OFX'],
            'settings' => $this->settings(),
        ]));
    }

    public function migrationServices(): View
    {
        $agents = $this->directoryListings()
            ->where('category', 'Immigration Lawyers')
            ->map(fn ($agent) => (array) $agent->toArray())
            ->merge(
                collect(SiteDefaults::directoryListings())
                    ->where('category', 'Immigration Lawyers')
                    ->all(),
            )
            ->map(function (array $agent): object {
                $agent['id'] = $agent['id'] ?? $agent['slug'];

                return (object) $agent;
            })
            ->unique('slug')
            ->sortByDesc('featured')
            ->values()
            ->take(5);

        return view('guides.migration-services', array_merge($this->seo('migration-services', 'Australian Visa Help for Expats and Migrants | SettleANZ', 'Connect with registered migration agents, learn common visa pathways, and request a free visa eligibility check.'), [
            'agents' => $agents,
            'settings' => $this->settings(),
            'visaTypes' => [
                'Skilled migrant visas',
                'Student visas',
                'Partner visas',
                'Working holiday visas',
            ],
        ]));
    }

    public function settlementServices(): View
    {
        return view('guides.settlement-services', array_merge($this->seo('settlement-services', 'Settlement Services for Expats & New Arrivals in Australia | SettleANZ', 'From airport pickup to your first rental, get real settlement support for new arrivals in Australia with personalised help at every stage.'), [
            'packages' => [
                [
                    'number' => '01',
                    'stage' => 'Pre-arrival',
                    'headline' => 'Settle Fast — Pre-Arrival Strategy & Document Review',
                    'tagline' => 'Be prepared for your new homeland before you land.',
                    'image' => 'media/services/Pre-arrival.webp',
                    'summary' => 'Before you land, know exactly what to do, in what order, with your specific documents reviewed by someone who has done it. No guesswork. No costly mistakes.',
                    'starting_from' => '$79 AUD',
                    'pricing' => [
                        'Async plan $79 AUD',
                        'Strategy call $148 AUD',
                        'Full pre-arrival package $279 AUD',
                    ],
                    'includes_heading' => 'What\'s included',
                    'timeline' => [
                        [
                            'title' => 'Document readiness review',
                            'description' => 'Passport, visa, qualifications, references — what you have, what you\'re missing, what matters',
                        ],
                        [
                            'title' => 'Personalised first-90-days action plan',
                            'description' => 'Step by step, in order, matched to your visa, city, and family situation',
                        ],
                        [
                            'title' => 'Vetted service referrals — pre-matched',
                            'description' => 'Accountant, migration agent, school, GP — selected for your background and language needs',
                        ],
                        [
                            'title' => '60-minute video strategy call',
                            'description' => 'Live session with Entel — ask everything, leave with clarity',
                        ],
                        [
                            'title' => 'Written summary delivered within 48hrs',
                            'description' => 'Everything from the call in writing — your permanent reference document',
                        ],
                    ],
                    'items' => [
                        'Document readiness review for passports, visas, qualifications, and references.',
                        'Personalised first-90-days action plan matched to your visa, city, and family situation.',
                        'Vetted service referrals pre-matched to your background and language needs.',
                        '60-minute video strategy call with Entel for live Q&A and clarity.',
                        'Written summary delivered within 48 hours for permanent reference.',
                    ],
                ],
                [
                    'number' => '02',
                    'stage' => 'Arrival day & first weeks',
                    'headline' => 'Welcome to Australia — Airport Meet & Arrival Concierge',
                    'tagline' => 'Step off the plane without feeling lost.',
                    'image' => 'media/services/Arrival day & first weeks.webp',
                    'summary' => 'From the moment you land to your first week, someone is there so you are not figuring everything out alone while jet-lagged.',
                    'starting_from' => '$179 AUD',
                    'pricing' => [
                        'Meet & greet $179 AUD',
                        'Full arrival day $299 AUD',
                        'First week concierge $499 AUD',
                    ],
                    'includes_heading' => 'What\'s included',
                    'timeline' => [
                        [
                            'title' => 'Airport arrivals meet and greet',
                            'description' => 'Met at the gate by name. A familiar face when everything else is unfamiliar.',
                        ],
                        [
                            'title' => 'SIM card and first cash sorted',
                            'description' => 'Best local SIM for your needs set up before you leave the airport',
                        ],
                        [
                            'title' => 'Transport to accommodation arranged',
                            'description' => 'Accompanied to your first address — no Uber confusion when you\'re jet-lagged',
                        ],
                        [
                            'title' => 'First-week trusted concierge access',
                            'description' => 'WhatsApp access to Entel for the first 7 days — quick answers when you need them',
                        ],
                        [
                            'title' => 'Neighbourhood orientation briefing',
                            'description' => 'Where to shop, which GP bulk bills, nearest transport — the things Google won\'t tell you',
                        ],
                    ],
                    'items' => [
                        'Airport arrivals meet and greet, including a familiar face at the gate.',
                        'SIM card setup and first cash guidance before leaving the airport.',
                        'Transport arranged and supported to your first accommodation.',
                        'WhatsApp concierge access for the first 7 days.',
                        'Neighbourhood orientation with practical local advice you will actually use.',
                    ],
                ],
                [
                    'number' => '03',
                    'stage' => 'Month 1–3',
                    'headline' => 'Settle In — Your First 90 Days, Fully Supported',
                    'tagline' => 'Your new country sorted for you.',
                    'image' => 'media/services/Month 1–3.webp',
                    'summary' => 'Housing, schools, banking, community, and healthcare support matched to who you are, where you are from, and how you want to live.',
                    'starting_from' => '$749 AUD',
                    'starting_from_meta' => '90-day package',
                    'pricing' => [
                        'Individual $749 AUD',
                        'Family $949 AUD',
                        'Premium with property search $1,399 AUD',
                    ],
                    'includes_heading' => 'What\'s included',
                    'timeline' => [
                        [
                            'title' => 'Rental finding — on the ground',
                            'description' => 'Entel or vetted local attends inspections, sends walkthroughs, submits applications on your behalf',
                        ],
                        [
                            'title' => 'School placement — culturally matched',
                            'description' => 'Not just ratings — which school has your community, speaks your language, fits your children',
                        ],
                        [
                            'title' => 'Banking and financial setup',
                            'description' => 'TFN, bank account, superannuation — in the right order, with the right providers',
                        ],
                        [
                            'title' => 'Community and cultural connections',
                            'description' => 'Introductions to your community group, cultural centre, mosque, temple, or church — wherever you belong',
                        ],
                        [
                            'title' => 'Three support calls over 90 days',
                            'description' => 'Week 1 kickoff · Day 30 check-in · Day 90 review and next steps',
                        ],
                        [
                            'title' => 'Full vetted referral network access',
                            'description' => 'GP, dentist, accountant, lawyer, gynaecologist — matched to your language, background, and location',
                        ],
                    ],
                    'items' => [
                        'Rental finding support with inspections, walkthroughs, and application help.',
                        'School placement guidance based on community fit, language, and family needs.',
                        'Banking and financial setup in the right order, including TFN, bank account, and super.',
                        'Introductions to relevant community, cultural, or faith networks.',
                        'Three support calls across 90 days plus access to a vetted referral network.',
                    ],
                ],
            ],
            'faqs' => [
                [
                    'question' => 'What does a settlement concierge in Australia do?',
                    'answer' => 'A settlement concierge guides new arrivals through every practical step of settling in Australia — from pre-arrival document preparation to finding a rental, setting up a bank account, getting a Tax File Number, connecting with schools, and building community. At SettleANZ, the concierge service is run by Entel Dajsmaili, who went through the full migration and settlement journey himself in 2001. He knows what the system actually looks like from the inside not just what the government websites say.',
                ],
                [
                    'question' => 'What is included in the airport meet and greet service?',
                    'answer' => 'From the moment you land, someone is there with your name. We sort your SIM card before you leave the airport, arrange your transport to your first address, and give you a neighbourhood briefing, where to shop, which GP bulk bills, how to get around. For the first 7 days, you have direct WhatsApp access to Entel for any question that comes up. It is the difference between landing in a new country feeling lost and landing feeling like someone is in your corner from day one.',
                ],
                [
                    'question' => 'How does SettleANZ help with finding a rental in Australia?',
                    'answer' => 'Finding a rental in Australia is hard when you have no local rental history or references — which is the situation every new arrival faces. SettleANZ handles the search on the ground. Entel or a vetted local attends inspections on your behalf, sends you walkthroughs, and submits applications with supporting documentation that gives you the best chance of being approved. We also match you to suburbs that fit your commute, budget, community, and family needs — not just whatever comes up on realestate.com.au.',
                ],
                [
                    'question' => 'How much does it cost to settle in Australia as an expat?',
                    'answer' => 'In Sydney or Melbourne, expect to spend $3,000 to $6,000 AUD in your first 90 days, covering bond and rent in advance (typically 4 to 6 weeks upfront), furniture and household basics, SIM and utilities, groceries and transport, and professional services like accountants or school applications. Regional cities like Brisbane, Adelaide, and Perth are 20 to 30 percent cheaper. SettleANZ provides a personalised cost breakdown as part of the Pre-Arrival Strategy package, matched to your city and family situation.',
                ],
            ],
        ]));
    }

    public function contact(): View
    {
        return view('contact', array_merge($this->seo('contact', 'Contact SettleANZ | Get In Touch', 'Contact SettleANZ for general enquiries, business listings, partnerships, media, or relocation support.'), [
            'contactSubjects' => ['General Enquiry', 'Business Listing', 'Partnership', 'Media'],
            'settings' => $this->settings(),
        ]));
    }

    public function about(): View
    {
        return view('about', array_merge($this->seo('about', "About SettleANZ | Entel's Story and Mission", "Read Entel's journey of settling in Australia and learn why SettleANZ was built to help new arrivals avoid expensive mistakes and settle with confidence."), [
            'settings' => $this->settings(),
        ]));
    }

    protected function blogPosts(): Collection
    {
        if (Schema::hasTable('blog_posts')) {
            return BlogPost::query()->where('is_published', true)->orderByDesc('published_at')->get();
        }

        return collect(SiteDefaults::blogPosts())
            ->map(fn (array $post) => (object) $post)
            ->sortByDesc('published_at')
            ->values();
    }

    protected function directoryListings(): Collection
    {
        if (Schema::hasTable('directory_listings')) {
            return DirectoryListing::query()->where('is_published', true)->orderByDesc('featured')->orderBy('name')->get();
        }

        return collect(SiteDefaults::directoryListings())
            ->map(fn (array $listing) => (object) $listing)
            ->values();
    }

    protected function settings(): array
    {
        return Schema::hasTable('site_settings') ? SiteSetting::keyValueMap() : SiteDefaults::siteSettings();
    }

    protected function seo(string $pageKey, string $defaultTitle, string $defaultDescription): array
    {
        if (!Schema::hasTable('page_seo')) {
            return [
                'metaTitle'       => $defaultTitle,
                'metaDescription' => $defaultDescription,
            ];
        }

        $record = PageSeo::forPage($pageKey);

        return array_filter([
            'metaTitle'       => $record?->meta_title       ?: $defaultTitle,
            'metaDescription' => $record?->meta_description ?: $defaultDescription,
            'metaOgTitle'     => $record?->og_title         ?: null,
            'metaOgDesc'      => $record?->og_description   ?: null,
            'metaOgImage'     => $record?->og_image         ?: null,
            'metaCanonical'   => $record?->canonical_url    ?: null,
            'metaNoIndex'     => $record?->no_index         ?? false,
            'metaSchemaType'  => $record?->schema_type      ?: null,
        ], fn ($v) => $v !== null && $v !== '' && $v !== false);
    }
}
