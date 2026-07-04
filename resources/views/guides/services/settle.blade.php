@extends('layouts.app')

@php
    $service = [
        'stage_number' => '02',
        'stage_label' => 'Stage 2 - Settle',
        'modal_stage' => 'Stage 02. Settle: Establish Your Foundation',
        'hero_title' => 'Establish Your <span class="service-stage-hero__accent">Foundation</span>',
        'hero_copy' => 'Once you have arrived, our Settle services help you establish your new life: finding a home, setting up essential services, choosing schools and building local confidence.',
        'hero_image' => 'media/services/Month 1–3.webp',
        'hero_image_alt' => 'Family settling into a new home',
        'chips' => [
            ['label' => 'Rental Assistance', 'href' => '#first-90-days'],
            ['label' => 'School & Banking Setup', 'href' => '#first-90-days'],
        ],
        'packages' => [
            [
                'id' => 'first-90-days',
                'number' => '3',
                'eyebrow' => 'Package 3',
                'title' => 'Your First 90 Days, Fully Supported',
                'tagline' => 'Your new country sorted for you.',
                'pricing_intro' => 'A guided settlement program for the first critical months.',
                'cta' => 'Start Your 90-Day Settlement',
                'modal_price' => '$2,499 AUD',
                'note' => 'Concierge onboarding begins after your booking confirmation.',
                'included' => [
                    ['icon' => 'target',      'title' => 'Tailored Profile & Suburb Matching', 'desc' => 'We align your suburbs, budget, commute, school preferences and lifestyle needs before the search begins.'],
                    ['icon' => 'home',        'title' => 'Guaranteed Rental Finding Assistance', 'desc' => 'Inspection support, video walkthroughs, application preparation and local rental strategy to help you secure a home faster.'],
                    ['icon' => 'book',        'title' => 'School Placement Support', 'desc' => 'School options matched to your children, commute, community preferences and enrollment requirements.'],
                    ['icon' => 'credit-card', 'title' => 'Banking and Financial Setup', 'desc' => 'Bank account, TFN, superannuation and essential financial setup handled in a practical sequence.'],
                    ['icon' => 'users',       'title' => 'Community and Cultural Connections', 'desc' => 'Introductions to community groups, cultural centers, professional networks and places of belonging.'],
                    ['icon' => 'phone',       'title' => 'Three Support Calls over 90 Days', 'desc' => 'Week 1 kickoff, Day 30 check-in and Day 90 review to keep your settlement moving.'],
                    ['icon' => 'network',     'title' => 'Vetted Referral Network Access', 'desc' => 'GP, dentist, accountant, lawyer and specialist referrals matched to location, language and background where possible.'],
                    ['icon' => 'key',         'title' => 'Member Portal Access', 'desc' => 'Access to private SettleANZ resources, checklists and practical newcomer guides.'],
                ],
                'pricing' => [
                    ['title' => '90-Day Settlement Program', 'desc' => 'Rental, school, banking, referrals and concierge guidance', 'price' => '$2,499 AUD', 'badge' => 'Flagship', 'featured' => true],
                ],
            ],
        ],
        'stats' => [
            ['value' => '98%', 'label' => 'Rental Finding Success'],
            ['value' => '100+', 'label' => 'Families Settled'],
            ['value' => '30 Days', 'label' => 'Average Placement Time'],
            ['value' => '90', 'label' => 'Days of Guidance'],
        ],
        'next' => [
            'heading'    => 'Ready for Stage 3?',
            'subheading' => 'Build your career and finances.',
            'copy'       => 'Once your foundations are in place, Work & Invest helps you navigate the job market, local workplace expectations and financial decisions.',
            'href'       => '/settlement-services/work-invest',
            'label'      => 'Explore Work & Invest Services',
        ],
    ];
@endphp

@include('guides.services.partials.service-detail')
