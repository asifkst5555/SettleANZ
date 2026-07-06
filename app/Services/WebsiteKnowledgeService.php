<?php

namespace App\Services;

use App\Models\AiKnowledgeEntry;
use App\Models\BlogPost;
use App\Models\DirectoryListing;
use App\Models\SiteSetting;
use App\Support\SiteDefaults;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class WebsiteKnowledgeService
{
    public function shouldPreferWebSearch(string $query, int $minKnowledgeScore = 6): bool
    {
        $normalized = Str::lower(trim($query));
        if ($normalized === '') {
            return false;
        }

        if ($this->containsRecencyIntent($normalized)) {
            return true;
        }

        if (!$this->looksLikeSiteOrRelocationIntent($normalized)) {
            return true;
        }

        return !$this->hasStrongKnowledgeMatch($normalized, $minKnowledgeScore);
    }

    public function hasStrongKnowledgeMatch(string $query, int $minScore = 6): bool
    {
        $top = $this->search($query, 1)->first();

        return (int) ($top['score'] ?? 0) >= $minScore;
    }

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

                if (($item['kind'] ?? '') === 'custom_knowledge' && $score > 0) {
                    $score += (int) ($item['priority'] ?? 0);
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
            'settlement', 'before arriving', 'pre-arrival', 'about', 'privacy', 'terms',
        ]);
    }

    private function containsRecencyIntent(string $query): bool
    {
        return Str::contains($query, [
            'latest', 'today', 'now', 'current', 'currently', 'new update', 'updated',
            'news', 'change', 'changes', 'policy', 'recent', 'this year', '2026', '2027',
            'visa update', 'immigration update', 'new law', 'new rules',
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
                'summary' => 'Homepage introduces SettleANZ as a practical relocation and settlement support platform for Australia and New Zealand. Features 4 journey personas (Future Migrant, International Student, Skilled Worker, New Arrival), 4 service stages (Arrive, Settle, Work & Invest, Enjoy), 7 testimonials, partner logos (Wise, SafetyWing, Booking.com, Cigna, OFX), 90-Day Roadmap lead form, and 3 featured blog posts.',
                'search' => 'home homepage settleanz starter support guide settling australia new zealand trusted partners latest posts support pathways expats helped journey personas future migrant international student skilled worker new arrival 90 day roadmap',
            ],
            [
                'kind' => 'primary_page',
                'title' => 'New to Australia Guide',
                'route' => '/new-to-australia',
                'summary' => 'Complete first-steps guide for new arrivals covering documents to carry physically (passport, visa grant letter, employment contract, bank statements, health insurance, passport photos), bookings to make before landing (short-term accommodation 4 weeks minimum, airport transfer, travel insurance), DOs and DON\'Ts, 7-step first week plan (SIM card -> TFN -> Bank Account -> Medicare -> Rental -> Address Registration -> Fund Transfer), and common FAQ. Includes Wise for transfers, Woolworths Mobile for prepaid SIM, Commonwealth Bank for pre-arrival account opening.',
                'search' => 'new to australia arrival guide checklist before arrival first week culture where to live banking finance healthcare working staying connected useful contacts expat migrant newcomer documents passport visa contract bank statements insurance photos sim card tfn medicare rental wise commonwealth bank woolworths',
            ],
            [
                'kind' => 'primary_page',
                'title' => 'New to New Zealand',
                'route' => '/new-to-new-zealand',
                'summary' => 'Guide for newcomers moving to New Zealand covering IRD number, KiwiSaver, healthcare system, rental market, and driver\'s license information.',
                'search' => 'new zealand guide newcomer ird number kiwisaver healthcare rental market driver license moving nz',
            ],
            [
                'kind' => 'primary_page',
                'title' => 'Settlement Services Overview',
                'route' => '/settlement-services',
                'summary' => 'Main hub for 4-stage settlement journey: Stage 1 Arrive (Pre-Arrival Strategy $99-$949, Airport Concierge $899-$1,299), Stage 2 Settle (90-Day Settlement Program $2,499), Stage 3 Work & Invest (Career Kickstart $1,499, Financial Foundations $1,999), Stage 4 Enjoy (Community & Lifestyle $1,499, Family & Education $2,599). Covers pre-arrival preparation, first-week tasks, and step-by-step settlement guidance.',
                'search' => 'settlement services what to do first family settlement plan prepare before you land hit the ground running new arrival settlement planning step by step guide pre-arrival first week tasks australia new zealand packages pricing arrive settle work invest enjoy',
            ],
            [
                'kind' => 'primary_page',
                'title' => 'Stage 1 Arrive — Pre-Arrival Strategy & Airport Concierge',
                'route' => '/settlement-services/arrive',
                'summary' => 'Stage 1 services: Package 1 Pre-Arrival Strategy & Document Review ($99-$949 AUD) including document readiness review, personalized 90-day action plan, vetted service referrals, 60-min video strategy call, written summary, community forum. Package 2 Airport Meet & Arrival Concierge ($899-$1,299 AUD) including airport meet & greet, SIM card & first cash, transport to accommodation, first-week WhatsApp concierge, neighborhood orientation, welcome pack.',
                'search' => 'arrive stage 1 pre-arrival strategy document review airport meet greet arrival concierge sim card transport accommodation welcome pack 90 day plan strategy call community forum pricing packages',
            ],
            [
                'kind' => 'primary_page',
                'title' => 'Stage 2 Settle — First 90 Days Program',
                'route' => '/settlement-services/settle',
                'summary' => 'Stage 2 services: 90-Day Settlement Program ($2,499 AUD) including tailored profile & suburb matching, guaranteed rental finding assistance, school placement support, banking & financial setup, community & cultural connections, 3 support calls over 90 days, vetted referral network access (GP, dentist, accountant, lawyer), member portal access.',
                'search' => 'settle stage 2 90 day settlement program rental finding suburb matching school placement banking setup community connections support calls referral network member portal pricing',
            ],
            [
                'kind' => 'primary_page',
                'title' => 'Stage 3 Work & Invest — Career & Financial Foundations',
                'route' => '/settlement-services/work-invest',
                'summary' => 'Stage 3 services: Package 4 Career Kickstart Program ($1,499 AUD) with local job market insights, resume & cover letter optimization, interview coaching, professional networking strategy, workplace culture briefing, TFN/super setup. Package 5 Financial Foundations Program ($1,999 AUD) with credit score setup guidance, tax & pension overview, property buying strategy, small business setup pathways, provider referral map, long-term money roadmap.',
                'search' => 'work invest stage 3 career kickstart resume optimization interview coaching networking workplace culture tfn super financial foundations credit score tax pension property buying small business money roadmap pricing',
            ],
            [
                'kind' => 'primary_page',
                'title' => 'Stage 4 Enjoy — Community & Family Support',
                'route' => '/settlement-services/enjoy',
                'summary' => 'Stage 4 services: Package 6 Community & Lifestyle Program ($1,499 AUD) with social & cultural connections, driving & transport guidance, healthcare navigation, volunteering, lifestyle orientation, citizenship eligibility map. Package 7 Family & Education Program ($2,599 AUD) with childcare & school consulting, pediatric healthcare mapping, spousal support & networks, children\'s cultural adaptation, family services referral map, long-term family planning.',
                'search' => 'enjoy stage 4 community lifestyle integration social connections driving transport healthcare volunteering citizenship family education school consulting pediatric spousal support children adaptation family planning pricing',
            ],
            [
                'kind' => 'primary_page',
                'title' => 'Migration Services — Registered Migration Agent',
                'route' => '/migration-services',
                'summary' => 'Migration services page featuring a registered migration agent. Services include skilled visas (189, 190, 491, 482, 186), partner and family visas, student visas, employer-sponsored visas, AAT/MRT tribunal reviews, NOICC responses, visa cancellations, health and character waivers, and ministerial interventions. Common pitfalls highlighted: wrong subclass, EOI errors, weak submissions.',
                'search' => 'migration services registered migration agent visa skilled partner student employer sponsored tribunal review NOICC cancellation health character waiver ministerial intervention consultation booking',
            ],
            [
                'kind' => 'primary_page',
                'title' => 'About SettleANZ',
                'route' => '/about',
                'summary' => 'Founder story: Entel Dajsmaili arrived 2001 with one suitcase, became Australian citizen 2004, made a promise that no one else should learn the system the hard way. Built from lived experience. Three pathways: free 90-day roadmap, free 15-min strategy call, or paid concierge services.',
                'search' => 'about settleanz mission story founder entel dajsmaili 2001 arrival 2004 citizenship lived experience roadmap strategy call concierge services',
            ],
            [
                'kind' => 'primary_page',
                'title' => 'Blog Index',
                'route' => '/blog',
                'summary' => 'Blog with practical guides for expats in Australia and New Zealand across Banking and Housing categories. Published articles include Best Banks for New Arrivals in Australia, How to Rent in Australia as a New Immigrant, How to Read an Australian Rental Listing. Search and filter by category.',
                'search' => 'blog practical guides honest advice real insights expats australia new zealand banking housing rent rental listings banks newcomers',
            ],
            [
                'kind' => 'primary_page',
                'title' => 'Directory',
                'route' => '/directory',
                'summary' => 'Curated directory of trusted expat services with search and filter by city (Brisbane, Melbourne, Sydney) and category (Inspection, Legal Services, Finance). Current listings: Property Inspectors Brisbane (4.9), Settlement Lawyers Melbourne (4.8), Finance Brokers Sydney (4.5). Businesses can apply for listing.',
                'search' => 'directory partners services property inspectors brisbane settlement lawyers melbourne finance brokers sydney inspection legal finance business listing apply',
            ],
            [
                'kind' => 'primary_page',
                'title' => 'Book a Consultation',
                'route' => '/consultation',
                'summary' => 'Consultation booking page where users can schedule a visa consultation with a registered migration agent. Form captures first name, last name, email, phone, visa type interest, and situation details. Urgent cases (deadline under 7 days, NOICC, overstaying) are flagged for urgent review.',
                'search' => 'book consultation schedule visa consultation booking form urgent NOICC deadline overstaying',
            ],
            [
                'kind' => 'primary_page',
                'title' => 'Contact SettleANZ',
                'route' => '/contact',
                'summary' => 'Contact page with address (P.O. Box 446, Gungahlin ACT 2912), phone +61 416 874 058, email ' . ($settings['contact_email'] ?? 'hello@settleanz.com') . ', WhatsApp chat, and contact form with subject options (General Enquiry, Business Listing, Partnership, Media). Response within 24 hours.',
                'search' => 'contact email whatsapp phone address support business listing partnership media enquiry hello settleanz general enquiry contact form',
            ],
            [
                'kind' => 'primary_page',
                'title' => 'Privacy Policy',
                'route' => '/privacy-policy',
                'summary' => 'Privacy policy explains how SettleANZ collects, uses, stores, and protects visitor personal data including contact details, journey stage, service bookings, directory applications, usage data, and cookies. Data retention up to 24 months. Users can access, correct, or request deletion.',
                'search' => 'privacy policy data protection personal information collection storage cookies data rights retention deletion security third party links',
            ],
            [
                'kind' => 'primary_page',
                'title' => 'Terms of Service',
                'route' => '/terms-of-service',
                'summary' => 'Terms of service outlining that SettleANZ is NOT a registered migration agent (no visa advice). Provides informational content only, not professional advice. Service packages, cancellation and refunds policy, third-party partner referrals, directory listings, intellectual property, user conduct rules, limitation of liability.',
                'search' => 'terms of service legal terms conditions disclaimer liability content accuracy website usage rules not migration agent refund cancellation booking directory listing intellectual property',
            ],
            [
                'kind' => 'primary_page',
                'title' => 'Business Listing Application',
                'route' => (string) ($settings['directory_apply_link'] ?? '/contact'),
                'summary' => 'Businesses can apply for directory inclusion through the contact page. Subject "Business Listing" option available in contact form.',
                'search' => 'business listing directory apply partner business promote service submit listing apply link contact form',
            ],
            [
                'kind' => 'system',
                'title' => 'SettleANZ Platform Capabilities',
                'route' => '',
                'summary' => 'SettleANZ combines guides, blog content, directory listings, 7 paid service packages ($99-$2,599 AUD), contact support, business listing applications, WhatsApp contact, and AI assistant to help newcomers settle in Australia and New Zealand in 90 days.',
                'search' => 'platform system whole website capabilities guides blog directory listings contact support business listing whatsapp ai assistant packages pricing 90 days',
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
            [
                'kind' => 'faq',
                'title' => 'FAQ: What is the Settlement Services page?',
                'route' => '/settlement-services',
                'summary' => 'The Settlement Services page is the central hub for new arrivals. It helps with what to do first, hitting the ground running, family settlement planning, and pre-arrival preparation. It covers banking, housing, healthcare, TFN, superannuation, and step-by-step settlement guidance.',
                'search' => 'settlement services page what is it central hub new arrivals what to do first family settlement pre-arrival preparation step by step guidance',
            ],
            [
                'kind' => 'faq',
                'title' => 'FAQ: What is SettleANZ?',
                'route' => '/about',
                'summary' => 'SettleANZ is a practical relocation and migration support platform for people settling in Australia and New Zealand. It combines guides, blog content, directory listings, AI assistant, and human contact support to help newcomers navigate settlement tasks confidently.',
                'search' => 'what is settleanz platform mission purpose help newcomers australia new zealand relocation migration support',
            ],
            [
                'kind' => 'faq',
                'title' => 'FAQ: Is the AI assistant accurate?',
                'route' => '/',
                'summary' => 'The AI assistant is trained on SettleANZ website content and can use web search for current information. It provides practical guidance but should not replace professional migration or legal advice. For regulated advice, consult a registered migration agent or lawyer.',
                'search' => 'ai assistant accurate reliable trained web search professional advice migration agent lawyer disclaimer',
            ],
            [
                'kind' => 'faq',
                'title' => 'FAQ: What should I do before arriving in Australia?',
                'route' => '/settlement-services',
                'summary' => 'Before arriving, research suburbs, understand visa conditions, prepare documents, arrange initial accommodation, research banking options, understand healthcare coverage, and plan your first-week tasks. The Settlement Services page and New to Australia guide have detailed checklists.',
                'search' => 'before arriving pre-arrival preparation checklist documents visa conditions initial accommodation banking healthcare first week tasks',
            ],
            [
                'kind' => 'faq',
                'title' => 'FAQ: How do I find housing in Australia?',
                'route' => '/settlement-services',
                'summary' => 'SettleANZ helps with rental finding, suburb selection, and lease guidance through the Settlement Services program. The Directory page lists relocation companies and real estate agents. Key steps include researching suburbs, understanding rental applications, and avoiding common pitfalls.',
                'search' => 'find housing rent apartment lease suburb relocation real estate agent rental application common mistakes housing guide',
            ],
            [
                'kind' => 'faq',
                'title' => 'FAQ: How do I open a bank account in Australia?',
                'route' => '/settlement-services',
                'summary' => 'SettleANZ helps with banking setup, newcomer-friendly accounts, money transfers, TFN, and superannuation. Popular options include CommBank, Westpac, and transfer tools like Wise, OFX, and WorldRemit. Some banks allow pre-arrival account setup.',
                'search' => 'open bank account commbank westpac tfn superannuation money transfer wise ofx worldremit pre-arrival account setup banking guide',
            ],
            [
                'kind' => 'faq',
                'title' => 'FAQ: What visa options are available?',
                'route' => '/migration-services',
                'summary' => 'Common visa pathways include skilled migration, student visas, partner visas, and working holiday visas. The Migration Services page explains visa types, the importance of registered migration agents, and how to request a consultation.',
                'search' => 'visa options skilled migration student partner working holiday registered migration agent consultation visa types',
            ],
            [
                'kind' => 'faq',
                'title' => 'FAQ: Why do people migrate to Australia?',
                'route' => '/new-to-australia',
                'summary' => 'People migrate to Australia for higher incomes, better quality of life, free healthcare (Medicare), excellent weather, work-life balance, rule of law, education, and career opportunities. Many come to pay off family debts, escape poor weather, or seek a peaceful life. The currency advantage (1 AUD = ~45-70 INR) makes earning in Australia highly valuable for supporting families abroad.',
                'search' => 'why migrate australia reasons move higher income quality life healthcare weather work-life balance education career opportunities currency advantage family debt peaceful life',
            ],
            [
                'kind' => 'faq',
                'title' => 'FAQ: What are the real experiences of migrants in Australia?',
                'route' => '/new-to-australia',
                'summary' => 'Real migrant stories show both positives and challenges. Positives: free Medicare healthcare, higher salaries (IT contractors earn $70-120/hour), better work-life balance, cleaner air, quality food standards, rule of law, easier business setup, government benefits, solar panel rebates, tenant protection via bond authorities. Challenges: first job may be at a lower level than in home country, need for local experience, occasional racism, tall poppy syndrome (humility expected), English fluency matters for career progression, and introverts may struggle with networking.',
                'search' => 'real migrant experiences stories positives challenges healthcare salary work-life balance local experience racism tall poppy syndrome english fluency networking first job',
            ],
            [
                'kind' => 'faq',
                'title' => 'FAQ: What challenges do new immigrants face in Australia?',
                'route' => '/new-to-australia',
                'summary' => 'New immigrants face intense homesickness and isolation, missing family deeply. Job hunting is a major hurdle without local experience - employers want local experience but no one gives the first chance. Social isolation is common due to cultural differences and language barriers. Loneliness triggers anxiety and depression. Affordable housing and transport mismatches add stress, with some commuting an hour with multiple train changes. Many struggle to find housing and employment due to lack of local networks.',
                'search' => 'challenges new immigrants homesickness isolation job hunt local experience social isolation language barriers anxiety depression housing transport commute local networks',
            ],
            [
                'kind' => 'faq',
                'title' => 'FAQ: What are the key tips for settling successfully in Australia?',
                'route' => '/new-to-australia',
                'summary' => 'Key tips: assimilate and explore the country, make effort to talk to people, don\'t only hang around with your own countrymen, work on English language skills and emotional intelligence, be polite and take the high road, understand dignity of labor (all jobs are respected), be humble (tall poppy syndrome is real), be social and form workplace relationships, embrace optimism and constructive criticism, and understand that Australia values meritocracy, low corruption, mutual respect, and environmental protection.',
                'search' => 'tips settling australia assimilate explore english language emotional intelligence polite humility tall poppy syndrome social relationships meritocracy respect',
            ],
            [
                'kind' => 'faq',
                'title' => 'FAQ: What are the pros and cons of living in Australia compared to India?',
                'route' => '/new-to-australia',
                'summary' => 'Pros: free healthcare for PR/citizens, nicer weather (less humid), freedom of speech/religion/press, higher incomes for same roles, better work-life balance, much less pollution, quality food/grocery standards, high-quality meat, ease of doing business, government benefits, negative gearing for property, lower stamp duty for first home buyers, roadworthy certificate system for used cars, tenant-landlord tribunal system, proper town planning with parks and greenery. Cons: tough to get first job at same seniority level, low tolerance for haughtiness/entitlement, occasional racism, lower career progression for introverts or those with weaker English.',
                'search' => 'pros cons australia vs india healthcare weather freedom speech income work-life balance pollution food standards business doing property tenant rights town planning job seniority racism career progression',
            ],
            [
                'kind' => 'faq',
                'title' => 'FAQ: What is the cost of living and salary expectation in Australia?',
                'route' => '/new-to-australia',
                'summary' => 'Australia has high living costs, especially in Sydney and Melbourne for housing. Median wages are high compared to many OECD countries. Minimum wage is around $20.33/hour. IT contractors earn $70-120/hour. Tradespeople (plumbers, electricians) earn $200-500 for a few hours. After-tax income is roughly 60% of gross (40% tax on high incomes). Superannuation (retirement contribution) adds ~10-12% to total compensation. Regional areas are considerably cheaper than major cities. You need a decent-paying job to enjoy Australia comfortably.',
                'search' => 'cost living salary expectation minimum wage it contractor tradesperson tax superannuation regional cheaper sydney melbourne expensive decent job',
            ],
            [
                'kind' => 'faq',
                'title' => 'FAQ: What is Australian culture and lifestyle like?',
                'route' => '/about',
                'summary' => 'Australia is multicultural with no rigid class structure. Australians value mateship, fairness, independence, and work-life balance. The laid-back lifestyle includes parties, get-togethers, and family picnics. Sports are a national fixation (cricket, soccer, rugby, tennis, netball). Coffee culture is strong with excellent cafes everywhere. Food is diverse with cuisines from around the world. Over 500 national parks protect natural heritage. 36,000+ km coastline with abundant beaches. Locals are friendly and expect basic politeness and greetings.',
                'search' => 'australian culture lifestyle multicultural mateship fairness work-life balance sports coffee culture food diverse national parks beaches friendly polite greetings',
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
            ->concat($this->customKnowledgeEntries())
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

    private function customKnowledgeEntries(): Collection
    {
        if (!Schema::hasTable('ai_knowledge_entries')) {
            return collect();
        }

        return AiKnowledgeEntry::query()
            ->active()
            ->byPriority()
            ->get()
            ->map(function (AiKnowledgeEntry $entry): array {
                $keywords = $entry->search_keywords ?? '';
                $search = implode(' ', array_filter([
                    $entry->title,
                    $entry->content,
                    $entry->category,
                    $keywords,
                ]));

                return [
                    'kind' => 'custom_knowledge',
                    'title' => 'AI Training: ' . $entry->title,
                    'route' => '',
                    'summary' => $entry->content,
                    'search' => $search,
                    'priority' => $entry->priority,
                ];
            });
    }
}

