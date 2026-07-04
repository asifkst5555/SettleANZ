@extends('layouts.app')

@php
    $service = [
        'stage_number' => '04',
        'stage_label' => 'Stage 4 - Enjoy',
        'modal_stage' => 'Stage 04. Enjoy: Embrace Your New Lifestyle',
        'hero_title' => 'Embrace Your <span class="service-stage-hero__accent">New Lifestyle</span>',
        'hero_copy' => 'You have done the hard part. With the essentials in place, our Enjoy services help you integrate, explore and make the most of your new life.',
        'hero_image' => 'media/services/services_new/enjoy_hero.webp',
        'hero_image_alt' => 'Family enjoying their new lifestyle',
        'chips' => [
            ['label' => 'Community & Lifestyle', 'href' => '#community-lifestyle'],
            ['label' => 'Family & Education', 'href' => '#family-education'],
        ],
        'packages' => [
            [
                'id' => 'community-lifestyle',
                'number' => '6',
                'eyebrow' => 'Package 6',
                'title' => 'Community & Lifestyle Integration',
                'tagline' => 'Connect, explore and truly feel at home.',
                'pricing_intro' => 'For newcomers ready to move beyond setup into belonging.',
                'cta' => 'Discover Your Community',
                'modal_price' => '$1,499 AUD',
                'note' => 'Community matching is tailored to your city, interests and background.',
                'included' => [
                    ['icon' => 'users',   'title' => 'Social & Cultural Connections', 'desc' => 'Introductions to local clubs, cultural associations and social groups matching your interests.'],
                    ['icon' => 'car',     'title' => 'Driving & Public Transport', 'desc' => 'Guidance on license exchange, transport cards, route planning and local car ownership basics.'],
                    ['icon' => 'heart',   'title' => 'Healthcare Navigation', 'desc' => 'A practical walkthrough of Medicare or NZ health systems, clinics and private family cover options.'],
                    ['icon' => 'smile',   'title' => 'Volunteering and Local Activities', 'desc' => 'Find meaningful ways to participate locally and build routine outside work and home.'],
                    ['icon' => 'compass', 'title' => 'Lifestyle Orientation', 'desc' => 'Local parks, libraries, recreation, weekend travel ideas and practical everyday-life tips.'],
                    ['icon' => 'shield',  'title' => 'Citizenship Eligibility Map', 'desc' => 'Understand residency timelines, character criteria and long-term pathway checkpoints.'],
                ],
                'pricing' => [
                    ['title' => 'Community & Lifestyle Program', 'desc' => 'Connections, lifestyle orientation, driving and pathway guidance', 'price' => '$1,499 AUD', 'featured' => true],
                ],
            ],
            [
                'id' => 'family-education',
                'number' => '7',
                'eyebrow' => 'Package 7',
                'title' => 'Family & Education Support',
                'tagline' => 'Ensure your family thrives in their new environment.',
                'pricing_intro' => 'Built for families who need education, childcare and household integration support.',
                'cta' => "Support Your Family's Transition",
                'modal_price' => '$2,599 AUD',
                'note' => 'Family support is shaped around each child and household need.',
                'included' => [
                    ['icon' => 'book',     'title' => 'Childcare & School Consulting', 'desc' => 'Navigate education structures, childcare options, enrollment workflows and school communication.'],
                    ['icon' => 'heart',    'title' => 'Pediatric Healthcare Mapping', 'desc' => 'Find GP, pediatric and specialist care options close to home and suitable for your family.'],
                    ['icon' => 'users',    'title' => 'Spousal Support & Networks', 'desc' => 'Partner integration, employment direction, social introductions and practical confidence building.'],
                    ['icon' => 'smile',    'title' => 'Children Cultural Adaptation', 'desc' => 'Support children through routine, school expectations, friendship building and belonging.'],
                    ['icon' => 'map',      'title' => 'Family Services Referral Map', 'desc' => 'Vetted referrals for tutors, childcare, activities, doctors and family support services.'],
                    ['icon' => 'calendar', 'title' => 'Long-Term Family Planning', 'desc' => 'A roadmap for school years, community participation, residency checkpoints and future goals.'],
                ],
                'pricing' => [
                    ['title' => 'Family & Education Program', 'desc' => 'School, childcare, healthcare and family integration support', 'price' => '$2,599 AUD', 'badge' => 'Family', 'featured' => true],
                ],
            ],
        ],
        'stats' => [
            ['value' => '99%', 'label' => 'Family Integration'],
            ['value' => '94%', 'label' => 'School Satisfaction'],
            ['value' => 'Life-Long', 'label' => 'Community Connections'],
            ['value' => '2', 'label' => 'Lifestyle Support Paths'],
        ],
        'next' => [
            'title' => 'Want to review the full settlement journey?',
            'copy' => 'Compare all four SettleANZ service stages and choose the support that matches where you are right now.',
            'href' => '/settlement-services',
            'label' => 'Explore All Services',
        ],
    ];
@endphp

@include('guides.services.partials.service-detail')
