@extends('site.layouts.master')

@section('title', isset($brosur) && $brosur->title ? $brosur->title . ' | SMKMUH1BLORA' : 'Detail Brosur | HSE & K3 Applications')

@push('head')
    <meta name="description" content="{{ Str::limit($brosur->meta_description ?? $brosur->description ?? $brosur->summary ?? $brosur->excerpt ?? '', 160) }}">
    
    <meta property="og:type" content="article">
    <meta property="og:url" content="{{ request()->url() }}">
    <meta property="og:title" content="{{ $brosur->meta_title ?? Str::limit($brosur->title ?? $brosur->name ?? config('app.name'), 70) }}">
    <meta property="og:description" content="{{ Str::limit($brosur->meta_description ?? $brosur->description ?? $brosur->summary ?? $brosur->excerpt ?? '', 160) }}">
    @if($brosur->image('cover'))
        <meta property="og:image" content="{{ $brosur->image('cover', 'default') ?? $brosur->image('thumbnail', 'default') ?? '' }}">
    @endif

    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $brosur->meta_title ?? Str::limit($brosur->title ?? $brosur->name ?? config('app.name'), 70) }}">
    <meta name="twitter:description" content="{{ Str::limit($brosur->meta_description ?? $brosur->description ?? $brosur->summary ?? $brosur->excerpt ?? '', 160) }}">
@endpush
@section('meta_title',
    $brosur->meta_title
        ? $brosur->meta_title
        : ($brosur->title . ' | ' . config('app.name'))
)

@section('meta_description',
    $brosur->meta_description
        ? $brosur->meta_description
        : \Illuminate\Support\Str::limit(strip_tags($brosur->description ?? ''), 160)
)

@if($brosur->hasImage('og_image', 'default'))
    @section('og_image', $brosur->image('og_image', 'default'))
@endif

@section('content')
@php
    // ===== Meta brosur =====
    $locale = app()->getLocale();

    $dateObj = $brosur->brosur_date
        ? \Carbon\Carbon::parse($brosur->brosur_date)->locale($locale)->timezone('Asia/Jakarta')
        : null;

    $brosurTitle    = $brosur->title ?: 'Detail Brosur';
    $brosurImageAlt = trim(strip_tags($brosurTitle)) ?: 'Cover brosur';

    // Cover image
    $coverImage = $brosur->hasImage('cover')
        ? $brosur->image('cover', 'default')
        : null;

    // ===== FILE DOWNLOAD: extract URL + fallback =====
    $downloadUrl  = null;
    $downloadName = null;

    // 1. Coba method bawaan Twill (returns File model)
    try {
        $fileModel = $brosur->file('download_file');

        if ($fileModel) {
            $downloadName = $fileModel->filename ?? null;

            // Coba property `url` (kalau ada)
            $downloadUrl = $fileModel->url ?? null;

            // Fallback: bangun dari uuid
            if (!$downloadUrl && !empty($fileModel->uuid)) {
                $downloadUrl = asset('storage/uploads/' . ltrim($fileModel->uuid, '/'));
            }

            // Fallback: bangun dari path
            if (!$downloadUrl && !empty($fileModel->path)) {
                $downloadUrl = asset('storage/uploads/' . ltrim($fileModel->path, '/'));
            }
        }
    } catch (\Throwable $e) {
        // lanjut ke fallback
    }

    // 2. Fallback: query langsung ke pivot (kalau method file() gagal / null)
    if (!$downloadUrl) {
        try {
            $fileRow = \DB::table('twill_fileables')
                ->join('twill_files', 'twill_files.id', '=', 'twill_fileables.file_id')
                ->where('twill_fileables.fileable_type', 'App\Models\Brosur')
                ->where('twill_fileables.fileable_id', $brosur->id)
                ->where('twill_fileables.role', 'download_file')
                ->whereNull('twill_files.deleted_at')
                ->orderBy('twill_fileables.id', 'desc')
                ->select('twill_files.uuid', 'twill_files.filename')
                ->first();

            if ($fileRow && !empty($fileRow->uuid)) {
                $downloadUrl  = asset('storage/uploads/' . ltrim($fileRow->uuid, '/'));
                $downloadName = $fileRow->filename;
            }
        } catch (\Throwable $e) {
            $downloadUrl = null;
        }
    }

    // ===== Prose class =====
    $proseClass = 'text-slate-600 text-sm sm:text-base leading-relaxed '
        . '[&_p]:my-3 [&_p]:leading-relaxed '
        . '[&_strong]:font-bold [&_strong]:text-slate-900 '
        . '[&_em]:italic '
        . '[&_a]:text-emerald-600 [&_a]:underline [&_a]:underline-offset-2 hover:[&_a]:text-emerald-700 '
        . '[&_ul]:list-disc [&_ul]:pl-5 [&_ul]:my-3 [&_ul]:space-y-1 '
        . '[&_ol]:list-decimal [&_ol]:pl-5 [&_ol]:my-3 [&_ol]:space-y-1 '
        . '[&_li]:leading-relaxed '
        . '[&_blockquote]:border-l-4 [&_blockquote]:border-emerald-300 [&_blockquote]:pl-4 [&_blockquote]:italic [&_blockquote]:text-slate-500 [&_blockquote]:my-4 '
        . '[&_h2]:font-bold [&_h2]:text-slate-900 [&_h2]:text-xl sm:[&_h2]:text-2xl [&_h2]:mt-6 [&_h2]:mb-3 '
        . '[&_h3]:font-bold [&_h3]:text-slate-900 [&_h3]:text-lg sm:[&_h3]:text-xl [&_h3]:mt-5 [&_h3]:mb-2 '
        . '[&_h4]:font-bold [&_h4]:text-slate-900 [&_h4]:text-base sm:[&_h4]:text-lg [&_h4]:mt-4 [&_h4]:mb-2 '
        . '[&_img]:rounded-xl [&_img]:my-4 [&_img]:shadow-sm '
        . '[&_code]:bg-slate-100 [&_code]:text-emerald-700 [&_code]:px-1.5 [&_code]:py-0.5 [&_code]:rounded [&_code]:text-sm '
        . '[&_pre]:bg-slate-900 [&_pre]:text-slate-100 [&_pre]:p-4 [&_pre]:rounded-xl [&_pre]:overflow-x-auto [&_pre]:my-4 '
        . '[&_hr]:my-6 [&_hr]:border-slate-200';

    // ===== Next brosur =====
    $nextSlug = isset($nextBrosur)
        ? ($nextBrosur->slug
            ?? optional($nextBrosur->slugs->where('locale', $locale)->first())->slug
            ?? optional($nextBrosur->slugs->first())->slug)
        : null;
@endphp

<main class="py-8 sm:py-10 lg:py-12 px-4 sm:px-6 lg:px-8 max-w-4xl mx-auto relative overflow-hidden">

    {{-- Efek Ambient Glow --}}
    <div class="absolute top-1/4 left-1/2 -translate-x-1/2 w-64 h-64 sm:w-96 sm:h-96 bg-emerald-500/10 blur-[140px] rounded-full pointer-events-none"></div>

    <div class="relative z-10 space-y-5 sm:space-y-6">

        {{-- Breadcrumb Navigation --}}
        @include('site.partials.breadcrumb', ['items' => [
            ['label' => 'Brosur', 'url' => null],
            ['label' => $brosurTitle, 'url' => null],
        ]])

        {{-- Header Brosur --}}
        <div class="space-y-3">

            @if($dateObj)
                <div class="inline-flex items-center gap-2 px-3 sm:px-3.5 py-1.5 rounded-full bg-slate-50 border border-slate-200 text-emerald-600 text-[11px] sm:text-xs font-semibold shadow-sm">
                    <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    {{ $dateObj->translatedFormat('F d, Y') }}
                </div>
            @endif

            <h1 class="text-2xl sm:text-3xl md:text-4xl font-extrabold text-slate-900 tracking-tight leading-tight">
                {{ $brosurTitle }}
            </h1>
        </div>

        {{-- Cover Image --}}
        @if($coverImage)
            <div class="rounded-2xl sm:rounded-3xl overflow-hidden border border-slate-200 shadow-sm bg-slate-100 aspect-[16/9]">
                <img
                    src="{{ $coverImage }}"
                    alt="{{ $brosurImageAlt }}"
                    loading="eager"
                    fetchpriority="high"
                    decoding="async"
                    class="w-full h-full object-cover"
                >
            </div>
        @endif

        {{-- Tombol Download File --}}
        @if($downloadUrl)
            <div class="p-5 sm:p-6 bg-white border border-emerald-500/30 rounded-2xl sm:rounded-3xl flex flex-col sm:flex-row items-center justify-between gap-4 shadow-sm">

                <div class="flex items-center gap-3 w-full sm:w-auto">
                    <div class="w-11 h-11 sm:w-12 sm:h-12 rounded-2xl bg-emerald-50 border border-emerald-200 flex items-center justify-center text-emerald-600 shrink-0 shadow-sm">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <div class="min-w-0">
                        <h4 class="text-slate-900 font-bold text-sm">Unduh Berkas Brosur</h4>
                        <p class="text-slate-500 text-xs truncate">
                            {{ $downloadName ?: 'Format file tersedia untuk diunduh langsung.' }}
                        </p>
                    </div>
                </div>

                <a
                    href="{{ $downloadUrl }}"
                    target="_blank"
                    rel="noopener noreferrer"
                    data-analytics="brosur-download"
                    data-brosur-id="{{ $brosur->id }}"
                    aria-label="Download file brosur {{ $brosurTitle }}"
                    class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-5 sm:px-6 py-3 rounded-2xl bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs uppercase tracking-wider transition-all shadow-md shadow-emerald-600/25 focus:outline-none focus-visible:ring-2 focus-visible:ring-emerald-500 focus-visible:ring-offset-2 motion-reduce:transition-none"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                    </svg>
                    Download File
                </a>
            </div>
        @endif

        {{-- Konten WYSIWYG --}}
        @if($brosur->description)
            <div class="bg-white border border-slate-200 rounded-2xl sm:rounded-3xl p-5 sm:p-6 md:p-8 shadow-sm">
                <div class="{{ $proseClass }}">
                    {!! safe_html($brosur->description) !!}
                </div>
            </div>
        @endif

        {{-- Tombol Navigasi Next Brosur --}}
        @if(isset($nextBrosur) && $nextSlug)
            <div class="pt-4 sm:pt-6 border-t border-slate-200 flex items-center justify-end">
                <a
                    href="{{ route('brosur.show', $nextSlug) }}"
                    data-analytics="brosur-next-click"
                    data-brosur-id="{{ $nextBrosur->id }}"
                    aria-label="Brosur selanjutnya: {{ $nextBrosur->title }}"
                    class="group inline-flex items-center gap-3 px-4 sm:px-5 py-3 rounded-2xl bg-white border border-slate-200 hover:border-emerald-500 hover:bg-slate-50 text-slate-700 transition-all duration-300 shadow-sm focus:outline-none focus-visible:ring-2 focus-visible:ring-emerald-500 focus-visible:ring-offset-2 motion-reduce:transition-none"
                >
                    <div class="text-right min-w-0">
                        <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider">Brosur Selanjutnya</span>
                        <span class="block text-xs sm:text-sm font-bold text-slate-900 group-hover:text-emerald-600 transition-colors max-w-[180px] sm:max-w-xs truncate">
                            {{ $nextBrosur->title }}
                        </span>
                    </div>
                    <div class="w-9 h-9 rounded-xl bg-emerald-50 border border-emerald-200 group-hover:border-emerald-500 group-hover:bg-emerald-100 flex items-center justify-center text-emerald-600 shrink-0 transition-all duration-300 shadow-sm motion-reduce:transition-none">
                        <svg class="w-4 h-4 transform group-hover:translate-x-0.5 transition-transform motion-reduce:transition-none motion-reduce:group-hover:translate-x-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </div>
                </a>
            </div>
        @endif

    </div>
</main>
@endsection
