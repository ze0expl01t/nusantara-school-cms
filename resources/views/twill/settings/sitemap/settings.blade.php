@twillBlockTitle('Pengaturan Sitemap')
@twillBlockIcon('text')
@twillBlockGroup('app')

<x-twill::input
    name="sitemap_base_url"
    label="Base URL Sitemap"
    default="{{ config('app.url') }}"
    note="URL dasar untuk sitemap. Kosongkan untuk pakai URL aplikasi default."
/>

<x-twill::checkbox
    name="sitemap_include_pages"
    label="Sertakan Pages"
    default="1"
/>

<x-twill::checkbox
    name="sitemap_include_posts"
    label="Sertakan Posts"
    default="1"
/>

<x-twill::checkbox
    name="sitemap_include_projects"
    label="Sertakan Projects"
    default="1"
/>
