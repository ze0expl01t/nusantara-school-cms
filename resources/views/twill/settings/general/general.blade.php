@twillBlockTitle('Identitas Situs')
@twillBlockIcon('text')
@twillBlockGroup('app')

{{-- Helper style (inline) --}}
@once
@push('extra_css')
<style>
    .twill-help-text {
        font-size: 12px;
        color: #718096;
        margin-top: 4px;
        margin-bottom: 12px;
        line-height: 1.5;
    }
    .twill-help-text strong { color: #4a5568; }
</style>
@endpush
@endonce

{{-- ============ IDENTITAS ============ --}}
<x-twill::input name="site_title" label="Nama Situs / Instansi" :translated="true" :maxlength="120" />
<x-twill::input name="site_tagline" label="Tagline / Slogan" :translated="true" :maxlength="200" />
<x-twill::input name="meta_description" label="Meta Description (SEO)" type="textarea" :rows="3" :translated="true" />
<x-twill::input name="meta_keywords" label="Meta Keywords (pisah koma)" :translated="true" />

{{-- ============ HEADER ORG (Nama Instansi di Header) ============ --}}
<x-twill::input
    name="header_org_name"
    label="Header - Nama Instansi (samping logo)"
    placeholder="HSE SMKMUH1BLORA"
    :translated="true"
    note="Kosongkan untuk pakai Site Title"
/>

<x-twill::input
    name="header_org_subtitle"
    label="Header - Subtitle Instansi"
    placeholder="PT. Nama Perusahaan"
    :translated="true"
    note="Tampil di bawah nama instansi. Disembunyikan di mobile."
/>

{{-- ============ ANALYTICS ============ --}}
<x-twill::input
    name="google_analytics_id"
    label="Google Analytics 4 — Measurement ID"
    placeholder="G-XXXXXXXXXX"
    :maxlength="30"
/>
<p class="twill-help-text">
    Isi dengan <strong>Measurement ID</strong> dari GA4. Contoh: <code>G-S0EZ5D88DM</code>
</p>

<x-twill::checkbox
    name="google_analytics_anonymize_ip"
    label="Anonymize IP (GDPR / privasi pengguna)"
/>
<p class="twill-help-text">
    Sangat disarankan <strong>aktif</strong> untuk kepatuhan privasi.
</p>

<x-twill::input
    name="google_tag_manager_id"
    label="Google Tag Manager — Container ID (opsional)"
    placeholder="GTM-XXXXXXX"
    :maxlength="30"
/>
<p class="twill-help-text">
    <strong>HANYA untuk GTM.</strong> Format: <code>GTM-XXXXXXX</code>.
    <br>JANGAN isi dengan GA4 ID (<code>G-XXXX</code>). Kosongkan jika tidak pakai GTM.
</p>

{{-- ============ MEDIA & FILE ============ --}}
<x-twill::medias name="logo" label="Logo Utama (Header)" :max="1" />
<x-twill::medias name="logo_white" label="Logo Putih (Footer)" :max="1" />
<x-twill::medias name="og_image" label="OG Image (1200x630)" :max="1" />
<x-twill::files name="favicon_file" label="Favicon (.ico / .png / .svg)" :max="1" />

{{-- ============ CTA HEADER ============ --}}
<x-twill::input name="cta_text" label="Tombol CTA - Teks" :translated="true" />
<x-twill::input name="cta_url" label="Tombol CTA - URL" :translated="true" />

{{-- ============ KONTAK ============ --}}
<x-twill::input name="contact_email" label="Email Kontak" :translated="true" />
<x-twill::input name="contact_phone" label="Nomor Telepon / WA" :translated="true" />
<x-twill::input name="contact_address" label="Alamat" type="textarea" :rows="3" :translated="true" />
<x-twill::input name="google_maps_url" label="Google Maps Embed URL" :translated="true" />

{{-- ============ SOSIAL MEDIA ============ --}}
<x-twill::input name="facebook_url" label="Facebook URL" :translated="true" />
<x-twill::input name="instagram_url" label="Instagram URL" :translated="true" />
<x-twill::input name="linkedin_url" label="LinkedIn URL" :translated="true" />
<x-twill::input name="youtube_url" label="YouTube URL" :translated="true" />
<x-twill::input name="twitter_url" label="Twitter / X URL" :translated="true" />
<x-twill::input name="tiktok_url" label="TikTok URL" :translated="true" />
<x-twill::input name="telegram_url" label="Telegram URL" :translated="true" />
<x-twill::input name="whatsapp_url" label="WhatsApp URL" :translated="true" />

{{-- ============ FOOTER ============ --}}
<x-twill::input name="footer_intro" label="Footer - Intro" :translated="true" />
<x-twill::input name="footer_words" label="Footer - Kata Animasi (pisah koma)" type="textarea" :rows="2" :translated="true" />

<x-twill::input
    name="footer_org_name"
    label="Footer - Nama Instansi (samping logo)"
    placeholder="HSE SMKMUH1BLORA"
    :translated="true"
    note="Kosongkan untuk pakai Site Title"
/>

<x-twill::input
    name="footer_org_subtitle"
    label="Footer - Subtitle Instansi"
    placeholder="PT. Nama Perusahaan"
    :translated="true"
/>

<x-twill::input name="quick_links_label" label="Label - Quick Links" :translated="true" />
<x-twill::input name="contact_us_label" label="Label - Contact Us" :translated="true" />
<x-twill::input name="stay_updated_label" label="Label - Stay Updated" :translated="true" />

<x-twill::input name="newsletter_text" label="Newsletter - Deskripsi" type="textarea" :rows="2" :translated="true" />
<x-twill::input name="newsletter_placeholder" label="Newsletter - Placeholder" :translated="true" />
<x-twill::input name="newsletter_button" label="Newsletter - Tombol" :translated="true" />

<x-twill::input name="footer_copyright" label="Footer - Copyright (tanpa tahun)" :translated="true" />
<x-twill::input name="vendor_label" label="Vendor - Label (mis. Powered By)" :translated="true" />
<x-twill::input name="vendor_name" label="Vendor - Nama" :translated="true" />
<x-twill::input name="vendor_url" label="Vendor - URL" :translated="true" />