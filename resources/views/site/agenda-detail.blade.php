@extends('site.layouts.master')

@section('title', isset($agenda) && $agenda->title ? $agenda->title . ' | SMKMUH1BLORA' : 'Detail Agenda | HSE & K3 Applications')

@push('head')
    <meta name="description" content="{{ Str::limit($agenda->meta_description ?? $agenda->description ?? $agenda->summary ?? $agenda->excerpt ?? '', 160) }}">
    
    <meta property="og:type" content="article">
    <meta property="og:url" content="{{ request()->url() }}">
    <meta property="og:title" content="{{ $agenda->meta_title ?? Str::limit($agenda->title ?? $agenda->name ?? config('app.name'), 70) }}">
    <meta property="og:description" content="{{ Str::limit($agenda->meta_description ?? $agenda->description ?? $agenda->summary ?? $agenda->excerpt ?? '', 160) }}">
    @if($agenda->image('cover'))
        <meta property="og:image" content="{{ $agenda->image('cover', 'default') ?? $agenda->image('thumbnail', 'default') ?? '' }}">
    @endif

    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $agenda->meta_title ?? Str::limit($agenda->title ?? $agenda->name ?? config('app.name'), 70) }}">
    <meta name="twitter:description" content="{{ Str::limit($agenda->meta_description ?? $agenda->description ?? $agenda->summary ?? $agenda->excerpt ?? '', 160) }}">
@endpush}
@section('meta_title',
    $agenda->meta_title
        ? $agenda->meta_title
        : \Illuminate\Support\Str::limit($agenda->title . ' | ' . config('app.name'), 70)
)

@section('meta_description',
    $agenda->meta_description
        ? $agenda->meta_description
        : \Illuminate\Support\Str::limit(strip_tags($agenda->description ?? ''), 160)
)

@if($agenda->hasImage('og_image', 'default'))
    @section('og_image', $agenda->image('og_image', 'default'))
@endif

@section('content')
<main class="py-8 sm:py-10 px-4 sm:px-6 lg:px-8 max-w-4xl mx-auto relative overflow-hidden">

    <!-- Efek Ambient Glow Latar Belakang -->
    <div class="absolute top-1/4 left-1/2 -translate-x-1/2 w-96 h-96 bg-emerald-500/10 blur-[140px] rounded-full pointer-events-none"></div>

    <div class="relative z-10 space-y-6">

        <!-- Breadcrumb Navigation -->
        @include('site.partials.breadcrumb', ['items' => [
            ['label' => 'Agenda', 'url' => null],
            ['label' => $agenda->title, 'url' => null],
        ]])

        <!-- Header Detail Agenda -->
        <div class="space-y-3">
            @php
                $dateObj = $agenda->agenda_date ? \Carbon\Carbon::parse($agenda->agenda_date) : null;
            @endphp

            @if($dateObj)
                <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-slate-50 border border-slate-200 text-emerald-600 text-xs font-semibold shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    {{ $dateObj->translatedFormat('l, d F Y - H:i') }} WIB
                </div>
            @endif

            <h1 class="text-3xl md:text-4xl font-extrabold text-slate-900 tracking-tight leading-tight">
                {{ $agenda->title }}
            </h1>

            @if($agenda->location)
                <div class="flex items-center gap-2 text-slate-600 text-sm bg-white px-4 py-2.5 rounded-2xl border border-slate-200 w-fit shadow-sm">
                    <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    <span><strong>Lokasi:</strong> {{ $agenda->location }}</span>
                </div>
            @endif
        </div>

        <!-- Gambar Utama / Banner Agenda (jika ada) -->
        @if($agenda->hasImage('cover'))
            <div class="rounded-3xl overflow-hidden border border-slate-200 shadow-sm bg-slate-100 aspect-[16/9]">
                <img src="{{ $agenda->image('cover', 'default') }}" alt="{{ $agenda->title }}" class="w-full h-full object-cover">
            </div>
        @endif

        <!-- Konten Deskripsi Lengkap -->
        <div class="bg-white border border-slate-200 rounded-3xl p-6 sm:p-8 backdrop-blur-xl shadow-sm space-y-4 text-slate-600 leading-relaxed">
            <div class="prose max-w-none text-slate-600 [&_strong]:text-slate-900">
                {!! safe_html($agenda->description) !!}
            </div>
        </div>

        <!-- Render Blok Tambahan (Jika Agenda Mendukung Block Editor Twill) -->
        @if($agenda->blocks->count() > 0)
            <div class="space-y-8">
                {!! $agenda->renderBlocks() !!}
            </div>
        @endif

        <!-- Tombol Navigasi Next Agenda -->
        @php
            $nextSlug = isset($nextAgenda) ? ($nextAgenda->slug ?? optional($nextAgenda->slugs->first())->slug) : null;
        @endphp

        @if(isset($nextAgenda) && $nextSlug)
            <div class="pt-4 border-t border-slate-200 flex items-center justify-end">
                <a href="{{ route('agenda.show', $nextSlug) }}" class="group inline-flex items-center gap-3 px-5 py-3 rounded-2xl bg-white border border-slate-200 hover:border-emerald-500 hover:bg-slate-50 text-slate-700 transition-all duration-300 shadow-sm">
                    <div class="text-right">
                        <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider">Agenda Selanjutnya</span>
                        <span class="block text-sm font-bold text-slate-900 group-hover:text-emerald-600 transition-colors max-w-xs truncate">{{ $nextAgenda->title }}</span>
                    </div>
                    <div class="w-9 h-9 rounded-xl bg-emerald-50 border border-emerald-200 group-hover:border-emerald-500 group-hover:bg-emerald-100 flex items-center justify-center text-emerald-600 shrink-0 transition-all duration-300 shadow-sm">
                        <svg class="w-4 h-4 transform group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </div>
                </a>
            </div>
        @endif

    </div>
</main>
@endsection