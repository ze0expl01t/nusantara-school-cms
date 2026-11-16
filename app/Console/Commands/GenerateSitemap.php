<?php

namespace App\Console\Commands;

use A17\Twill\Facades\TwillAppSettings;
use App\Models\Agenda;
use App\Models\Brosur;
use App\Models\Extracurricular;
use App\Models\Facility;
use App\Models\Page;
use App\Models\Post;
use App\Models\Project;
use Illuminate\Console\Command;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;

class GenerateSitemap extends Command
{
    protected $signature = 'sitemap:generate';

    protected $description = 'Generate static sitemap.xml file';

    /**
     * Helper: ambil setting value dari Twill Settings
     */
    protected function setting(string $key, $default = null)
    {
        try {
            $value = TwillAppSettings::get("sitemap.settings.{$key}");

            return $value !== null && $value !== '' ? $value : $default;
        } catch (\Throwable $e) {
            return $default;
        }
    }

    /**
     * Helper: cek toggle aktif
     */
    protected function enabled(string $key): bool
    {
        return (bool) $this->setting($key, true);
    }

    public function handle(): int
    {
        $this->info('Generating sitemap...');
        $startTime = microtime(true);

        // ============ BASE URL dari setting ============
        $baseUrl = rtrim($this->setting('sitemap_base_url', config('app.url')), '/');

        $path = public_path('sitemap.xml');
        $sitemap = Sitemap::create();

        // ============ HOMEPAGE (selalu) ============
        $sitemap->add(
            Url::create($baseUrl.'/')->setLastModificationDate(now())
        );

        // ============ PAGES ============
        $pagesCount = 0;
        if ($this->enabled('sitemap_include_pages')) {
            Page::where('published', true)->with('slugs')->get()->each(function ($page) use ($sitemap, $baseUrl, &$pagesCount) {
                $slug = $page->slugs->where('locale', app()->getLocale())->first()?->slug
                    ?? $page->slugs->first()?->slug;
                if ($slug) {
                    $sitemap->add(
                        Url::create($baseUrl.'/'.$slug)
                            ->setLastModificationDate($page->updated_at)
                    );
                    $pagesCount++;
                }
            });
        }

        // ============ POSTS ============
        $postsCount = 0;
        if ($this->enabled('sitemap_include_posts')) {
            Post::where('published', true)->with('slugs')->get()->each(function ($post) use ($sitemap, $baseUrl, &$postsCount) {
                $slug = $post->slugs->where('locale', app()->getLocale())->first()?->slug
                    ?? $post->slugs->first()?->slug;
                if ($slug) {
                    $sitemap->add(
                        Url::create($baseUrl.'/post/'.$slug)
                            ->setLastModificationDate($post->updated_at)
                    );
                    $postsCount++;
                }
            });
        }

        // ============ PROJECTS ============
        $projectsCount = 0;
        if ($this->enabled('sitemap_include_projects')) {
            Project::where('published', true)->with('slugs')->get()->each(function ($project) use ($sitemap, $baseUrl, &$projectsCount) {
                $slug = $project->slugs->where('locale', app()->getLocale())->first()?->slug
                    ?? $project->slugs->first()?->slug;
                if ($slug) {
                    $sitemap->add(
                        Url::create($baseUrl.'/project/'.$slug)
                            ->setLastModificationDate($project->updated_at)
                    );
                    $projectsCount++;
                }
            });
        }

        // ============ BROSURE ============
        $brosursCount = 0;
        if ($this->enabled('sitemap_include_brosurs')) {
            Brosur::where('published', true)->with('slugs')->get()->each(function ($brosur) use ($sitemap, $baseUrl, &$brosursCount) {
                $slug = $brosur->slugs->where('locale', app()->getLocale())->first()?->slug
                    ?? $brosur->slugs->first()?->slug;
                if ($slug) {
                    $sitemap->add(
                        Url::create($baseUrl.'/brosur/'.$slug)
                            ->setLastModificationDate($brosur->updated_at)
                    );
                    $brosursCount++;
                }
            });
        }

        // ============ AGENDA ============
        $agendasCount = 0;
        if ($this->enabled('sitemap_include_agendas')) {
            Agenda::where('published', true)->with('slugs')->get()->each(function ($agenda) use ($sitemap, $baseUrl, &$agendasCount) {
                $slug = $agenda->slugs->where('locale', app()->getLocale())->first()?->slug
                    ?? $agenda->slugs->first()?->slug;
                if ($slug) {
                    $sitemap->add(
                        Url::create($baseUrl.'/agenda/'.$slug)
                            ->setLastModificationDate($agenda->updated_at)
                    );
                    $agendasCount++;
                }
            });
        }

        // ============ EKSTRAKURIKULER ============
        $ekskulCount = 0;
        if ($this->enabled('sitemap_include_extracurriculars')) {
            Extracurricular::where('published', true)->with('slugs')->get()->each(function ($ekskul) use ($sitemap, $baseUrl, &$ekskulCount) {
                $slug = $ekskul->slugs->where('locale', app()->getLocale())->first()?->slug
                    ?? $ekskul->slugs->first()?->slug;
                if ($slug) {
                    $sitemap->add(
                        Url::create($baseUrl.'/ekstrakurikuler/'.$slug)
                            ->setLastModificationDate($ekskul->updated_at)
                    );
                    $ekskulCount++;
                }
            });
        }

        // ============ FASILITAS ============
        $facilitiesCount = 0;
        if ($this->enabled('sitemap_include_facilities')) {
            Facility::where('published', true)->with('slugs')->get()->each(function ($facility) use ($sitemap, $baseUrl, &$facilitiesCount) {
                $slug = $facility->slugs->where('locale', app()->getLocale())->first()?->slug
                    ?? $facility->slugs->first()?->slug;
                if ($slug) {
                    $sitemap->add(
                        Url::create($baseUrl.'/fasilitas/'.$slug)
                            ->setLastModificationDate($facility->updated_at)
                    );
                    $facilitiesCount++;
                }
            });
        }

        // ============ WRITE FILE ============
        $sitemap->writeToFile($path);

        $duration = round((microtime(true) - $startTime) * 1000, 2);
        $total = $pagesCount + $postsCount + $projectsCount + $brosursCount
            + $agendasCount + $ekskulCount + $facilitiesCount + 1;

        $this->newLine();
        $this->info('✅ Sitemap generated successfully!');
        $this->table(
            ['Sumber', 'Jumlah', 'Status'],
            [
                ['Pages', $pagesCount, $this->enabled('sitemap_include_pages') ? '✓' : '✗ skip'],
                ['Posts', $postsCount, $this->enabled('sitemap_include_posts') ? '✓' : '✗ skip'],
                ['Projects', $projectsCount, $this->enabled('sitemap_include_projects') ? '✓' : '✗ skip'],
                ['Brosur', $brosursCount, $this->enabled('sitemap_include_brosurs') ? '✓' : '✗ skip'],
                ['Agenda', $agendasCount, $this->enabled('sitemap_include_agendas') ? '✓' : '✗ skip'],
                ['Ekstrakurikuler', $ekskulCount, $this->enabled('sitemap_include_extracurriculars') ? '✓' : '✗ skip'],
                ['Fasilitas', $facilitiesCount, $this->enabled('sitemap_include_facilities') ? '✓' : '✗ skip'],
                ['TOTAL', $total, ''],
            ]
        );
        $this->info("🌐 Base URL: {$baseUrl}");
        $this->info("📁 File: {$path}");
        $this->info("⏱️  Duration: {$duration}ms");

        return self::SUCCESS;
    }
}
