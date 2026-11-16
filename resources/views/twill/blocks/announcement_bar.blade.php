@twillBlockTitle('Announcement Bar')
@twillBlockIcon('image-text')
@twillBlockGroup('app')

<x-twill::input
    name="text"
    label="Teks Pengumuman"
    placeholder="Contoh: PPDB 2026/2027 telah dibuka! Daftar sekarang."
    :translated="true"
    :maxlength="250"
    required
/>

<x-twill::input
    name="highlight_text"
    label="Kata Sorotan (opsional)"
    placeholder="Contoh: GRATIS BIAYA PENDAFTARAN"
    :translated="true"
    :maxlength="60"
    note="Ditampilkan dengan warna berbeda / badge kecil."
/>

<hr style="margin: 24px 0; border: none; border-top: 1px solid #e2e8f0;">

<x-twill::select
    name="style"
    label="Style Warna"
    default="emerald"
    :options="[
        ['value' => 'emerald', 'label' => 'Emerald (info / ajakan)'],
        ['value' => 'blue', 'label' => 'Blue (info umum)'],
        ['value' => 'amber', 'label' => 'Amber (peringatan)'],
        ['value' => 'red', 'label' => 'Red (urgent / penting)'],
        ['value' => 'dark', 'label' => 'Dark (netral)'],
    ]"
/>

<x-twill::select
    name="icon"
    label="Icon"
    default="megaphone"
    :options="[
        ['value' => 'none', 'label' => 'Tanpa icon'],
        ['value' => 'megaphone', 'label' => 'Megaphone (pengumuman)'],
        ['value' => 'sparkles', 'label' => 'Sparkles (baru / promo)'],
        ['value' => 'alert', 'label' => 'Alert (peringatan)'],
        ['value' => 'info', 'label' => 'Info'],
        ['value' => 'calendar', 'label' => 'Calendar (event)'],
    ]"
/>

<hr style="margin: 24px 0; border: none; border-top: 1px solid #e2e8f0;">

<x-twill::input
    name="button_text"
    label="Tombol — Text (opsional)"
    placeholder="Contoh: Daftar Sekarang"
    :translated="true"
    :maxlength="50"
/>

<x-twill::input
    name="button_url"
    label="Tombol — URL (opsional)"
    placeholder="/ppdb"
/>

<x-twill::select
    name="button_style"
    label="Style Tombol"
    default="solid"
    :options="[
        ['value' => 'solid', 'label' => 'Solid'],
        ['value' => 'outline', 'label' => 'Outline'],
        ['value' => 'link', 'label' => 'Link'],
    ]"
/>

<hr style="margin: 24px 0; border: none; border-top: 1px solid #e2e8f0;">



<x-twill::checkbox
    name="show_marquee"
    label="Mode Marquee (teks berjalan)"
    default="false"
/>

<x-twill::select
    name="marquee_speed"
    label="Kecepatan Marquee"
    default="normal"
    :options="[
        ['value' => 'slow', 'label' => 'Lambat'],
        ['value' => 'normal', 'label' => 'Normal'],
        ['value' => 'fast', 'label' => 'Cepat'],
    ]"
/>

<x-twill::checkbox
    name="hide_on_mobile"
    label="Sembunyikan di mobile"
    default="false"
/>
