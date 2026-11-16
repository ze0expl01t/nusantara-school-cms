<?php

namespace App\Models;

use A17\Twill\Models\Model;

class AppSettingTranslation extends Model
{
    protected $fillable = [
        'site_title',
        'site_tagline',
        'meta_description',
        'meta_keywords',
        'cta_text',
        'cta_url',
        'admin_login_text',
        'contact_email',
        'contact_phone',
        'contact_address',
        'google_maps_url',
        'facebook_url',
        'instagram_url',
        'youtube_url',
        'twitter_url',
        'tiktok_url',
        'whatsapp_url',
        'footer_intro',
        'footer_words',
        'quick_links_label',
        'contact_us_label',
        'stay_updated_label',
        'newsletter_text',
        'newsletter_placeholder',
        'newsletter_button',
        'footer_copyright',
        'vendor_label',
        'vendor_name',
        'vendor_url',
        'active',
    ];
}
