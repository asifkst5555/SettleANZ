@extends('layouts.app')

@php
    $service = [
        'stage_number' => '01',
        'stage_label' => 'Stage 1 - Arrive',
        'modal_stage' => 'Stage 01. Arrive: Your Smooth Start in a New Land',
        'hero_title' => 'Your Smooth Start <span class="service-stage-hero__accent">in a New Land</span>',
        'hero_copy' => "From meticulous planning before you leave to a warm welcome upon arrival, our Arrive services ensure your first moments in Australia or New Zealand are stress-free, hassle-free, and organized.",
        'hero_image' => 'media/services/Pre-arrival.webp',
        'hero_image_alt' => 'New arrivals walking through an airport',
        'chips' => [
            ['label' => 'Pre-Arrival Strategy', 'href' => '#pre-arrival'],
            ['label' => 'Airport Concierge', 'href' => '#arrival-concierge'],
        ],
        'packages' => [
            [
                'id' => 'pre-arrival',
                'number' => '1',
                'eyebrow' => 'Package 1',
                'title' => 'Pre-Arrival Strategy & Document Review',
                'tagline' => 'Be prepared for your new homeland before you land.',
                'pricing_intro' => 'Start where you are. Upgrade anytime.',
                'cta' => 'Choose Pre-Arrival Support',
                'modal_price' => '$99 AUD',
                'note' => 'No commitment required. Cancel anytime.',
                'included' => [
                    ['icon' => 'document',  'title' => 'Document Readiness Review', 'desc' => 'Passport, visa, qualifications and references reviewed so you know what is ready, what is missing and what matters most.'],
                    ['icon' => 'clipboard', 'title' => 'Personalized First-90-Days Action Plan', 'desc' => 'A step-by-step guide tailored to your visa, destination city, family situation and first-month priorities.'],
                    ['icon' => 'users',     'title' => 'Vetted Service Referrals', 'desc' => 'Trusted accountants, schools, GPs and other providers selected for your background, language needs and location.'],
                    ['icon' => 'video',     'title' => '60-Minute Video Strategy Call', 'desc' => 'A live session with the SettleANZ team to answer your practical arrival questions before you travel.'],
                    ['icon' => 'pencil',    'title' => 'Written Summary within 48 Hours', 'desc' => 'Everything from your call, captured in writing as a permanent reference document.'],
                    ['icon' => 'chat',      'title' => 'Community Forum Access', 'desc' => 'Connect with other pre-arrival newcomers, ask questions and get peer support before you land.'],
                ],
                'pricing' => [
                    ['title' => 'Self-Guided Starter', 'desc' => 'Essential templates, checklists and community forum', 'price' => '$99 AUD'],
                    ['title' => 'Expert Strategy Call', 'desc' => '1-hour video consultation plus written summary', 'price' => '$220 AUD'],
                    ['title' => 'Strategy & Document Review', 'desc' => 'Document assessment plus personalized 90-day plan', 'price' => '$499 AUD', 'badge' => 'Popular', 'featured' => true],
                    ['title' => 'Comprehensive Pre-Arrival', 'desc' => 'All-inclusive pre-arrival support package', 'price' => '$949 AUD'],
                ],
            ],
            [
                'id' => 'arrival-concierge',
                'number' => '2',
                'eyebrow' => 'Package 2',
                'title' => 'Airport Meet & Arrival Concierge',
                'tagline' => 'Step off the plane without feeling lost.',
                'pricing_intro' => 'Both include airport pickup and SIM setup.',
                'cta' => 'Book Your Arrival Concierge',
                'modal_price' => '$899 AUD',
                'note' => 'Bookings confirmed within 24 hours.',
                'included' => [
                    ['icon' => 'star',     'title' => 'Airport Meet & Greet', 'desc' => 'Met at the gate by name, with a familiar face ready when everything else feels unfamiliar.'],
                    ['icon' => 'phone',    'title' => 'SIM Card & First Cash Sorted', 'desc' => 'The right local SIM and first practical money options arranged before you leave the terminal.'],
                    ['icon' => 'car',      'title' => 'Transport to Accommodation', 'desc' => 'Accompanied by a local to your first address, avoiding confusing transport decisions while jet-lagged.'],
                    ['icon' => 'phone',    'title' => 'First-Week Concierge Access', 'desc' => 'WhatsApp access to the SettleANZ team for quick answers during your first seven days.'],
                    ['icon' => 'location', 'title' => 'Neighborhood Orientation Briefing', 'desc' => 'Where to shop, nearby transport, GP options and essential local context that search engines miss.'],
                    ['icon' => 'gift',     'title' => 'Welcome Pack', 'desc' => 'Local essentials and emergency contacts ready for your first day.'],
                ],
                'pricing' => [
                    ['title' => 'Basic Meet & Greet', 'desc' => 'Airport pickup, SIM setup and transport to accommodation', 'price' => '$899 AUD'],
                    ['title' => 'Full Arrival Concierge', 'desc' => 'Basic plus 7-day WhatsApp support, orientation and welcome pack', 'price' => '$1,299 AUD', 'badge' => 'Best value', 'featured' => true],
                ],
            ],
        ],
        'stats' => [
            ['value' => '500+', 'label' => 'Newcomers Welcomed'],
            ['value' => '100%', 'label' => 'On-Time Arrivals'],
            ['value' => '4.9/5', 'label' => 'Client Satisfaction'],
            ['value' => '24/7', 'label' => 'Arrival Day Support'],
        ],
        'next' => [
            'heading'    => 'Ready for Stage 2?',
            'subheading' => 'Settle into your new home.',
            'copy'       => "Once you have arrived, the real work begins: finding a home, setting up banking and enrolling children in school. Our Settle services handle all of it.",
            'href'       => '/settlement-services/settle',
            'label'      => 'Explore Settle Services',
        ],
    ];
@endphp

@include('guides.services.partials.service-detail')
