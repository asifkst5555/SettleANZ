<?php

namespace App\Support;

class EmailTemplateMigrator
{
    public static function convert(string $html): array
    {
        // Handle new default roadmap download template conversion mapping
        if (str_contains($html, 'Your 90-Day Roadmap is Ready')) {
            return [
                'content' => '',
                'settings' => [
                    'theme' => [
                        'primaryColor' => '#0f766e',
                        'secondaryColor' => '#0f766e',
                        'backgroundColor' => '#f5f7fa',
                        'canvasColor' => '#ffffff',
                        'textColor' => '#334155',
                        'defaultFont' => 'Arial, Helvetica, sans-serif'
                    ]
                ],
                'blocks' => [
                    [
                        'type' => 'logo',
                        'properties' => [
                            'alignment' => 'center',
                            'width' => '170',
                            'paddingTop' => '36',
                            'paddingBottom' => '20'
                        ]
                    ],
                    [
                        'type' => 'heading',
                        'properties' => [
                            'text' => 'Your 90-Day Roadmap is Ready',
                            'fontSize' => '28px',
                            'alignment' => 'left',
                            'fontWeight' => 'bold',
                            'color' => '#0f172a'
                        ]
                    ],
                    [
                        'type' => 'text',
                        'properties' => [
                            'text' => "Hi {{ lead_name }},\n\nThank you for downloading **{{ ebook_title }}**.\nYour download is now ready.\n\nThis secure link expires on **{{ expires_at }}**.\nYou can download your file up to **{{ expires_in_hours }}** times during this period.",
                            'fontSize' => '16px',
                            'alignment' => 'left',
                            'lineHeight' => '1.6',
                            'color' => '#475569'
                        ]
                    ],
                    [
                        'type' => 'button',
                        'properties' => [
                            'text' => 'Download Your Roadmap',
                            'url' => '{{ download_url }}',
                            'alignment' => 'center',
                            'backgroundColor' => '#0f766e',
                            'textColor' => '#ffffff',
                            'fontSize' => '16px'
                        ]
                    ],
                    [
                        'type' => 'text',
                        'properties' => [
                            'text' => "If the button doesn't work, copy and paste this link into your browser:\n\n{{ download_url }}",
                            'fontSize' => '14px',
                            'alignment' => 'left',
                            'lineHeight' => '1.5',
                            'color' => '#64748b'
                        ]
                    ],
                    [
                        'type' => 'divider',
                        'properties' => [
                            'height' => '1',
                            'color' => '#e5e7eb',
                            'margin' => '20'
                        ]
                    ],
                    [
                        'type' => 'text',
                        'properties' => [
                            'text' => "If you have any questions, simply reply to this email or contact us at {{ support_email }}.",
                            'fontSize' => '14px',
                            'alignment' => 'left',
                            'lineHeight' => '1.5',
                            'color' => '#64748b'
                        ]
                    ],
                    [
                        'type' => 'footer',
                        'properties' => [
                            'background' => '#fafafa',
                            'color' => '#94a3b8'
                        ]
                    ]
                ],
                'metadata' => [
                    'version' => '1.0',
                    'migrated' => true
                ]
            ];
        }

        $blocks = [];
        
        // 1. Add logo block if logo.webp or email_logo.png is detected
        if (str_contains($html, 'logo.webp') || str_contains($html, 'email_logo.png')) {
            $blocks[] = [
                'type' => 'logo',
                'properties' => [
                    'alignment' => 'center',
                    'width' => '150',
                    'paddingTop' => '20',
                    'paddingBottom' => '15'
                ]
            ];
        }

        // 2. Try simple extraction of clean main paragraphs
        // If we can parse using DOM, let's do it. Otherwise fallback.
        try {
            $dom = new \DOMDocument();
            // Suppress warnings due to partial HTML
            @$dom->loadHTML(mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8'), LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
            $xpath = new \DOMXPath($dom);
            
            // Look for headings
            $headings = $xpath->query('//h1 | //h2 | //h3');
            $paragraphs = $xpath->query('//p');
            $buttons = $xpath->query("//a[contains(@style, 'background') or contains(@style, 'display: inline-block')]");

            if ($headings->length > 0 || $paragraphs->length > 0) {
                // We have clean elements
                foreach ($headings as $h) {
                    $blocks[] = [
                        'type' => 'heading',
                        'properties' => [
                            'text' => trim($h->textContent),
                            'fontSize' => '24px',
                            'alignment' => 'left',
                            'fontWeight' => 'bold'
                        ]
                    ];
                }

                $pText = '';
                foreach ($paragraphs as $p) {
                    $text = trim($p->textContent);
                    if ($text && !str_contains($text, 'All rights reserved') && !str_contains($text, 'received this email')) {
                        $pText .= $text . "\n\n";
                    }
                }

                if ($pText) {
                    $blocks[] = [
                        'type' => 'text',
                        'properties' => [
                            'text' => trim($pText),
                            'fontSize' => '16px',
                            'alignment' => 'left',
                            'lineHeight' => '1.6'
                        ]
                    ];
                }

                foreach ($buttons as $btn) {
                    $blocks[] = [
                        'type' => 'button',
                        'properties' => [
                            'text' => trim($btn->textContent) ?: 'Click Here',
                            'url' => $btn->getAttribute('href') ?: '#',
                            'alignment' => 'center'
                        ]
                    ];
                }
            } else {
                throw new \Exception("No elements found");
            }
        } catch (\Exception $e) {
            // Fallback: Strip HTML tags partially or wrap body
            // Extract the main readable text from HTML body
            $bodyContent = self::extractBodyText($html);
            $blocks[] = [
                'type' => 'text',
                'properties' => [
                    'text' => $bodyContent,
                    'fontSize' => '16px',
                    'alignment' => 'left',
                    'lineHeight' => '1.6'
                ]
            ];
        }

        // 3. Append social and footer
        $blocks[] = [
            'type' => 'divider',
            'properties' => [
                'height' => '1',
                'color' => '#e6f4f3',
                'margin' => '20'
            ]
        ];
        $blocks[] = [
            'type' => 'footer',
            'properties' => []
        ];

        return [
            'content' => '',
            'settings' => [
                'theme' => []
            ],
            'blocks' => $blocks,
            'metadata' => [
                'version' => '1.0',
                'migrated' => true
            ]
        ];
    }

    private static function extractBodyText(string $html): string
    {
        // Remove style, head, scripts
        $text = preg_replace('/<style\b[^>]*>(.*?)<\/style>/is', "", $html);
        $text = preg_replace('/<head\b[^>]*>(.*?)<\/head>/is', "", $text);
        
        // Match content between body tags if exists
        if (preg_match('/<body[^>]*>(.*?)<\/body>/is', $text, $matches)) {
            $text = $matches[1];
        }

        // Convert links to readable format before stripping tags
        $text = preg_replace('/<a\b[^>]*href="([^"]*)"[^>]*>(.*?)<\/a>/i', '$2 ($1)', $text);

        // Strip remaining tags
        $text = strip_tags($text);
        
        // Clean whitespace
        $text = preg_replace('/[ \t]+/', ' ', $text);
        $text = preg_replace('/\n\s*\n+/', "\n\n", $text);

        return trim($text);
    }
}
