@extends('site.layouts.master')

@section('title', isset($agenda) && $agenda->title ? $agenda->title . ' | SMKMUH1BLORA' : 'Detail Agenda | HSE & K3 Applications')

@push('head')
    <meta name="description" content="{{ \Illuminate\Support\Str::limit(strip_tags($agenda->meta_description ?? $agenda->description ?? $agenda->summary ?? $agenda->excerpt ?? ''), 160) }}">

    <meta property="og:type" content="article">
    <meta property="og:url" content="{{ request()->url() }}">
    <meta property="og:title" content="{{ $agenda->meta_title ?? \Illuminate\Support\Str::limit($agenda->title ?? $agenda->name ?? config('app.name'), 70) }}">
    <meta property="og:description" content="{{ \Illuminate\Support\Str::limit(strip_tags($agenda->meta_description ?? $agenda->description ?? $agenda->summary ?? $agenda->excerpt ?? ''), 160) }}">
    @if($agenda->hasImage('cover'))
        <meta property="og:image" content="{{ $agenda->image('cover', 'default') ?? $agenda->image('thumbnail', 'default') ?? '' }}">
    @endif

    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $agenda->meta_title ?? \Illuminate\Support\Str::limit($agenda->title ?? $agenda->name ?? config('app.name'), 70) }}">
    <meta name="twitter:description" content="{{ \Illuminate\Support\Str::limit(strip_tags($agenda->meta_description ?? $agenda->description ?? $agenda->summary ?? $agenda->excerpt ?? ''), 160) }}">
@endpush

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
@php
    $dateObj = $agenda->agenda_date ? \Carbon\Carbon::parse($agenda->agenda_date)->timezone('Asia/Jakarta') : null;

    $jamDetail = filled($agenda->time_slot ?? null)
        ? trim((string) $agenda->time_slot)
        : ($dateObj ? $dateObj->format('H:i') . ' WIB' : null);

    $tempatDetail = filled($agenda->venue ?? null)
        ? trim((string) $agenda->venue)
        : (filled($agenda->location ?? null) ? trim((string) $agenda->location) : null);

    $penyelenggaraDetail = filled($agenda->organizer ?? null)
        ? trim((string) $agenda->organizer)
        : null;

    $pesertaDetail = filled($agenda->participants ?? null)
        ? trim((string) $agenda->participants)
        : null;

    $rawExternalUrl = trim((string) ($agenda->external_url ?? ''));
    $safeExternalUrl = ($rawExternalUrl !== '' && preg_match('#^https?://#i', $rawExternalUrl) && filter_var($rawExternalUrl, FILTER_VALIDATE_URL))
        ? $rawExternalUrl
        : null;

    $rawDescription = (string) ($agenda->description ?? '');
    $hasHtmlTags = $rawDescription !== strip_tags($rawDescription);
@endphp

<main class="py-8 sm:py-10 px-4 sm:px-6 lg:px-8 max-w-4xl mx-auto relative overflow-hidden">

    <div class="absolute top-1/4 left-1/2 -translate-x-1/2 w-96 h-96 bg-emerald-500/10 blur-[140px] rounded-full pointer-events-none"></div>

    <div class="relative z-10 space-y-6">

        @include('site.partials.breadcrumb', ['items' => [
            ['label' => 'Agenda', 'url' => null],
            ['label' => $agenda->title, 'url' => null],
        ]])

        <div class="space-y-3">
            @if($dateObj)
                <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-slate-50 border border-slate-200 text-emerald-600 text-xs font-semibold shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    {{ $dateObj->translatedFormat('l, d F Y') }}
                </div>
            @endif

            <h1 class="text-3xl md:text-4xl font-extrabold text-slate-900 tracking-tight leading-tight">
                {{ $agenda->title }}
            </h1>
        </div>

        @if($jamDetail || $tempatDetail || $penyelenggaraDetail || $pesertaDetail || $safeExternalUrl)
            <div class="bg-white border border-slate-200 rounded-3xl p-5 sm:p-6 shadow-sm">
                <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                    @if($jamDetail)
                        <div class="space-y-1">
                            <dt class="text-xs font-bold uppercase tracking-wider text-slate-400">Jam</dt>
                            <dd class="font-semibold text-slate-800">{{ $jamDetail }}</dd>
                        </div>
                    @endif

                    @if($tempatDetail)
                        <div class="space-y-1">
                            <dt class="text-xs font-bold uppercase tracking-wider text-slate-400">Tempat</dt>
                            <dd class="font-semibold text-slate-800">{{ $tempatDetail }}</dd>
                        </div>
                    @endif

                    @if($penyelenggaraDetail)
                        <div class="space-y-1">
                            <dt class="text-xs font-bold uppercase tracking-wider text-slate-400">Penyelenggara</dt>
                            <dd class="font-semibold text-slate-800">{{ $penyelenggaraDetail }}</dd>
                        </div>
                    @endif

                    @if($pesertaDetail)
                        <div class="space-y-1">
                            <dt class="text-xs font-bold uppercase tracking-wider text-slate-400">Peserta</dt>
                            <dd class="font-semibold text-slate-800">{{ $pesertaDetail }}</dd>
                        </div>
                    @endif

                    @if($safeExternalUrl)
                        <div class="sm:col-span-2 space-y-1 pt-1 border-t border-slate-100">
                            <dt class="text-xs font-bold uppercase tracking-wider text-slate-400">URL</dt>
                            <dd>
                                <a
                                    href="{{ $safeExternalUrl }}"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    class="inline-flex items-center gap-1.5 font-semibold text-emerald-600 hover:text-emerald-700 underline underline-offset-4 break-all"
                                >
                                    <span>{{ $safeExternalUrl }}</span>
                                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                    </svg>
                                </a>
                            </dd>
                        </div>
                    @endif
                </dl>
            </div>
        @endif

        @if($agenda->hasImage('cover'))
            <div class="rounded-3xl overflow-hidden border border-slate-200 shadow-sm bg-slate-100 aspect-[16/9]">
                <img src="{{ $agenda->image('cover', 'default') }}" alt="{{ $agenda->title }}" class="w-full h-full object-cover">
            </div>
        @endif

        @if(filled(trim($rawDescription)))
            <div class="bg-white border border-slate-200 rounded-3xl p-6 sm:p-8 backdrop-blur-xl shadow-sm space-y-4 text-slate-600 leading-relaxed">
                <div class="text-xs font-bold uppercase tracking-wider text-slate-400">Deskripsi</div>
                @if($hasHtmlTags)
                    <div class="prose max-w-none text-slate-600 [&_strong]:text-slate-900">
                        {!! safe_html($rawDescription) !!}
                    </div>
                @else
                    <div class="text-slate-700 whitespace-pre-line leading-relaxed">{{ $rawDescription }}</div>
                @endif
            </div>
        @endif

        @if($agenda->blocks->count() > 0)
            <div class="space-y-8">
                {!! $agenda->renderBlocks() !!}
            </div>
        @endif

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
