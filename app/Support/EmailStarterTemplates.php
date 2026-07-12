<?php

namespace App\Support;

class EmailStarterTemplates
{
    public static function list(): array
    {
        return [
            'professional_download' => [
                'name' => 'Professional Download',
                'subject' => SystemEmailTemplates::downloadSubject(),
                'description' => 'The final SettleANZ download delivery layout with online view and PDF buttons.',
                'blocks' => SystemEmailTemplates::downloadBuilderJson()['blocks'],
            ],
            'welcome' => [
                'name' => 'Welcome Email',
                'subject' => 'Welcome to SettleANZ, {{name}}!',
                'description' => 'Introduce new members to SettleANZ and guide them to key services.',
                'blocks' => [
                    ['type' => 'logo', 'properties' => ['alignment' => 'center', 'width' => 150, 'paddingTop' => 20, 'paddingBottom' => 15]],
                    ['type' => 'heading', 'properties' => ['text' => 'Welcome to the SettleANZ Family!', 'fontSize' => '28px', 'alignment' => 'center', 'fontWeight' => 'bold']],
                    ['type' => 'spacer', 'properties' => ['height' => 10]],
                    ['type' => 'text', 'properties' => ['text' => 'Hi {{name}},

We are thrilled to welcome you to SettleANZ! Moving to Australia or New Zealand is one of the most exciting journeys of your life, but it can also be overwhelming.

We built SettleANZ to make sure you have the exact resources, checklists, and guides you need to feel right at home.', 'fontSize' => '16px', 'alignment' => 'center']],
                    ['type' => 'button', 'properties' => ['text' => 'Explore the Settler Roadmap', 'url' => '{{website}}', 'alignment' => 'center', 'fontSize' => '16px']],
                    ['type' => 'divider', 'properties' => ['height' => 1, 'color' => '#e6f4f3', 'margin' => 25]],
                    ['type' => 'heading', 'properties' => ['text' => 'Top Resources For You', 'fontSize' => '20px', 'alignment' => 'left', 'fontWeight' => 'bold']],
                    ['type' => 'text', 'properties' => ['text' => '- **Housing & Rental Guide**: Learn how to secure your first home without rental history.
- **Medicare & Healthcare**: Register for local clinics and understand health insurance.
- **Banking & TFN**: Open your bank account early and apply for your tax file number.', 'fontSize' => '15px', 'alignment' => 'left']],
                    ['type' => 'spacer', 'properties' => ['height' => 15]],
                    ['type' => 'footer', 'properties' => []],
                ]
            ],
            'contact_auto_reply' => [
                'name' => 'Contact Auto Reply',
                'subject' => 'Thanks for contacting SettleANZ',
                'description' => 'A warm acknowledgement for contact form submissions.',
                'blocks' => [
                    ['type' => 'logo', 'properties' => ['alignment' => 'center', 'width' => 150, 'paddingTop' => 20, 'paddingBottom' => 15]],
                    ['type' => 'heading', 'properties' => ['text' => 'Thanks, {{lead_name}}', 'fontSize' => '26px', 'alignment' => 'left', 'fontWeight' => 'bold']],
                    ['type' => 'text', 'properties' => ['text' => 'Hi {{lead_name}},

Thank you for reaching out to SettleANZ. We have received your message and will review it carefully.

A member of our team will get back to you within **{{response_time}}**.', 'fontSize' => '16px', 'alignment' => 'left']],
                    ['type' => 'divider', 'properties' => ['height' => 1, 'color' => '#e6f4f3', 'margin' => 20]],
                    ['type' => 'text', 'properties' => ['text' => 'Warm regards,
The SettleANZ Team

P.S. If your question is urgent, you can reply directly to this email and we will do our best to help.', 'fontSize' => '15px', 'color' => '#607080', 'alignment' => 'left']],
                    ['type' => 'footer', 'properties' => []],
                ]
            ],
            'booking_auto_reply' => [
                'name' => 'Booking Auto Reply',
                'subject' => 'We received your booking request',
                'description' => 'A professional acknowledgement for package and consultation requests.',
                'blocks' => [
                    ['type' => 'logo', 'properties' => ['alignment' => 'center', 'width' => 150, 'paddingTop' => 20, 'paddingBottom' => 15]],
                    ['type' => 'heading', 'properties' => ['text' => 'Booking request received', 'fontSize' => '26px', 'alignment' => 'left', 'fontWeight' => 'bold']],
                    ['type' => 'text', 'properties' => ['text' => 'Hi {{lead_name}},

Thank you for reaching out to SettleANZ. We have received your {{enquiry_type}} and will confirm the session details shortly.

A member of our team will get back to you within **{{response_time}}**.', 'fontSize' => '16px', 'alignment' => 'left']],
                    ['type' => 'divider', 'properties' => ['height' => 1, 'color' => '#e6f4f3', 'margin' => 20]],
                    ['type' => 'text', 'properties' => ['text' => 'Warm regards,
The SettleANZ Team

P.S. If your question is urgent, you can reply directly to this email and we will do our best to help.', 'fontSize' => '15px', 'color' => '#607080', 'alignment' => 'left']],
                    ['type' => 'footer', 'properties' => []],
                ]
            ],
            'ebook_follow_up' => [
                'name' => 'Ebook Follow Up',
                'subject' => 'How was {{ebook_title}}, {{lead_name}}?',
                'description' => 'A gentle follow-up after a lead downloads a guide or checklist.',
                'blocks' => [
                    ['type' => 'logo', 'properties' => ['alignment' => 'center', 'width' => 150, 'paddingTop' => 20, 'paddingBottom' => 15]],
                    ['type' => 'heading', 'properties' => ['text' => 'How was {{ebook_title}}?', 'fontSize' => '26px', 'alignment' => 'left', 'fontWeight' => 'bold']],
                    ['type' => 'text', 'properties' => ['text' => 'Hi {{lead_name}},

I hope **{{ebook_title}}** has been helpful as you plan your next steps.

If you have questions after reading it, simply reply to this email. Our team can point you toward the right settlement resources and practical next steps.', 'fontSize' => '16px', 'alignment' => 'left']],
                    ['type' => 'divider', 'properties' => ['height' => 1, 'color' => '#e6f4f3', 'margin' => 20]],
                    ['type' => 'text', 'properties' => ['text' => 'Warm regards,
The SettleANZ Team', 'fontSize' => '15px', 'color' => '#607080', 'alignment' => 'left']],
                    ['type' => 'footer', 'properties' => []],
                ]
            ],
            'newsletter' => [
                'name' => 'Newsletter Template',
                'subject' => 'The Settler Monthly: Tips, visa news, and stories',
                'description' => 'A clean layout for monthly newsletters and updates.',
                'blocks' => [
                    ['type' => 'logo', 'properties' => ['alignment' => 'center', 'width' => 150, 'paddingTop' => 20, 'paddingBottom' => 15]],
                    ['type' => 'heading', 'properties' => ['text' => 'Monthly Settler Digest', 'fontSize' => '28px', 'alignment' => 'center', 'fontWeight' => 'bold']],
                    ['type' => 'text', 'properties' => ['text' => 'Welcome to your monthly round-up of advice and resources for making your relocation smooth.', 'fontSize' => '16px', 'alignment' => 'center', 'color' => '#607080']],
                    ['type' => 'divider', 'properties' => ['height' => 2, 'color' => '#e6f4f3', 'margin' => 15]],
                    ['type' => 'heading', 'properties' => ['text' => '1. Finding a Job in Australia/NZ', 'fontSize' => '20px', 'alignment' => 'left', 'fontWeight' => 'bold']],
                    ['type' => 'text', 'properties' => ['text' => 'Job markets down under value local formats. Learn how to adapt your resume and write cover letters that capture attention.', 'fontSize' => '15px', 'alignment' => 'left']],
                    ['type' => 'button', 'properties' => ['text' => 'Read Resume Guide', 'url' => '{{website}}/blog', 'alignment' => 'left', 'fontSize' => '14px', 'padding' => 10]],
                    ['type' => 'spacer', 'properties' => ['height' => 15]],
                    ['type' => 'heading', 'properties' => ['text' => '2. Medicare vs Private Care', 'fontSize' => '20px', 'alignment' => 'left', 'fontWeight' => 'bold']],
                    ['type' => 'text', 'properties' => ['text' => 'Healthcare eligibility varies by visa status. Find out what you are entitled to and whether private insurance is necessary.', 'fontSize' => '15px', 'alignment' => 'left']],
                    ['type' => 'button', 'properties' => ['text' => 'Read Healthcare Guide', 'url' => '{{website}}/blog', 'alignment' => 'left', 'fontSize' => '14px', 'padding' => 10]],
                    ['type' => 'divider', 'properties' => ['height' => 1, 'color' => '#e6f4f3', 'margin' => 25]],
                    ['type' => 'footer', 'properties' => []],
                ]
            ],
            'password_reset' => [
                'name' => 'Password Reset',
                'subject' => 'Reset your SettleANZ account password',
                'description' => 'Simple alert with CTA button for security overrides.',
                'blocks' => [
                    ['type' => 'logo', 'properties' => ['alignment' => 'center', 'width' => 150, 'paddingTop' => 20, 'paddingBottom' => 15]],
                    ['type' => 'heading', 'properties' => ['text' => 'Reset Your Password', 'fontSize' => '24px', 'alignment' => 'center', 'fontWeight' => 'bold']],
                    ['type' => 'text', 'properties' => ['text' => 'We received a request to reset your password. Click the button below to set a new one. This link will expire shortly.', 'fontSize' => '16px', 'alignment' => 'center']],
                    ['type' => 'button', 'properties' => ['text' => 'Reset Password', 'url' => '{{website}}/password-reset', 'alignment' => 'center', 'fontSize' => '16px']],
                    ['type' => 'text', 'properties' => ['text' => 'If you did not request a password reset, please ignore this email. Your password remains safe.', 'fontSize' => '14px', 'color' => '#607080', 'alignment' => 'center']],
                    ['type' => 'footer', 'properties' => []],
                ]
            ],
            'verification' => [
                'name' => 'Verification Email',
                'subject' => 'Verify your email address - SettleANZ',
                'description' => 'Simple, focus-driven account confirmation template.',
                'blocks' => [
                    ['type' => 'logo', 'properties' => ['alignment' => 'center', 'width' => 150, 'paddingTop' => 20, 'paddingBottom' => 15]],
                    ['type' => 'heading', 'properties' => ['text' => 'Verify Your Email Address', 'fontSize' => '24px', 'alignment' => 'center', 'fontWeight' => 'bold']],
                    ['type' => 'text', 'properties' => ['text' => 'Hi {{name}},

Thanks for signing up! Please verify your email address to unlock your account and get full access to SettleANZ.', 'fontSize' => '16px', 'alignment' => 'center']],
                    ['type' => 'button', 'properties' => ['text' => 'Verify Email Address', 'url' => '{{website}}/verify', 'alignment' => 'center', 'fontSize' => '16px']],
                    ['type' => 'footer', 'properties' => []],
                ]
            ],
            'receipt' => [
                'name' => 'Payment Receipt',
                'subject' => 'Receipt for your SettleANZ purchase',
                'description' => 'Post-purchase transactional receipt summary.',
                'blocks' => [
                    ['type' => 'logo', 'properties' => ['alignment' => 'center', 'width' => 150, 'paddingTop' => 20, 'paddingBottom' => 15]],
                    ['type' => 'heading', 'properties' => ['text' => 'Payment Receipt', 'fontSize' => '24px', 'alignment' => 'left', 'fontWeight' => 'bold']],
                    ['type' => 'text', 'properties' => ['text' => 'Hi {{name}},

Thank you for your purchase. This email confirms payment for your settlement assistance package.', 'fontSize' => '16px', 'alignment' => 'left']],
                    ['type' => 'quote', 'properties' => ['text' => 'Transaction ID: TXN-018247
Date: {{current_year}}-07-10
Total Paid: $299.00 USD
Payment Method: Visa ending in 4242', 'author' => '', 'background' => '#f9fafb']],
                    ['type' => 'text', 'properties' => ['text' => 'If you have any questions, feel free to reply to this email or write to us at {{support_email}}.', 'fontSize' => '14px', 'alignment' => 'left']],
                    ['type' => 'footer', 'properties' => []],
                ]
            ],
            'invoice' => [
                'name' => 'Invoice Template',
                'subject' => 'Invoice for SettleANZ services',
                'description' => 'Billing statement for custom consultations.',
                'blocks' => [
                    ['type' => 'logo', 'properties' => ['alignment' => 'center', 'width' => 150, 'paddingTop' => 20, 'paddingBottom' => 15]],
                    ['type' => 'heading', 'properties' => ['text' => 'Invoice #INV-2026-09', 'fontSize' => '24px', 'alignment' => 'left', 'fontWeight' => 'bold']],
                    ['type' => 'text', 'properties' => ['text' => 'Hi {{name}},

Your invoice for settlement consultation services is ready. Details are listed below:', 'fontSize' => '16px', 'alignment' => 'left']],
                    ['type' => 'quote', 'properties' => ['text' => 'Due Date: Next Month
Invoice Total: $150.00 AUD', 'author' => '', 'background' => '#ffebee', 'borderColor' => '#f44336']],
                    ['type' => 'button', 'properties' => ['text' => 'View & Pay Invoice', 'url' => '{{website}}/invoices/INV-2026-09', 'alignment' => 'center', 'fontSize' => '16px']],
                    ['type' => 'footer', 'properties' => []],
                ]
            ],
            'promotion' => [
                'name' => 'Promotion / Campaign',
                'subject' => 'Exclusive relocation packages: Save 20% today!',
                'description' => 'Attractive promotional email with banner and highlight block.',
                'blocks' => [
                    ['type' => 'logo', 'properties' => ['alignment' => 'center', 'width' => 150, 'paddingTop' => 20, 'paddingBottom' => 15]],
                    ['type' => 'banner', 'properties' => ['backgroundImage' => '', 'title' => 'Big Moves Made Easy', 'subtitle' => 'Save 20% on SettleANZ premium relocation concierge helper guides.', 'buttonText' => 'Claim Discount Now', 'buttonUrl' => '{{website}}/promo', 'height' => 250]],
                    ['type' => 'spacer', 'properties' => ['height' => 10]],
                    ['type' => 'heading', 'properties' => ['text' => 'Take the Stress Out of Moving', 'fontSize' => '22px', 'alignment' => 'center', 'fontWeight' => 'bold']],
                    ['type' => 'text', 'properties' => ['text' => 'For a limited time, get direct consultation matching, customized suburbs checklists, and phone setup guides at 20% off. Offer expires in 48 hours!', 'fontSize' => '16px', 'alignment' => 'center']],
                    ['type' => 'footer', 'properties' => []],
                ]
            ]
        ];
    }
}
