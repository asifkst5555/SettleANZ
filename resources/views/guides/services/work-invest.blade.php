@extends('layouts.app')

@php
    $service = [
        'stage_number' => '03',
        'stage_label' => 'Stage 3 - Work & Invest',
        'modal_stage' => 'Stage 03. Work & Invest: Build Your Future',
        'hero_title' => 'Build Your <span class="service-stage-hero__accent">Future</span>',
        'hero_copy' => 'Beyond settling in, our Work & Invest services help you thrive professionally and financially in Australia or New Zealand.',
        'hero_image' => 'media/services/services_new/work-invest_hero.webp',
        'hero_image_alt' => 'Professional newcomer planning career and finances',
        'chips' => [
            ['label' => 'Career Kickstart', 'href' => '#career-kickstart'],
            ['label' => 'Financial Foundations', 'href' => '#financial-foundations'],
        ],
        'packages' => [
            [
                'id' => 'career-kickstart',
                'number' => '4',
                'eyebrow' => 'Package 4',
                'title' => 'Career Kickstart & Professional Integration',
                'tagline' => 'Unlock your career potential in your new country.',
                'pricing_intro' => 'Designed for newcomers who want a local-ready career plan.',
                'cta' => 'Advance Your Career',
                'modal_price' => '$1,499 AUD',
                'note' => 'Career support is tailored to your industry and target role.',
                'included' => [
                    ['icon' => 'trending',  'title' => 'Local Job Market Insights', 'desc' => 'Understand hiring expectations, industry demand, salary context and where your experience fits.'],
                    ['icon' => 'pencil',    'title' => 'Resume & Cover Letter Optimization', 'desc' => 'Localize your CV, cover letter and LinkedIn profile for Australian or New Zealand recruiter expectations.'],
                    ['icon' => 'chat',      'title' => 'Interview Coaching', 'desc' => 'Prepare for local interview styles, behavioral questions and workplace communication norms.'],
                    ['icon' => 'network',   'title' => 'Professional Networking Strategy', 'desc' => 'Guidance on building useful industry relationships and improving your professional visibility.'],
                    ['icon' => 'briefcase', 'title' => 'Workplace Culture Briefing', 'desc' => 'Navigate local workplace dynamics, etiquette and communication with confidence.'],
                    ['icon' => 'dollar',    'title' => 'TFN/IRD & Superannuation Setup', 'desc' => 'Practical help understanding tax identifiers, employment setup and pension foundations.'],
                ],
                'pricing' => [
                    ['title' => 'Career Kickstart Program', 'desc' => 'Resume localization, interview coaching and professional integration', 'price' => '$1,499 AUD', 'featured' => true],
                ],
            ],
            [
                'id' => 'financial-foundations',
                'number' => '5',
                'eyebrow' => 'Package 5',
                'title' => 'Financial Foundations & Investment Guidance',
                'tagline' => 'Secure your financial future in Australia or New Zealand.',
                'pricing_intro' => 'For newcomers ready to understand local money systems and long-term planning.',
                'cta' => 'Build Your Wealth',
                'modal_price' => '$1,999 AUD',
                'note' => 'We provide practical guidance and referrals, not regulated financial advice.',
                'included' => [
                    ['icon' => 'award',    'title' => 'Credit Score Setup Guidance', 'desc' => 'Understand how local credit history works and how to start building it responsibly.'],
                    ['icon' => 'document', 'title' => 'Tax and Pension Overview', 'desc' => 'A practical walkthrough of tax structures, superannuation or KiwiSaver basics and record keeping.'],
                    ['icon' => 'home',     'title' => 'Property Buying Strategy', 'desc' => 'Learn the buying process, borrowing readiness and common newcomer mistakes before you invest.'],
                    ['icon' => 'office',   'title' => 'Small Business Setup Pathways', 'desc' => 'Understand local registration, compliance and professional referral options if you plan to operate a business.'],
                    ['icon' => 'map',      'title' => 'Provider Referral Map', 'desc' => 'Introductions to accountants, mortgage brokers and other specialists when you need regulated advice.'],
                    ['icon' => 'trending', 'title' => 'Long-Term Money Roadmap', 'desc' => 'A clear sequence for bank accounts, savings, credit, tax, protection and future investment conversations.'],
                ],
                'pricing' => [
                    ['title' => 'Financial Foundations Program', 'desc' => 'Credit, tax, pension, property and referral guidance', 'price' => '$1,999 AUD', 'badge' => 'Advanced', 'featured' => true],
                ],
            ],
        ],
        'stats' => [
            ['value' => '85%', 'label' => 'Job Placement Rate'],
            ['value' => '6 Weeks', 'label' => 'Average Time To Offer'],
            ['value' => 'Top-Tier', 'label' => 'Referral Partners'],
            ['value' => '2', 'label' => 'Career & Finance Paths'],
        ],
        'next' => [
            'heading'    => 'Ready for Stage 4?',
            'subheading' => 'Enjoy the life you are building.',
            'copy'       => 'After work and finances start taking shape, Enjoy helps you deepen belonging through community, family, lifestyle and long-term pathways.',
            'href'       => '/settlement-services/enjoy',
            'label'      => 'Explore Enjoy Services',
        ],
    ];
@endphp

@include('guides.services.partials.service-detail')
