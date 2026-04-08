<?php
require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();
$html = view('home', [
    'metaTitle' => 'SettleANZ | Move To Australia With Confidence',
    'metaDescription' => 'Practical migration guides, relocation support, trusted partners, and useful resources for people starting a new life in Australia and New Zealand.',
    'navItems' => [
        ['label' => 'Why SettleANZ', 'href' => '#why-settleanz'],
        ['label' => 'Services', 'href' => '#services'],
        ['label' => 'Guides', 'href' => '#guides'],
        ['label' => 'Partners', 'href' => '#partners'],
        ['label' => 'Contact', 'href' => '#lead-strip'],
    ],
    'heroStats' => [
        ['value' => '10,000+', 'label' => 'Expats helped'],
        ['value' => 'Trusted', 'label' => 'Partner network'],
        ['value' => 'Expert-reviewed', 'label' => 'Content and guides'],
    ],
    'guides' => [
        ['title' => 'New to Australia', 'eyebrow' => 'Arrival guide', 'description' => 'Checklist-led guidance.'],
        ['title' => 'Housing Guide', 'eyebrow' => 'Relocation guide', 'description' => 'Housing basics.'],
        ['title' => 'Banking Guide', 'eyebrow' => 'Finance guide', 'description' => 'Banking basics.'],
    ],
    'partnerLogos' => ['Migration Agents'],
    'trustPoints' => ['Warm, practical guidance.'],
])->render();
echo strlen($html), PHP_EOL;
echo substr($html, 0, 200), PHP_EOL;
