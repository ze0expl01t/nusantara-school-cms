@php
    $safeInput = function ($block, $field, $default = null) {
        $value = $block->input($field);
        if (is_array($value)) {
            return $value[app()->getLocale()]
                ?? $value[config('app.fallback_locale')]
                ?? $default;
        }
        return filled($value) ? $value : $default;
    };

    $titleMain      = $safeInput($block, 'title_main', 'Pengalaman');
    $titleHighlight = $safeInput($block, 'title_highlight', '& Mutu Pendidikan');

    $stats = $block->children;
    $counterUid = 'ns_stats_' . ($block->id ?? uniqid());
@endphp

<section id="{{ $counterUid }}" class="py-8 sm:py-10 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto" data-block-reveal>
    <div class="space-y-6 sm:space-y-8">

        <div class="bg-white border border-slate-200 rounded-2xl sm:rounded-3xl p-6 sm:p-8 md:p-12 shadow-sm relative overflow-hidden [contain:layout_style]">

            {{-- Cahaya Latar — blur dikurangi --}}
            <div class="absolute top-0 left-1/2 -translate-x-1/2 w-3/4 h-20 sm:h-24 bg-emerald-500/5 blur-[50px] rounded-full pointer-events-none [transform:translate3d(-50%,0,0)]" aria-hidden="true"></div>

            <div class="relative z-10">

                @if($titleMain || $titleHighlight)
                    <h2 class="text-2xl sm:text-3xl md:text-4xl font-extrabold text-center mb-8 sm:mb-10 tracking-tight text-slate-900 px-2 [text-wrap:balance]">
                        {{ $titleMain }}
                        @if($titleHighlight)
                            <span class="text-emerald-600">{{ $titleHighlight }}</span>
                        @endif
                    </h2>
                @endif

                @if($stats && $stats->count() > 0)
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-y-6 sm:gap-y-8 gap-x-0 md:divide-x md:divide-slate-200">
                        @foreach($stats as $stat)
                            @php
                                $number = $safeInput($stat, 'number');
                                $suffix = $safeInput($stat, 'suffix');
                                $label  = $safeInput($stat, 'label');

                                $cleanNum = is_string($number) || is_numeric($number)
                                    ? preg_replace('/[^0-9.]/', '', (string) $number)
                                    : '';
                                $isNumericTarget = $cleanNum !== '' && is_numeric($cleanNum);
                                $decimals = ($isNumericTarget && str_contains($cleanNum, '.'))
                                    ? strlen(explode('.', $cleanNum)[1])
                                    : 0;
                                $initialDisplay = $isNumericTarget
                                    ? number_format(0, $decimals, '.', '')
                                    : (string) $number;
                            @endphp

                            <div class="flex flex-col items-center justify-center text-center group cursor-default px-2 sm:px-4">
                                <div class="flex items-baseline gap-1 transform transition-transform duration-300 group-hover:-translate-y-1.5 motion-reduce:transition-none motion-reduce:group-hover:translate-y-0 [transform:translateZ(0)]">

                                    @if($number !== null && $number !== '')
                                        <span
                                            class="text-3xl sm:text-4xl md:text-5xl font-black text-emerald-600 drop-shadow-sm tabular-nums leading-none"
                                            @if($isNumericTarget)
                                                data-ns-countup
                                                data-target="{{ $cleanNum }}"
                                                data-decimals="{{ $decimals }}"
                                                data-raw="{{ $number }}"
                                            @endif
                                        >{{ $initialDisplay }}</span>
                                    @endif

                                    @if($suffix)
                                        <span class="text-lg sm:text-xl md:text-2xl font-bold text-slate-500 leading-none">
                                            {{ $suffix }}
                                        </span>
                                    @endif

                                </div>

                                @if($label)
                                    <p class="mt-2.5 sm:mt-3 text-[10px] sm:text-xs md:text-sm font-bold tracking-[0.15em] sm:tracking-[0.2em] text-slate-500 uppercase group-hover:text-slate-800 transition-colors duration-300 leading-snug motion-reduce:transition-none">
                                        {{ $label }}
                                    </p>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="w-full text-center py-8 sm:py-10 text-slate-400 bg-slate-50 rounded-2xl sm:rounded-3xl border border-dashed border-slate-200">
                        <svg class="w-10 h-10 mx-auto text-slate-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                        <p class="text-sm font-medium">Belum ada data statistik yang ditambahkan melalui panel admin.</p>
                    </div>
                @endif

            </div>
        </div>
    </div>
</section>

<script>
(function () {
    const sectionEl = document.getElementById(@json($counterUid));
    if (!sectionEl) return;

    const counters = Array.from(sectionEl.querySelectorAll('[data-ns-countup]'));
    if (!counters.length) return;

    const prefersReducedMotion = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    const formatNumber = (val, decimals) => {
        if (decimals > 0) {
            return Number(val).toFixed(decimals);
        }
        return String(Math.round(val));
    };

    const runCounters = () => {
        if (sectionEl.dataset.nsCountupStarted === '1') return;
        sectionEl.dataset.nsCountupStarted = '1';

        counters.forEach((el) => {
            const target = parseFloat(el.dataset.target || '0');
            const decimals = parseInt(el.dataset.decimals || '0', 10) || 0;
            const raw = el.dataset.raw || String(target);

            if (!Number.isFinite(target)) {
                el.textContent = raw;
                return;
            }

            if (prefersReducedMotion || target === 0) {
                el.textContent = decimals > 0 ? target.toFixed(decimals) : raw;
                return;
            }

            const duration = 1200;
            const startTime = performance.now();

            const step = (now) => {
                const elapsed = now - startTime;
                const progress = Math.min(elapsed / duration, 1);
                const eased = 1 - Math.pow(1 - progress, 3);
                const current = target * eased;

                el.textContent = progress < 1
                    ? formatNumber(current, decimals)
                    : (decimals > 0 ? target.toFixed(decimals) : raw);

                if (progress < 1) {
                    requestAnimationFrame(step);
                }
            };

            requestAnimationFrame(step);
        });
    };

    if (!('IntersectionObserver' in window) || prefersReducedMotion) {
        runCounters();
        return;
    }

    const observer = new IntersectionObserver(
        (entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting || entry.boundingClientRect.top < window.innerHeight) {
                    runCounters();
                    observer.disconnect();
                }
            });
        },
        { threshold: 0.05 }
    );

    observer.observe(sectionEl);

    window.addEventListener('scroll', () => {
        if (sectionEl.dataset.nsCountupStarted === '1') return;
        const rect = sectionEl.getBoundingClientRect();
        if (rect.top < window.innerHeight * 0.92 && rect.bottom > 0) {
            runCounters();
            observer.disconnect();
        }
    }, { passive: true });
})();
</script>
