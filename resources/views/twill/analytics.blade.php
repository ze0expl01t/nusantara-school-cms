@extends('twill::layouts.free')

@section('customPageContent')
<div style="padding: 24px; max-width: 1400px; margin: 0 auto;">

    {{-- HEADER --}}
    <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 24px;">
        <div style="display: flex; align-items: center; gap: 12px;">
            {{-- Heroicon: chart-bar --}}
            <div style="width: 44px; height: 44px; background: #ebf8ff; border-radius: 10px; display: flex; align-items: center; justify-content: center; color: #3182ce;">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" style="width: 24px; height: 24px;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75ZM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V8.625ZM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125Z" />
                </svg>
            </div>
            <div>
                <h1 style="font-size: 24px; font-weight: 700; color: #1a202c; margin: 0;">
                    Analytics Dashboard
                </h1>
                <p style="color: #718096; font-size: 14px; margin-top: 4px;">
                    Statistik Google Analytics — 30 hari terakhir
                </p>
            </div>
        </div>

        {{-- Refresh Button dengan Heroicon --}}
        <a href="{{ url()->current() }}"
           style="background: #3182ce; color: white; padding: 10px 18px; border-radius: 8px; text-decoration: none; font-weight: 600; font-size: 14px; display: inline-flex; align-items: center; gap: 8px;">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 16px; height: 16px;">
                <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99" />
            </svg>
            Refresh
        </a>
    </div>

    {{-- ERROR MESSAGE --}}
    @if(session('analytics_error'))
        <div style="background: #fff5f5; border: 1px solid #e53e3e; color: #c53030; padding: 12px 16px; border-radius: 8px; margin-bottom: 20px; display: flex; align-items: center; gap: 8px;">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 18px; height: 18px; flex-shrink: 0;">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z" />
            </svg>
            {{ session('analytics_error') }}
        </div>
    @endif

    {{-- STAT CARDS --}}
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 16px; margin-bottom: 24px;">

        {{-- Total Visitors --}}
        <div style="background: white; border: 1px solid #e2e8f0; border-radius: 12px; padding: 20px;">
            <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 8px;">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="#3182ce" style="width: 16px; height: 16px;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" />
                </svg>
                <div style="font-size: 11px; font-weight: 600; color: #718096; text-transform: uppercase; letter-spacing: 0.5px;">
                    Total Visitors
                </div>
            </div>
            <div style="font-size: 32px; font-weight: 700; color: #2d3748; line-height: 1;">
                {{ number_format($totalVisitors) }}
            </div>
            <div style="font-size: 12px; color: #a0aec0; margin-top: 6px;">
                30 hari terakhir
            </div>
        </div>

        {{-- Total Page Views --}}
        <div style="background: white; border: 1px solid #e2e8f0; border-radius: 12px; padding: 20px;">
            <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 8px;">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="#38a169" style="width: 16px; height: 16px;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                </svg>
                <div style="font-size: 11px; font-weight: 600; color: #718096; text-transform: uppercase; letter-spacing: 0.5px;">
                    Total Page Views
                </div>
            </div>
            <div style="font-size: 32px; font-weight: 700; color: #2d3748; line-height: 1;">
                {{ number_format($totalPageViews) }}
            </div>
            <div style="font-size: 12px; color: #a0aec0; margin-top: 6px;">
                30 hari terakhir
            </div>
        </div>

        {{-- Avg Views/User --}}
        <div style="background: white; border: 1px solid #e2e8f0; border-radius: 12px; padding: 20px;">
            <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 8px;">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="#805ad5" style="width: 16px; height: 16px;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.042 21.672 13.684 16.6m0 0-2.51 2.225.569-9.47 5.227 7.917-3.286-.672ZM12 2.25V4.5m5.834.166-1.591 1.591M20.25 10.5H18M7.757 14.743l-1.59 1.59M6 10.5H3.75m4.007-4.243-1.59-1.59" />
                </svg>
                <div style="font-size: 11px; font-weight: 600; color: #718096; text-transform: uppercase; letter-spacing: 0.5px;">
                    Avg. Views / User
                </div>
            </div>
            <div style="font-size: 32px; font-weight: 700; color: #2d3748; line-height: 1;">
                {{ $avgViewsPerUser }}
            </div>
            <div style="font-size: 12px; color: #a0aec0; margin-top: 6px;">
                Rata-rata interaksi
            </div>
        </div>

        {{-- Status GA4 --}}
        <div style="background: white; border: 1px solid #e2e8f0; border-radius: 12px; padding: 20px;">
            <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 8px;">
                @if($totalVisitors > 0)
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="#38a169" style="width: 16px; height: 16px;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                @else
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="#e53e3e" style="width: 16px; height: 16px;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z" />
                    </svg>
                @endif
                <div style="font-size: 11px; font-weight: 600; color: #718096; text-transform: uppercase; letter-spacing: 0.5px;">
                    Status GA4
                </div>
            </div>
            <div style="font-size: 20px; font-weight: 700; line-height: 1.3; color: {{ $totalVisitors > 0 ? '#38a169' : '#e53e3e' }};">
                {{ $totalVisitors > 0 ? 'Connected' : 'No Data' }}
            </div>
            <div style="font-size: 12px; color: #a0aec0; margin-top: 6px;">
                ID: {{ config('analytics.property_id', '-') }}
            </div>
        </div>

    </div>

    {{-- TREND CHART --}}
    <div style="background: white; border: 1px solid #e2e8f0; border-radius: 12px; padding: 24px; margin-bottom: 24px;">
        <div style="display: flex; align-items: center; gap: 8px; margin: 0 0 16px 0;">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="#3182ce" style="width: 18px; height: 18px;">
                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18 9 11.25l4.306 4.306a11.95 11.95 0 0 1 5.814-5.518l2.74-1.22m0 0-5.94-2.281m5.94 2.28-2.28 5.941" />
            </svg>
            <h2 style="font-size: 16px; font-weight: 700; margin: 0; color: #2d3748;">
                Trend Visitors & Page Views (30 Hari)
            </h2>
        </div>
        <canvas id="trendChart" height="80"></canvas>
    </div>

    {{-- TOP PAGES --}}
    <div style="background: white; border: 1px solid #e2e8f0; border-radius: 12px; padding: 24px; margin-bottom: 24px;">
        <div style="display: flex; align-items: center; gap: 8px; margin: 0 0 16px 0;">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="#dd6b20" style="width: 18px; height: 18px;">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.362 5.214A8.252 8.252 0 0 1 12 21 8.25 8.25 0 0 1 6.038 7.047 8.287 8.287 0 0 0 9 9.601a8.983 8.983 0 0 1 3.361-6.867 8.21 8.21 0 0 0 3 2.48Z" />
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 18a3.75 3.75 0 0 0 .495-7.468 5.99 5.99 0 0 0-1.925 3.547 5.975 5.975 0 0 1-2.133-1.001A3.75 3.75 0 0 0 12 18Z" />
            </svg>
            <h2 style="font-size: 16px; font-weight: 700; margin: 0; color: #2d3748;">
                Top 10 Halaman
            </h2>
        </div>
        @if(count($topPages) > 0)
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background: #f7fafc; text-align: left;">
                        <th style="padding: 10px 12px; font-size: 11px; font-weight: 600; color: #718096; text-transform: uppercase;">#</th>
                        <th style="padding: 10px 12px; font-size: 11px; font-weight: 600; color: #718096; text-transform: uppercase;">Judul</th>
                        <th style="padding: 10px 12px; font-size: 11px; font-weight: 600; color: #718096; text-transform: uppercase;">URL</th>
                        <th style="padding: 10px 12px; font-size: 11px; font-weight: 600; color: #718096; text-transform: uppercase; text-align: right;">Views</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($topPages as $i => $page)
                        <tr style="border-top: 1px solid #edf2f7;">
                            <td style="padding: 10px 12px; font-size: 13px; color: #a0aec0;">{{ $i + 1 }}</td>
                            <td style="padding: 10px 12px; font-size: 13px; color: #2d3748; font-weight: 600;">{{ $page['title'] }}</td>
                            <td style="padding: 10px 12px; font-size: 12px; color: #718096; font-family: monospace; word-break: break-all;">{{ $page['url'] }}</td>
                            <td style="padding: 10px 12px; font-size: 13px; color: #2d3748; font-weight: 700; text-align: right;">{{ number_format($page['pageViews']) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p style="color: #a0aec0; font-size: 14px; margin: 0;">Belum ada data.</p>
        @endif
    </div>

    {{-- GRID: 2 COLUMNS --}}
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 24px;">

        {{-- TOP REFERRERS --}}
        <div style="background: white; border: 1px solid #e2e8f0; border-radius: 12px; padding: 24px;">
            <div style="display: flex; align-items: center; gap: 8px; margin: 0 0 16px 0;">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="#3182ce" style="width: 18px; height: 18px;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.19 8.688a4.5 4.5 0 0 1 1.242 7.244l-4.5 4.5a4.5 4.5 0 0 1-6.364-6.364l1.757-1.757m13.35-.622 1.757-1.757a4.5 4.5 0 0 0-6.364-6.364l-4.5 4.5a4.5 4.5 0 0 0 1.242 7.244" />
                </svg>
                <h2 style="font-size: 16px; font-weight: 700; margin: 0; color: #2d3748;">
                    Top Referrers
                </h2>
            </div>
            @if(count($topReferrers) > 0)
                @foreach($topReferrers as $ref)
                    <div style="display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid #edf2f7; font-size: 13px;">
                        <span style="color: #4a5568; word-break: break-all; flex: 1;">{{ $ref['url'] }}</span>
                        <strong style="color: #2d3748; margin-left: 12px;">{{ number_format($ref['pageViews']) }}</strong>
                    </div>
                @endforeach
            @else
                <p style="color: #a0aec0; font-size: 14px; margin: 0;">Belum ada data.</p>
            @endif
        </div>

        {{-- TOP BROWSERS --}}
        <div style="background: white; border: 1px solid #e2e8f0; border-radius: 12px; padding: 24px;">
            <div style="display: flex; align-items: center; gap: 8px; margin: 0 0 16px 0;">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="#38a169" style="width: 18px; height: 18px;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9.004 9.004 0 0 0 8.716-6.747M12 21a9.004 9.004 0 0 1-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 0 1 7.843 4.582M12 3a8.997 8.997 0 0 0-7.843 4.582m15.686 0A11.953 11.953 0 0 1 12 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0 1 21 12c0 .778-.099 1.533-.284 2.253m0 0A17.919 17.919 0 0 1 12 16.5c-3.162 0-6.133-.815-8.716-2.247m0 0A9.015 9.015 0 0 1 3 12c0-1.605.42-3.113 1.157-4.418" />
                </svg>
                <h2 style="font-size: 16px; font-weight: 700; margin: 0; color: #2d3748;">
                    Top Browsers
                </h2>
            </div>
            @if(count($topBrowsers) > 0)
                @foreach($topBrowsers as $b)
                    <div style="display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid #edf2f7; font-size: 13px;">
                        <span style="color: #4a5568;">{{ $b['browser'] }}</span>
                        <strong style="color: #2d3748;">{{ number_format($b['pageViews']) }}</strong>
                    </div>
                @endforeach
            @else
                <p style="color: #a0aec0; font-size: 14px; margin: 0;">Belum ada data.</p>
            @endif
        </div>

        {{-- TOP COUNTRIES --}}
        <div style="background: white; border: 1px solid #e2e8f0; border-radius: 12px; padding: 24px;">
            <div style="display: flex; align-items: center; gap: 8px; margin: 0 0 16px 0;">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="#805ad5" style="width: 18px; height: 18px;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 6.75V15m6-6v8.25m.503 3.498 4.875-2.437c.381-.19.622-.58.622-1.006V4.82c0-.836-.88-1.38-1.628-1.006l-3.869 1.934c-.317.159-.69.159-1.006 0L9.503 3.252a1.125 1.125 0 0 0-1.006 0L3.622 5.689C3.24 5.88 3 6.27 3 6.695V19.18c0 .836.88 1.38 1.628 1.006l3.869-1.934c.317-.159.69-.159 1.006 0l4.994 2.497c.317.158.69.158 1.006 0Z" />
                </svg>
                <h2 style="font-size: 16px; font-weight: 700; margin: 0; color: #2d3748;">
                    Top Countries
                </h2>
            </div>
            @if(count($topCountries) > 0)
                @foreach($topCountries as $c)
                    <div style="display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid #edf2f7; font-size: 13px;">
                        <span style="color: #4a5568;">{{ $c['country'] }}</span>
                        <strong style="color: #2d3748;">{{ number_format($c['pageViews']) }}</strong>
                    </div>
                @endforeach
            @else
                <p style="color: #a0aec0; font-size: 14px; margin: 0;">Belum ada data.</p>
            @endif
        </div>

        {{-- TOP OS --}}
        <div style="background: white; border: 1px solid #e2e8f0; border-radius: 12px; padding: 24px;">
            <div style="display: flex; align-items: center; gap: 8px; margin: 0 0 16px 0;">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="#dd6b20" style="width: 18px; height: 18px;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 17.25v1.007a3 3 0 0 1-.879 2.122L7.5 21h9l-.621-.621A3 3 0 0 1 15 18.257V17.25m6-12V15a2.25 2.25 0 0 1-2.25 2.25H5.25A2.25 2.25 0 0 1 3 15V5.25m18 0A2.25 2.25 0 0 0 18.75 3H5.25A2.25 2.25 0 0 0 3 5.25m18 0V12a2.25 2.25 0 0 1-2.25 2.25H5.25A2.25 2.25 0 0 1 3 12V5.25" />
                </svg>
                <h2 style="font-size: 16px; font-weight: 700; margin: 0; color: #2d3748;">
                    Top Operating Systems
                </h2>
            </div>
            @if(count($topOS) > 0)
                @foreach($topOS as $os)
                    <div style="display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid #edf2f7; font-size: 13px;">
                        <span style="color: #4a5568;">{{ $os['os'] }}</span>
                        <strong style="color: #2d3748;">{{ number_format($os['pageViews']) }}</strong>
                    </div>
                @endforeach
            @else
                <p style="color: #a0aec0; font-size: 14px; margin: 0;">Belum ada data.</p>
            @endif
        </div>

    </div>

    {{-- INFO --}}
    <div style="margin-top: 20px; padding: 14px 18px; background: #ebf8ff; border-radius: 8px; font-size: 13px; color: #2b6cb0; line-height: 1.6; display: flex; align-items: flex-start; gap: 10px;">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 18px; height: 18px; flex-shrink: 0; margin-top: 1px;">
            <path stroke-linecap="round" stroke-linejoin="round" d="m11.25 11.25.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z" />
        </svg>
        <div>
            <strong>Info:</strong> Data diambil dari Google Analytics 4 (GA4) dengan cache 60 menit.
            Data delay ~1 jam dari GA4 realtime.
            Konfigurasi di <a href="{{ url('/admin/settings/list/general') }}" style="color: #2b6cb0; text-decoration: underline;">Settings → General</a>.
        </div>
    </div>

</div>

{{-- Chart.js --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
    (function() {
        const ctx = document.getElementById('trendChart').getContext('2d');
        const trendData = @json($dailyTrend);

        if (!trendData || trendData.length === 0) {
            document.getElementById('trendChart').style.display = 'none';
            document.getElementById('trendChart').insertAdjacentHTML('afterend', '<p style="color:#a0aec0; font-size:14px; text-align:center; padding:40px 0;">Belum ada data trend.</p>');
            return;
        }

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: trendData.map(d => d.label),
                datasets: [
                    {
                        label: 'Visitors',
                        data: trendData.map(d => d.visitors),
                        borderColor: '#3182ce',
                        backgroundColor: 'rgba(49, 130, 206, 0.1)',
                        tension: 0.4,
                        fill: true,
                        pointRadius: 3,
                        pointHoverRadius: 6,
                    },
                    {
                        label: 'Page Views',
                        data: trendData.map(d => d.pageViews),
                        borderColor: '#38a169',
                        backgroundColor: 'rgba(56, 161, 105, 0.1)',
                        tension: 0.4,
                        fill: true,
                        pointRadius: 3,
                        pointHoverRadius: 6,
                    },
                ],
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: { position: 'top' },
                    tooltip: { mode: 'index', intersect: false },
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { precision: 0 },
                    },
                },
                interaction: {
                    mode: 'nearest',
                    axis: 'x',
                    intersect: false,
                },
            },
        });
    })();
</script>
@stop