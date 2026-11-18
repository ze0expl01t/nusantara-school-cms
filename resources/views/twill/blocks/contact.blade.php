@twillBlockTitle('Contact')
@twillBlockIcon('text')
@twillBlockGroup('app')

{{-- ============ HEADER ============ --}}
<x-twill::input
    name="title"
    label="Section Title"
    placeholder="Contoh: Hubungi Kami"
    :translated="true"
/>
<p style="font-size:12px;color:#718096;margin-top:6px;margin-bottom:16px;line-height:1.5;">
    Judul utama section kontak.
</p>

<x-twill::wysiwyg
    type="quill"
    name="subtitle"
    label="Subtitle / Description"
    :toolbar-options="['bold', 'italic']"
    :translated="true"
/>
<p style="font-size:12px;color:#718096;margin-top:6px;margin-bottom:16px;line-height:1.5;">
    Hanya <strong>bold</strong> &amp; <em>italic</em> yang diizinkan untuk keamanan.
</p>

<hr class="my-4">

{{-- ============ CONTACT DETAILS ============ --}}
<h4 class="text-primary font-bold mb-3">Contact Details</h4>

<x-twill::input
    name="address"
    label="Head Office Address"
    placeholder="Contoh: Jl. Gatot Subroto Km.1, Blora, Jawa Tengah"
    :translated="true"
/>
<p style="font-size:12px;color:#718096;margin-top:6px;margin-bottom:16px;line-height:1.5;">
    Boleh multi-baris — tekan Enter untuk baris baru.
</p>

<x-twill::input
    name="phone"
    label="Direct Line Phone Number"
    placeholder="Contoh: +62 812 3456 7890"
/>
<p style="font-size:12px;color:#718096;margin-top:6px;margin-bottom:16px;line-height:1.5;">
    Gunakan format <code style="background:#f1f5f9;padding:1px 4px;border-radius:3px;">+62</code>
    agar link WhatsApp benar. Nomor dengan awalan <code>0</code> otomatis dikonversi ke <code>62</code>.
</p>

<x-twill::input
    name="email"
    label="Support Desk Email"
    placeholder="Contoh: info@perusahaan.sch.id"
/>
<p style="font-size:12px;color:#718096;margin-top:6px;margin-bottom:16px;line-height:1.5;">
    Email tujuan untuk link <code>mailto:</code>.
</p>

<hr class="my-4">

{{-- ============ GOOGLE MAPS ============ --}}
<h4 class="text-primary font-bold mb-3">Google Maps</h4>

<x-twill::input
    name="map_embed_url"
    label="Google Maps Embed URL (Iframe src)"
    placeholder="https://www.google.com/maps/embed?pb=..."
/>
<p style="font-size:12px;color:#718096;margin-top:6px;margin-bottom:12px;line-height:1.5;">
    Kosongkan untuk pakai peta default perusahaan. Tempel <strong>hanya URL</strong> — bukan tag
    <code style="background:#f1f5f9;padding:1px 4px;border-radius:3px;">&lt;iframe&gt;</code>.
</p>

<div style="background:#fef3c7;border-left:4px solid #f59e0b;padding:12px 16px;border-radius:4px;margin-bottom:20px;">
    <p style="font-size:13px;color:#78350f;margin:0 0 6px 0;font-weight:600;">
        📌 Cara ambil URL yang benar:
    </p>
    <ol style="font-size:12px;color:#78350f;margin:0;padding-left:20px;line-height:1.7;">
        <li>Buka <strong>Google Maps</strong> → cari lokasi</li>
        <li>Klik <strong>Share</strong> → tab <strong>Embed a map</strong></li>
        <li>Copy <strong>hanya URL</strong> di dalam <code style="background:#fde68a;padding:1px 4px;border-radius:2px;">src="..."</code></li>
        <li>Paste di field di atas — <strong style="color:#dc2626;">tanpa tag &lt;iframe&gt;</strong></li>
    </ol>
</div>

<hr class="my-4">

{{-- ============ CTA BUTTONS ============ --}}
<h4 class="text-primary font-bold mb-3">CTA Buttons</h4>

<p style="font-size:13px;color:#64748b;margin-bottom:20px;line-height:1.5;">
    Kosongkan teks <strong>atau</strong> URL jika tidak ingin menampilkan tombol tertentu.
    URL harus diawali <code>https://</code>, <code>mailto:</code>, <code>tel:</code>, atau <code>#</code>.
</p>

{{-- CTA 1 --}}
<div class="row">
    <div class="col-5">
        <x-twill::input
            name="cta_1_text"
            label="CTA 1 — Text"
            placeholder="Tanya Pendaftaran"
            :translated="true"
        />
    </div>
    <div class="col-7">
        <x-twill::input
            name="cta_1_url"
            label="CTA 1 — URL"
            placeholder="https://wa.me/62812345678?text=..."
        />
    </div>
</div>
<p style="font-size:12px;color:#718096;margin-top:6px;margin-bottom:16px;line-height:1.5;">
    Kosongkan URL untuk pakai WhatsApp default dari nomor telepon di atas.
</p>

{{-- CTA 2 --}}
<div class="row">
    <div class="col-5">
        <x-twill::input
            name="cta_2_text"
            label="CTA 2 — Text"
            placeholder="Info Jurusan"
            :translated="true"
        />
    </div>
    <div class="col-7">
        <x-twill::input
            name="cta_2_url"
            label="CTA 2 — URL"
            placeholder="#jurusan atau /halaman-jurusan"
        />
    </div>
</div>

{{-- CTA 3 --}}
<div class="row">
    <div class="col-5">
        <x-twill::input
            name="cta_3_text"
            label="CTA 3 — Text"
            placeholder="Info Biaya"
            :translated="true"
        />
    </div>
    <div class="col-7">
        <x-twill::input
            name="cta_3_url"
            label="CTA 3 — URL"
            placeholder="#biaya atau /halaman-biaya"
        />
    </div>
</div>
