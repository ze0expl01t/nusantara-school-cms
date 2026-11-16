@props([
    'audience' => 'student',   // 'student' atau 'teacher'
    'limit'    => 5,
])

@php
    $announcements = \App\Models\Announcement::query()
        ->active()
        ->forAudience($audience)
        ->ordered()
        ->with('translations')
        ->limit($limit)
        ->get();
@endphp

@if($announcements->isNotEmpty())
    <section class="space-y-3" x-data="{ expanded: false }">
        {{-- Header --}}
        <div class="flex items-center justify-between">
            <h2 class="text-lg font-bold text-slate-900 flex items-center gap-2">
                <svg class="h-5 w-5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/>
                </svg>
                Pengumuman
                @if($announcements->count() > 0)
                    <span class="inline-flex items-center justify-center h-5 min-w-[20px] px-1.5 rounded-full bg-emerald-100 text-emerald-700 text-[10px] font-bold">
                        {{ $announcements->count() }}
                    </span>
                @endif
            </h2>
        </div>

        {{-- List --}}
        <div class="space-y-3">
            @foreach($announcements as $index => $announcement)
                <div @if($index >= 2) x-show="expanded" x-transition @endif>
                    <x-announcement-card :announcement="$announcement" />
                </div>
            @endforeach
        </div>

        {{-- Toggle "Lihat Semua" --}}
        @if($announcements->count() > 2)
            <button @click="expanded = !expanded"
                    class="w-full text-center text-xs font-semibold text-emerald-600 hover:text-emerald-700 py-2 transition-colors">
                <span x-text="expanded ? '− Sembunyikan' : '+ Lihat semua {{ $announcements->count() }} pengumuman'"></span>
            </button>
        @endif
    </section>
@endif
