@php
    use App\Models\Agenda;
    use Illuminate\Support\Str;
    use Carbon\Carbon;

    $safeInput = function ($block, $field, $default = null) {
        $value = $block->input($field);
        if (is_array($value)) {
            return $value[app()->getLocale()]
                ?? $value[config('app.fallback_locale')]
                ?? $default;
        }
        return $value ?: $default;
    };

    $sectionTitle = $safeInput($block, 'title', 'Agenda Kegiatan');
    $limit = (int) $safeInput($block, 'limit', 6);

    // Eager load slugs → hindari N+1 query
    $agendas = Agenda::where('published', true)
        ->with(['slugs'])
        ->orderBy('agenda_date', 'asc')
        ->take($limit)
        ->get();

    // Set locale Carbon untuk translatedFormat
    $locale = app()->getLocale();
    Carbon::setLocale($locale);

    // ═══ Prefetch URL agenda terdekat via browser resource hints ═══
    // Ambil 1-2 URL pertama untuk di-prefetch (bukan via JS fetch)
    $prefetchUrls = $agendas->take(2)->map(function ($agenda) use ($locale) {
        $slug = $agenda->slug
            ?? optional($agenda->slugs->where('locale', $locale)->first())->slug
            ?? optional($agenda->slugs->first())->slug;
        return $slug ? url('/agenda/' . $slug) : null;
    })->filter()->values();
@endphp

{{-- ═══ Resource hints: prefetch agenda terdekat (better than JS fetch) ═══ --}}
@push('head')
    @foreach($prefetchUrls as $url)
        <link rel="prefetch" href="{{ $url }}">
    @endforeach
@endpush

<section class="py-8 sm:py-10 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto relative overflow-hidden [content-visibility:auto] [contain-intrinsic-size:700px]">

    <!-- Efek Ambient Glow — kurangi blur radius untuk hemat GPU paint -->
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[300px] h-[300px] sm:w-[500px] sm:h-[500px] bg-emerald-500/10 blur-[80px] rounded-full pointer-events-none [transform:translate3d(-50%,-50%,0)]"></div>

    <div class="relative z-10 space-y-10 sm:space-y-12">

        <!-- Judul Section Modern -->
        @if($sectionTitle)
            <div class="flex flex-col items-center text-center space-y-3">
                <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-white border border-slate-200 shadow-sm text-xs font-semibold text-slate-600">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse motion-reduce:animate-none" aria-hidden="true"></span>
                    Jadwal & Kegiatan
                </div>
                <h2 class="text-2xl sm:text-3xl md:text-4xl font-extrabold text-slate-900 tracking-tight px-2">
                    {{ $sectionTitle }}
                </h2>
                <div class="w-20 h-1 bg-gradient-to-r from-transparent via-emerald-500 to-transparent rounded-full" aria-hidden="true"></div>
            </div>
        @endif

        <!-- Grid List Agenda Modern -->
        @if($agendas->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5 sm:gap-6">
                @foreach($agendas as $agenda)
                    @php
                        $dateObj = $agenda->agenda_date
                            ? Carbon::parse($agenda->agenda_date)
                                ->locale($locale)
                                ->timezone('Asia/Jakarta')
                            : null;

                        // Handle slug: akses accessor atau relasi slugs
                        $slugValue = $agenda->slug
                            ?? optional($agenda->slugs->where('locale', $locale)->first())->slug
                            ?? optional($agenda->slugs->first())->slug;
                        $agendaUrl = $slugValue ? url('/agenda/' . $slugValue) : '#';

                        // Excerpt deskripsi — bersihkan HTML + limit
                        $excerpt = $agenda->description
                            ? Str::limit(trim(strip_tags($agenda->description)), 160)
                            : null;
                    @endphp

                    <!-- Kartu Agenda Dibungkus Tautan Single View -->
                    <a
                        href="{{ $agendaUrl }}"
                        data-analytics="agenda-click"
                        data-agenda-id="{{ $agenda->id }}"
                        data-agenda-title="{{ $agenda->title }}"
                        aria-label="Lihat detail agenda: {{ $agenda->title }}"
                        class="bg-white border border-slate-200 hover:border-emerald-500 hover:shadow-xl hover:shadow-emerald-600/10 rounded-2xl sm:rounded-3xl p-5 sm:p-6 transition-all duration-300 group flex flex-col justify-between space-y-5 sm:space-y-6 hover:-translate-y-1.5 focus:outline-none focus-visible:ring-2 focus-visible:ring-emerald-500 focus-visible:ring-offset-2 motion-reduce:transition-none motion-reduce:hover:translate-y-0 [contain:layout_style] [transform:translateZ(0)]"
                    >

                        <div class="space-y-4">
                            <!-- Header Kartu: Tanggal & Waktu -->
                            <div class="flex items-start justify-between gap-3">
                                @if($dateObj)
                                    <div class="flex items-center gap-3">
                                        <div class="w-12 h-12 rounded-2xl bg-emerald-50 border border-emerald-100 group-hover:border-emerald-300 group-hover:bg-emerald-100 flex flex-col items-center justify-center text-emerald-600 shrink-0 transition-all duration-300 shadow-sm">
                                            <span class="text-[10px] font-bold leading-none uppercase tracking-wider">
                                                {{ $dateObj->translatedFormat('M') }}
                                            </span>
                                            <span class="text-base font-black leading-tight">
                                                {{ $dateObj->format('d') }}
                                            </span>
                                        </div>
                                        <div>
                                            <span class="text-xs font-semibold text-slate-500 block">
                                                {{ $dateObj->translatedFormat('l') }}
                                            </span>
                                            <span class="text-xs font-bold text-emerald-600">
                                                {{ $dateObj->format('H:i') }} WIB
                                            </span>
                                        </div>
                                    </div>
                                @endif

                                <span class="px-2.5 py-1 rounded-full bg-slate-100 border border-slate-200 text-[10px] font-bold text-slate-600 uppercase tracking-wider shrink-0">
                                    Agenda
                                </span>
                            </div>

                            <!-- Judul Agenda -->
                            <h3 class="text-base sm:text-lg md:text-xl font-bold text-slate-900 group-hover:text-emerald-600 transition-colors duration-300 leading-snug line-clamp-2">
                                {{ $agenda->title }}
                            </h3>

                            <!-- Lokasi -->
                            @if($agenda->location)
                                <div class="flex items-center gap-2 text-slate-600 text-xs bg-slate-50 px-3 py-2 rounded-xl border border-slate-100">
                                    <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    <span class="truncate">{{ $agenda->location }}</span>
                                </div>
                            @endif

                            <!-- Deskripsi Singkat -->
                            @if($excerpt)
                                <p class="text-slate-600 text-sm line-clamp-3 leading-relaxed">
                                    {{ $excerpt }}
                                </p>
                            @endif
                        </div>

                        <!-- Footer Kartu -->
                        <div class="pt-4 border-t border-slate-100 flex items-center justify-between text-xs font-semibold text-slate-500 group-hover:text-emerald-600 transition-colors">
                            <span>Detail Kegiatan</span>
                            <svg class="w-4 h-4 transform group-hover:translate-x-1 transition-transform duration-300 motion-reduce:transition-none motion-reduce:group-hover:translate-x-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                            </svg>
                        </div>

                    </a>
                @endforeach
            </div>
        @else
            <div class="text-center py-16 bg-white border border-slate-200 rounded-3xl shadow-sm">
                <svg class="w-12 h-12 mx-auto text-slate-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                <p class="text-slate-500 text-sm font-medium">Belum ada agenda kegiatan yang dipublikasikan saat ini.</p>
            </div>
        @endif

    </div>
</section>
