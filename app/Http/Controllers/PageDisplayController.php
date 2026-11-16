<?php

namespace App\Http\Controllers;

use A17\Twill\Facades\TwillAppSettings;
use App\Models\Page;
use App\Repositories\PageRepository;
use Illuminate\Contracts\View\View;

class PageDisplayController extends Controller
{
    public function show(string $slug, PageRepository $pageRepository): View
    {
        $page = $pageRepository->forSlug($slug);

        if (! $page) {
            abort(404);
        }

        return view('site.page', [
            'item' => $page,
            'blocks' => $this->renderBlocks($page),
        ]);
    }

    public function home(): View
    {
        // Ambil koleksi halaman yang dipilih di settings (browser field)
        $selected = TwillAppSettings::get('homepage.homepage.page');

        // Null-safe: kalau belum dipilih, koleksi kosong, atau bukan collection
        if (! $selected || ! method_exists($selected, 'first') || $selected->isEmpty()) {
            abort(404);
        }

        /** @var Page|null $frontPage */
        $frontPage = $selected->first();

        if (! $frontPage || ! $frontPage->published) {
            abort(404);
        }

        return view('site.page', [
            'item' => $frontPage,
            'blocks' => $this->renderBlocks($frontPage),
        ]);
    }

    /**
     * Render Twill blocks SEKALI di controller, hasilnya string.
     * Mencegah bug interaksi ViewFactory antara renderBlocks() dan @include.
     */
    /**
     * Render Twill blocks SEKALI di controller, hasilnya string.
     * Di-cache untuk performa (6 jam TTL) dengan auto-invalidation.
     */

    /**
     * Render Twill blocks SEKALI di controller, hasilnya string.
     * Di-cache untuk performa (6 jam TTL) dengan auto-invalidation.
     */
    protected function renderBlocks(?Page $page): string
    {
        if (! $page || ! method_exists($page, 'renderBlocks')) {
            return '';
        }

        // ═══ Fragment cache: 6 jam TTL ═══
        $cacheKey = "page:{$page->id}:blocks:".app()->getLocale();
        $ttl = 60 * 60 * 6;

        return cache_block($cacheKey, $ttl, function () use ($page) {
            try {
                return (string) $page->renderBlocks();
            } catch (\Throwable $e) {
                \Log::error('renderBlocks failed', [
                    'page_id' => $page->id,
                    'slug' => $page->slug ?? null,
                    'error' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                ]);

                return '';
            }
        });
    }
}
