<?php

namespace App\Services;

use App\Models\SiteSetting;

class EmailTemplateRenderer
{
    public static function render(array|string|null $builderJson, ?string $preheaderText = null): string
    {
        if (is_string($builderJson)) {
            $builderJson = json_decode($builderJson, true);
        }

        $blocks = $builderJson['blocks'] ?? [];
        $settings = $builderJson['settings'] ?? [];

        // Global Theme Overrides merged with builder config
        $localTheme = array_filter($settings['theme'] ?? [], function($val) {
            return !is_null($val) && $val !== '';
        });

        $theme = array_merge([
            'primaryColor' => SiteSetting::getValue('email_theme_primary_color', '#065e5b'),
            'secondaryColor' => SiteSetting::getValue('email_theme_secondary_color', '#e8773a'),
            'backgroundColor' => SiteSetting::getValue('email_theme_background', '#f5f0e8'),
            'textColor' => SiteSetting::getValue('email_theme_text_color', '#2c3a47'),
            'buttonRadius' => SiteSetting::getValue('email_theme_button_radius', '8px'),
            'defaultFont' => SiteSetting::getValue('email_theme_default_font', "Arial, 'Helvetica Neue', Helvetica, sans-serif"),
            'logo' => asset('media/logo/email_logo.png'),
            'footer' => SiteSetting::getValue('email_theme_footer', '&copy; {{current_year}} {{company_name}}. All rights reserved.'),
            'address' => SiteSetting::getValue('email_theme_address', 'SettleANZ, Australia'),
            'supportEmail' => SiteSetting::getValue('email_theme_support_email', 'hello@settleanz.com'),
            'website' => SiteSetting::getValue('email_theme_website', 'https://settleanz.com'),
            'social_facebook' => SiteSetting::getValue('email_theme_social_facebook', 'https://facebook.com'),
            'social_instagram' => SiteSetting::getValue('email_theme_social_instagram', 'https://instagram.com'),
            'social_linkedin' => SiteSetting::getValue('email_theme_social_linkedin', 'https://linkedin.com'),
            'social_pinterest' => SiteSetting::getValue('email_theme_social_pinterest', 'https://pinterest.com'),
            'social_youtube' => SiteSetting::getValue('email_theme_social_youtube', 'https://youtube.com'),
        ], $localTheme);

        // Get preheader
        $preheader = $preheaderText ?? $settings['preheader'] ?? '';

        $html = self::getHeader($theme, $preheader);
        $html .= self::renderBlocks($blocks, $theme);
        $html .= self::getFooter($theme);

        return $html;
    }

    public static function renderBlocks(array $blocks, array $theme): string
    {
        $html = '';
        foreach ($blocks as $block) {
            $html .= self::renderBlock($block, $theme);
        }
        return $html;
    }

    private static function renderBlock(array $block, array $theme): string
    {
        $type = $block['type'] ?? '';
        $props = $block['properties'] ?? [];

        switch ($type) {
            case 'logo':
                $align = $props['alignment'] ?? 'center';
                $width = $props['width'] ?? '150';
                $paddingTop = $props['paddingTop'] ?? '20';
                $paddingBottom = $props['paddingBottom'] ?? '20';
                $logoUrl = asset('media/logo/email_logo.png'); // Lock to PNG email logo!

                return "
                <table role=\"presentation\" width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">
                    <tr>
                        <td align=\"{$align}\" style=\"padding: {$paddingTop}px 0 {$paddingBottom}px 0;\">
                            <a href=\"{$theme['website']}\" target=\"_blank\">
                                <img src=\"{$logoUrl}\" width=\"{$width}\" style=\"width: {$width}px; max-width: 100%; height: auto; display: block; border: 0;\" alt=\"Logo\">
                            </a>
                        </td>
                    </tr>
                </table>";

            case 'heading':
                $text = $props['text'] ?? 'Heading Text';
                $fontSize = $props['fontSize'] ?? '24px';
                $color = $props['color'] ?? $theme['primaryColor'];
                $weight = $props['fontWeight'] ?? 'bold';
                $align = $props['alignment'] ?? 'left';
                $paddingTop = $props['paddingTop'] ?? '15';
                $paddingBottom = $props['paddingBottom'] ?? '15';
                $margin = $props['margin'] ?? '0';

                return "
                <table role=\"presentation\" width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">
                    <tr>
                        <td align=\"{$align}\" style=\"padding: {$paddingTop}px 20px {$paddingBottom}px 20px; margin: {$margin}px 0;\">
                            <h1 style=\"color: {$color}; font-family: {$theme['defaultFont']}; font-size: {$fontSize}; font-weight: {$weight}; line-height: 1.3; margin: 0; text-align: {$align};\">{$text}</h1>
                        </td>
                    </tr>
                </table>";

            case 'text':
                $text = $props['text'] ?? 'Text block content goes here. You can insert variables too.';
                $fontSize = $props['fontSize'] ?? '16px';
                $color = $props['color'] ?? $theme['textColor'];
                $weight = $props['fontWeight'] ?? 'normal';
                $align = $props['alignment'] ?? 'left';
                $lineHeight = $props['lineHeight'] ?? '1.6';
                $paddingTop = $props['paddingTop'] ?? '10';
                $paddingBottom = $props['paddingBottom'] ?? '10';
                
                // Format text newlines into paragraphs or linebreaks
                $formattedText = nl2br($text);

                return "
                <table role=\"presentation\" width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">
                    <tr>
                        <td align=\"{$align}\" style=\"padding: {$paddingTop}px 20px {$paddingBottom}px 20px; font-family: {$theme['defaultFont']}; font-size: {$fontSize}; color: {$color}; font-weight: {$weight}; line-height: {$lineHeight}; text-align: {$align};\">
                            <div style=\"margin: 0;\">{$formattedText}</div>
                        </td>
                    </tr>
                </table>";

            case 'image':
                $src = $props['src'] ?? '';
                if (empty($src)) {
                    $src = asset('media/logo/email_logo.png');
                }
                // Fallback to PNG if another template references WEBP
                if (str_contains($src, 'logo.webp')) {
                    $src = str_replace('logo.webp', 'email_logo.png', $src);
                }

                $width = $props['width'] ?? '560';
                $alt = $props['alt'] ?? 'Image';
                $align = $props['alignment'] ?? 'center';
                $radius = $props['borderRadius'] ?? '0';
                $link = $props['link'] ?? '';
                $padding = $props['padding'] ?? '10';

                $imgHtml = "<img src=\"{$src}\" width=\"{$width}\" alt=\"{$alt}\" style=\"width: {$width}px; max-width: 100%; height: auto; display: block; border: 0; border-radius: {$radius}px;\">";
                if ($link) {
                    $imgHtml = "<a href=\"{$link}\" target=\"_blank\">{$imgHtml}</a>";
                }

                return "
                <table role=\"presentation\" width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">
                    <tr>
                        <td align=\"{$align}\" style=\"padding: {$padding}px 20px;\">
                            <table role=\"presentation\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\" align=\"{$align}\">
                                <tr>
                                    <td>{$imgHtml}</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>";

            case 'button':
                $text = $props['text'] ?? 'Click Here';
                $url = $props['url'] ?? '#';
                $bg = $props['background'] ?? $theme['secondaryColor'];
                $radius = $props['radius'] ?? $theme['buttonRadius'];
                $fontColor = $props['fontColor'] ?? '#ffffff';
                $align = $props['alignment'] ?? 'center';
                $fontSize = $props['fontSize'] ?? '16px';
                $padding = $props['padding'] ?? '15';

                // Ensure radius is absolute
                if (is_numeric($radius)) {
                    $radius = $radius . 'px';
                }

                return "
                <table role=\"presentation\" width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">
                    <tr>
                        <td align=\"{$align}\" style=\"padding: {$padding}px 20px;\">
                            <table role=\"presentation\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\" align=\"{$align}\">
                                <tr>
                                    <td align=\"center\" bgcolor=\"{$bg}\" style=\"border-radius: {$radius};\">
                                        <a href=\"{$url}\" target=\"_blank\" style=\"background-color: {$bg}; border: 1px solid {$bg}; border-radius: {$radius}; color: {$fontColor}; display: inline-block; font-family: {$theme['defaultFont']}; font-size: {$fontSize}; font-weight: bold; line-height: 1.5; padding: 12px 30px; text-decoration: none; text-align: center;\">
                                            {$text}
                                        </a>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>";

            case 'divider':
                $height = $props['height'] ?? '1';
                $color = $props['color'] ?? '#e6f4f3';
                $margin = $props['margin'] ?? '20';

                return "
                <table role=\"presentation\" width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">
                    <tr>
                        <td style=\"padding: {$margin}px 20px;\">
                            <table role=\"presentation\" width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">
                                <tr>
                                    <td style=\"border-top: {$height}px solid {$color}; font-size: 1px; line-height: 1px;\">&nbsp;</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>";

            case 'spacer':
                $height = $props['height'] ?? '20';

                return "
                <table role=\"presentation\" width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">
                    <tr>
                        <td height=\"{$height}\" style=\"font-size: 1px; line-height: 1px;\">&nbsp;</td>
                    </tr>
                </table>";

            case 'columns_2':
                $bg = $props['background'] ?? '#ffffff';
                $padding = $props['padding'] ?? '15';
                $gap = $props['gap'] ?? '20';

                $col1Blocks = self::renderBlocks($block['properties']['col1_blocks'] ?? [], $theme);
                $col2Blocks = self::renderBlocks($block['properties']['col2_blocks'] ?? [], $theme);

                return "
                <table role=\"presentation\" width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\" style=\"background-color: {$bg};\">
                    <tr>
                        <td style=\"padding: {$padding}px 20px;\">
                            <!--[if mso]>
                            <table role=\"presentation\" width=\"560\" cellpadding=\"0\" cellspacing=\"0\">
                                <tr>
                                    <td width=\"270\" valign=\"top\">
                            <![endif]-->
                            <div style=\"display:inline-block; width:100%; max-width:270px; vertical-align:top;\">
                                {$col1Blocks}
                            </div>
                            <!--[if mso]>
                                    </td>
                                    <td width=\"20\" style=\"font-size: 1px;\">&nbsp;</td>
                                    <td width=\"270\" valign=\"top\">
                            <![endif]-->
                            <div style=\"display:inline-block; width:100%; max-width:270px; vertical-align:top; margin-left: {$gap}px;\">
                                {$col2Blocks}
                            </div>
                            <!--[if mso]>
                                    </td>
                                </tr>
                            </table>
                            <![endif]-->
                        </td>
                    </tr>
                </table>";

            case 'columns_3':
                $bg = $props['background'] ?? '#ffffff';
                $padding = $props['padding'] ?? '15';
                $gap = $props['gap'] ?? '10';

                $col1Blocks = self::renderBlocks($block['properties']['col1_blocks'] ?? [], $theme);
                $col2Blocks = self::renderBlocks($block['properties']['col2_blocks'] ?? [], $theme);
                $col3Blocks = self::renderBlocks($block['properties']['col3_blocks'] ?? [], $theme);

                return "
                <table role=\"presentation\" width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\" style=\"background-color: {$bg};\">
                    <tr>
                        <td style=\"padding: {$padding}px 20px;\">
                            <!--[if mso]>
                            <table role=\"presentation\" width=\"560\" cellpadding=\"0\" cellspacing=\"0\">
                                <tr>
                                    <td width=\"180\" valign=\"top\">
                            <![endif]-->
                            <div style=\"display:inline-block; width:100%; max-width:180px; vertical-align:top;\">
                                {$col1Blocks}
                            </div>
                            <!--[if mso]>
                                    </td>
                                    <td width=\"10\" style=\"font-size: 1px;\">&nbsp;</td>
                                    <td width=\"180\" valign=\"top\">
                            <![endif]-->
                            <div style=\"display:inline-block; width:100%; max-width:180px; vertical-align:top; margin-left: {$gap}px;\">
                                {$col2Blocks}
                            </div>
                            <!--[if mso]>
                                    </td>
                                    <td width=\"10\" style=\"font-size: 1px;\">&nbsp;</td>
                                    <td width=\"180\" valign=\"top\">
                            <![endif]-->
                            <div style=\"display:inline-block; width:100%; max-width:180px; vertical-align:top; margin-left: {$gap}px;\">
                                {$col3Blocks}
                            </div>
                            <!--[if mso]>
                                    </td>
                                </tr>
                            </table>
                            <![endif]-->
                        </td>
                    </tr>
                </table>";

            case 'social_icons':
                $align = $props['alignment'] ?? 'center';
                $size = $props['size'] ?? '32';
                $padding = $props['padding'] ?? '15';
                
                $socials = [
                    ['name' => 'facebook', 'url' => $theme['social_facebook'], 'icon' => 'https://img.icons8.com/color/48/facebook.png'],
                    ['name' => 'instagram', 'url' => $theme['social_instagram'], 'icon' => 'https://img.icons8.com/color/48/instagram-new.png'],
                    ['name' => 'linkedin', 'url' => $theme['social_linkedin'], 'icon' => 'https://img.icons8.com/color/48/linkedin.png'],
                    ['name' => 'pinterest', 'url' => $theme['social_pinterest'], 'icon' => 'https://img.icons8.com/color/48/pinterest.png'],
                    ['name' => 'youtube', 'url' => $theme['social_youtube'], 'icon' => 'https://img.icons8.com/color/48/youtube-play.png'],
                ];

                $socialHtml = '';
                foreach ($socials as $s) {
                    // Check if toggled on (default true)
                    if (($props[$s['name'] . '_enabled'] ?? true) && $s['url'] && $s['url'] !== '#') {
                        $socialHtml .= "
                        <td style=\"padding: 0 8px;\">
                            <a href=\"{$s['url']}\" target=\"_blank\">
                                <img src=\"{$s['icon']}\" width=\"{$size}\" height=\"{$size}\" alt=\"{$s['name']}\" style=\"display:block; border: 0;\">
                            </a>
                        </td>";
                    }
                }

                if (empty($socialHtml)) {
                    return '';
                }

                return "
                <table role=\"presentation\" width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">
                    <tr>
                        <td align=\"{$align}\" style=\"padding: {$padding}px 20px;\">
                            <table role=\"presentation\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\" align=\"{$align}\">
                                <tr>
                                    {$socialHtml}
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>";

            case 'footer':
                $bg = $props['background'] ?? '#f5f0e8';
                $color = $props['color'] ?? '#607080';
                $padding = $props['padding'] ?? '25';
                $align = $props['alignment'] ?? 'center';

                return "
                <table role=\"presentation\" width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\" style=\"background-color: {$bg};\">
                    <tr>
                        <td align=\"{$align}\" style=\"padding: {$padding}px 20px; font-family: {$theme['defaultFont']}; font-size: 12px; color: {$color}; line-height: 1.6; text-align: {$align};\">
                            <p style=\"margin: 0 0 10px 0;\">{$theme['footer']}</p>
                            <p style=\"margin: 0 0 10px 0;\">{$theme['address']}</p>
                            <p style=\"margin: 0;\">You received this email because you interact with {$theme['website']}.</p>
                            <p style=\"margin: 10px 0 0 0;\"><a href=\"{{unsubscribe}}\" style=\"color: {$theme['primaryColor']}; text-decoration: underline;\">Unsubscribe</a> &middot; <a href=\"mailto:{$theme['supportEmail']}\" style=\"color: {$theme['primaryColor']}; text-decoration: underline;\">Contact Support</a></p>
                        </td>
                    </tr>
                </table>";

            case 'signature':
                $name = $props['name'] ?? 'The SettleANZ Team';
                $title = $props['title'] ?? 'Customer Support';
                $company = $props['company'] ?? $theme['website'];
                $signImg = $props['image'] ?? '';
                $align = $props['alignment'] ?? 'left';
                $padding = $props['padding'] ?? '15';

                $sigHtml = "<p style=\"margin: 0 0 4px 0; font-family: {$theme['defaultFont']}; font-size: 16px; font-weight: bold; color: {$theme['textColor']};\">{$name}</p>";
                if ($title) {
                    $sigHtml .= "<p style=\"margin: 0 0 2px 0; font-family: {$theme['defaultFont']}; font-size: 14px; color: #607080;\">{$title}</p>";
                }
                if ($company) {
                    $sigHtml .= "<p style=\"margin: 0; font-family: {$theme['defaultFont']}; font-size: 14px; color: {$theme['primaryColor']};\"><a href=\"{$theme['website']}\" style=\"color: {$theme['primaryColor']}; text-decoration: none;\">{$company}</a></p>";
                }

                $imgHtml = '';
                if ($signImg) {
                    $imgHtml = "<img src=\"{$signImg}\" max-height=\"50\" style=\"max-height: 50px; height: auto; display: block; border: 0; margin-bottom: 10px;\" alt=\"Signature\"><br>";
                }

                return "
                <table role=\"presentation\" width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">
                    <tr>
                        <td align=\"{$align}\" style=\"padding: {$padding}px 20px;\">
                            <div style=\"font-family: {$theme['defaultFont']}; font-size: 16px; color: {$theme['textColor']}; line-height: 1.5;\">
                                <p style=\"margin: 0 0 10px 0; font-style: italic;\">Sincerely,</p>
                                {$imgHtml}
                                {$sigHtml}
                            </div>
                        </td>
                    </tr>
                </table>";

            case 'banner':
                $bgImg = $props['backgroundImage'] ?? '';
                $title = $props['title'] ?? 'Banner Title';
                $subtitle = $props['subtitle'] ?? 'Banner Subtitle';
                $buttonText = $props['buttonText'] ?? '';
                $buttonUrl = $props['buttonUrl'] ?? '#';
                $height = $props['height'] ?? '250';
                $overlay = $props['overlayOpacity'] ?? '0.4';
                $color = $props['color'] ?? '#ffffff';
                $align = $props['alignment'] ?? 'center';

                if (empty($bgImg)) {
                    // Fallback to simple colored background block
                    $bgImg = 'linear-gradient(135deg, ' . $theme['primaryColor'] . ' 0%, ' . $theme['secondaryColor'] . ' 100%)';
                } else {
                    $bgImg = "url('{$bgImg}')";
                }

                $btnHtml = '';
                if ($buttonText) {
                    $btnHtml = "
                    <table role=\"presentation\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\" align=\"{$align}\" style=\"margin-top: 15px;\">
                        <tr>
                            <td align=\"center\" bgcolor=\"{$theme['secondaryColor']}\" style=\"border-radius: {$theme['buttonRadius']};\">
                                <a href=\"{$buttonUrl}\" target=\"_blank\" style=\"background-color: {$theme['secondaryColor']}; border: 1px solid {$theme['secondaryColor']}; border-radius: {$theme['buttonRadius']}; color: #ffffff; display: inline-block; font-family: {$theme['defaultFont']}; font-size: 14px; font-weight: bold; line-height: 1.5; padding: 10px 20px; text-decoration: none;\">
                                    {$buttonText}
                                </a>
                            </td>
                        </tr>
                    </table>";
                }

                return "
                <table role=\"presentation\" width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">
                    <tr>
                        <td align=\"{$align}\" style=\"padding: 10px 20px;\">
                            <table role=\"presentation\" width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\" style=\"background: {$bgImg} no-repeat center center / cover; border-radius: 8px; overflow: hidden;\">
                                <tr>
                                    <td height=\"{$height}\" valign=\"middle\" style=\"padding: 30px 20px; background-color: rgba(0, 0, 0, {$overlay}); text-align: {$align};\">
                                        <h2 style=\"color: {$color}; font-family: {$theme['defaultFont']}; font-size: 28px; font-weight: bold; margin: 0 0 10px 0; text-shadow: 0 2px 4px rgba(0,0,0,0.5);\">{$title}</h2>
                                        <p style=\"color: {$color}; font-family: {$theme['defaultFont']}; font-size: 16px; margin: 0; text-shadow: 0 1px 2px rgba(0,0,0,0.5);\">{$subtitle}</p>
                                        {$btnHtml}
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>";

            case 'quote':
                $text = $props['text'] ?? 'The only limit to our realization of tomorrow will be our doubts of today.';
                $author = $props['author'] ?? 'Franklin D. Roosevelt';
                $borderColor = $props['borderColor'] ?? $theme['primaryColor'];
                $bg = $props['background'] ?? '#f0fbfb';
                $color = $props['color'] ?? $theme['textColor'];
                $padding = $props['padding'] ?? '20';

                $authHtml = '';
                if ($author) {
                    $authHtml = "<p style=\"margin: 10px 0 0 0; font-family: {$theme['defaultFont']}; font-size: 14px; font-weight: bold; color: #607080; text-align: right;\">&mdash; {$author}</p>";
                }

                return "
                <table role=\"presentation\" width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">
                    <tr>
                        <td style=\"padding: 15px 20px;\">
                            <table role=\"presentation\" width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\" style=\"background-color: {$bg}; border-left: 4px solid {$borderColor};\">
                                <tr>
                                    <td style=\"padding: {$padding}px;\">
                                        <p style=\"margin: 0; font-family: {$theme['defaultFont']}; font-size: 16px; font-style: italic; color: {$color}; line-height: 1.6;\">&ldquo;{$text}&rdquo;</p>
                                        {$authHtml}
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>";

            default:
                return '';
        }
    }

    private static function getHeader(array $theme, string $preheader): string
    {
        $preheaderHtml = '';
        if ($preheader) {
            $preheaderHtml = "
            <div style=\"display:none; max-height:0px; max-width:0px; opacity:0; overflow:hidden; mso-hide:all; font-size:1px; line-height:1px;\">
                {$preheader}
            </div>";
        }

        return "<!DOCTYPE html>
<html lang=\"en\" xmlns:v=\"urn:schemas-microsoft-com:vml\" xmlns:o=\"urn:schemas-microsoft-com:office:office\">
<head>
    <meta charset=\"utf-8\">
    <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">
    <!--[if !mso]><!-->
    <meta http-equiv=\"X-UA-Compatible\" content=\"IE=edge\">
    <!--<![endif]-->
    <title></title>
    <!--[if mso]>
    <noscript>
    <xml>
        <o:OfficeDocumentSettings>
            <o:AllowPNG/>
            <o:PixelsPerInch>96</o:PixelsPerInch>
        </o:OfficeDocumentSettings>
    </xml>
    </noscript>
    <![endif]-->
    <style>
        * {
            -ms-text-size-adjust: 100%;
            -webkit-text-size-adjust: 100%;
        }
        html, body {
            margin: 0 !important;
            padding: 0 !important;
            height: 100% !important;
            width: 100% !important;
        }
        @media only screen and (max-width: 600px) {
            .email-container {
                width: 100% !important;
                max-width: 100% !important;
                padding: 10px !important;
            }
            .stack-column {
                display: block !important;
                width: 100% !important;
                max-width: 100% !important;
                direction: ltr !important;
                margin-left: 0 !important;
                margin-bottom: 20px !important;
            }
        }
    </style>
</head>
<body style=\"margin: 0; padding: 0 !important; mso-line-height-rule: exactly; background-color: {$theme['backgroundColor']}; font-family: {$theme['defaultFont']};\">
    {$preheaderHtml}
    <center style=\"width: 100%; background-color: {$theme['backgroundColor']};\">
        <table role=\"presentation\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" style=\"background-color: {$theme['backgroundColor']};\">
            <tr>
                <td align=\"center\" valign=\"top\" style=\"padding: 40px 10px;\">
                    <table role=\"presentation\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"600\" class=\"email-container\" style=\"max-width: 600px; width: 100%; background-color: #ffffff; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.05); overflow: hidden;\">
                        <tr>
                            <td style=\"background-color: #ffffff;\">";
    }

    private static function getFooter(array $theme): string
    {
        return "
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </center>
</body>
</html>";
    }
}
