@props(['announcement'])

@php
    $c = $announcement->priorityClasses();
@endphp

<article class="rounded-2xl border-2 {{ $c['border'] }} {{ $c['bg'] }} p-4 sm:p-5 transition-all hover:shadow-md">
    <div class="flex items-start gap-3">
        {{-- Icon --}}
        <div class="shrink-0 mt-0.5">
            @if($announcement->priority === 'urgent')
                <svg class="h-6 w-6 {{ $c['icon'] }}" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            @elseif($announcement->priority === 'important')
                <svg class="h-6 w-6 {{ $c['icon'] }}" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            @else
                <svg class="h-6 w-6 {{ $c['icon'] }}" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            @endif
        </div>

        {{-- Content --}}
        <div class="flex-1 min-w-0">
            {{-- Badges --}}
            <div class="flex flex-wrap items-center gap-2 mb-1.5">
                @if($announcement->is_pinned)
                    <span class="inline-flex items-center gap-1 rounded-full bg-slate-900 text-white text-[10px] font-bold px-2 py-0.5">
                        📌 PIN
                    </span>
                @endif
                <span class="inline-flex items-center gap-1 rounded-full {{ $c['badge'] }} text-[10px] font-bold px-2 py-0.5 uppercase tracking-wider">
                    {{ $announcement->priorityLabel() }}
                </span>
                @if($announcement->starts_at)
                    <span class="text-[10px] text-slate-500 font-medium">
                        {{ $announcement->starts_at->translatedFormat('d M Y, H:i') }}
                    </span>
                @endif
            </div>

            {{-- Title --}}
            <h3 class="text-sm sm:text-base font-bold {{ $c['text'] }} leading-tight mb-1">
                {{ $announcement->title }}
            </h3>

            {{-- Body --}}
            @if($announcement->body)
                <div class="text-xs sm:text-sm {{ $c['text'] }} opacity-90 leading-relaxed prose prose-sm max-w-none line-clamp-3">
                    {!! strip_tags($announcement->body, '<b><strong><i><em><u><br><p><span><a>') !!}
                </div>
            @endif

            {{-- CTA --}}
            @if($announcement->cta_label && $announcement->cta_url)
                <a href="{{ $announcement->cta_url }}"
                   class="inline-flex items-center gap-1 mt-2 text-xs font-bold {{ $c['icon'] }} hover:underline">
                    {{ $announcement->cta_label }}
                    <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                    </svg>
                </a>
            @endif
        </div>
    </div>
</article>
