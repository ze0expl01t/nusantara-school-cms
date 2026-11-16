<?php

namespace App\Http\Controllers\Twill;

use A17\Twill\Http\Controllers\Admin\Controller;
use Illuminate\Contracts\View\View;
use Spatie\Analytics\Facades\Analytics;
use Spatie\Analytics\Period;

class AnalyticsController extends Controller
{
    public function __construct()
    {
        parent::__construct();

        // Pakai gate backup-access (bisa disesuaikan)
        $this->middleware('can:backup-access');
    }

    public function index(): View
    {
        $period = Period::days(30);

        try {
            /* === AGGREGATE STATS (30 hari) === */
            $totals = Analytics::fetchTotalVisitorsAndPageViews($period);

            $totalVisitors = $totals->sum('activeUsers');
            $totalPageViews = $totals->sum('screenPageViews');
            $avgViewsPerUser = $totalVisitors > 0
                ? round($totalPageViews / $totalVisitors, 2)
                : 0;

            /* === DAILY TREND (untuk grafik) === */
            $dailyTrend = $totals->map(fn ($row) => [
                'date' => $row['date']->format('Y-m-d'),
                'label' => $row['date']->translatedFormat('d M'),
                'visitors' => $row['activeUsers'],
                'pageViews' => $row['screenPageViews'],
            ])->values()->toArray();

            /* === TOP PAGES === */
            $topPages = Analytics::fetchMostVisitedPages($period, 10)
                ->map(fn ($row) => [
                    'url' => $row['fullPageUrl'] ?? $row['pageTitle'] ?? '-',
                    'title' => $row['pageTitle'] ?? '-',
                    'pageViews' => $row['screenPageViews'] ?? 0,
                ])
                ->toArray();

            /* === TOP REFERRERS === */
            $topReferrers = Analytics::fetchTopReferrers($period, 10)
                ->map(fn ($row) => [
                    'url' => $row['pageReferrer'] ?? '-',
                    'pageViews' => $row['screenPageViews'] ?? 0,
                ])
                ->toArray();

            /* === TOP BROWSERS === */
            $topBrowsers = Analytics::fetchTopBrowsers($period, 5)
                ->map(fn ($row) => [
                    'browser' => $row['browser'] ?? '-',
                    'pageViews' => $row['screenPageViews'] ?? 0,
                ])
                ->toArray();

            /* === TOP COUNTRIES === */
            $topCountries = Analytics::fetchTopCountries($period, 5)
                ->map(fn ($row) => [
                    'country' => $row['country'] ?? '-',
                    'pageViews' => $row['screenPageViews'] ?? 0,
                ])
                ->toArray();

            /* === TOP OPERATING SYSTEMS === */
            $topOS = Analytics::fetchTopOperatingSystems($period, 5)
                ->map(fn ($row) => [
                    'os' => $row['operatingSystem'] ?? '-',
                    'pageViews' => $row['screenPageViews'] ?? 0,
                ])
                ->toArray();

        } catch (\Throwable $e) {
            \Log::error('Analytics fetch failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            // Fallback ke data kosong
            $totalVisitors = 0;
            $totalPageViews = 0;
            $avgViewsPerUser = 0;
            $dailyTrend = [];
            $topPages = [];
            $topReferrers = [];
            $topBrowsers = [];
            $topCountries = [];
            $topOS = [];

            session()->flash('analytics_error', 'Gagal memuat data analytics: '.$e->getMessage());
        }

        return view('twill.analytics', compact(
            'totalVisitors',
            'totalPageViews',
            'avgViewsPerUser',
            'dailyTrend',
            'topPages',
            'topReferrers',
            'topBrowsers',
            'topCountries',
            'topOS'
        ));
    }
}
