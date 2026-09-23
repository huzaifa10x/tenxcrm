<?php

namespace App\Support;

/**
 * 10X Digital email branding.
 * Built-in defaults guarantee emails always render with brand colors,
 * even when config is cached or config/mail_brand.php is missing.
 * Any value in config/mail_brand.php overrides the defaults.
 */
class MailBrand
{
    public const DEFAULTS = [
        'company'  => '10X Digital',
        'app_name' => '10X One',
        'tagline'  => 'Growth-Driven Digital Marketing Agency',
        'logo'     => 'https://www.10xdigital.ae/wp-content/uploads/2024/10/Logo-Con.webp',

        'colors' => [
            'primary'       => '#6CC04A',
            'primary_dark'  => '#4E9A31',
            'primary_light' => '#EEF8E9',
            'dark'          => '#0F1A14',
            'text'          => '#1F2A24',
            'muted'         => '#6B7A72',
            'border'        => '#E3EAE5',
            'bg'            => '#F2F5F3',
            'danger'        => '#E5484D',
            'danger_light'  => '#FDECEC',
            'warning'       => '#F5A524',
            'warning_light' => '#FFF6E5',
            // solid tints for text/lines on the dark background (no rgba — Outlook/Gmail safe)
            'on_dark'        => '#FFFFFF',
            'on_dark_soft'   => '#C3C8C5',
            'on_dark_muted'  => '#9CA39F',
            'on_dark_line'   => '#2B3630',
            'on_dark_sep'    => '#56605A',
        ],

        'website' => 'https://www.10xdigital.ae',
        'email'   => 'info@10xdigital.ae',
        'phone'   => '+971 4 564 4587',
        'address' => '103C, Ibn Battuta Office Complex, Dubai, UAE',

        'socials' => [
            'LinkedIn'  => 'https://www.linkedin.com/company/10xdigital-ae',
            'Instagram' => 'https://www.instagram.com/10xdigital.ae/',
            'Facebook'  => 'https://www.facebook.com/10Xdigital.ae',
        ],
    ];

    public static function get(): array
    {
        $overrides = config('mail_brand');

        return is_array($overrides)
            ? array_replace_recursive(self::DEFAULTS, array_filter($overrides, fn ($v) => !is_null($v)))
            : self::DEFAULTS;
    }
}