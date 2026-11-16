<?php

namespace App\Http\Controllers;

use App\Models\Agenda;
use App\Models\Brosur;
use App\Models\Extracurricular;
use App\Models\Facility;
use App\Models\Page;
use App\Models\Post;
use App\Models\Project;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    /**
     * Halaman hasil pencarian
     */
    public function index(Request $request)
    {
        $keyword = trim((string) $request->input('q', ''));
        $locale = app()->getLocale();

        // Kalau keyword kosong, tampilkan halaman search kosong
        if (mb_strlen($keyword) < 2) {
            return view('site.search-results', [
                'keyword' => $keyword,
                'results' => collect(),
                'total' => 0,
            ]);
        }

        $results = collect();

        // ============ PAGES ============
        $pages = Page::where('published', true)
            ->with('slugs')
            ->whereHas('translations', function ($q) use ($keyword) {
                $q->where('active', true)
                    ->where(function ($q2) use ($keyword) {
                        $q2->where('title', 'like', "%{$keyword}%")
                            ->orWhere('description', 'like', "%{$keyword}%");
                    });
            })
            ->limit(20)
            ->get();

        foreach ($pages as $page) {
            $slug = $page->slugs->where('locale', $locale)->first()?->slug
                ?? $page->slugs->first()?->slug;

            if (! $slug) {
                continue;
            }

            $results->push([
                'type' => 'Page',
                'type_slug' => 'page',
                'title' => $page->title,
                'excerpt' => $this->excerpt($page->description ?? ''),
                'url' => url('/'.$slug),
                'date' => $page->updated_at,
                'category' => null,
                'icon' => 'document',
            ]);
        }

        // ============ POSTS ============
        $posts = Post::where('published', true)
            ->with('slugs')
            ->whereHas('translations', function ($q) use ($keyword) {
                $q->where('active', true)
                    ->where(function ($q2) use ($keyword) {
                        $q2->where('title', 'like', "%{$keyword}%")
                            ->orWhere('description', 'like', "%{$keyword}%");
                    });
            })
            ->orderBy('post_date', 'desc')
            ->limit(20)
            ->get();

        foreach ($posts as $post) {
            $slug = $post->slugs->where('locale', $locale)->first()?->slug
                ?? $post->slugs->first()?->slug;

            if (! $slug) {
                continue;
            }

            $results->push([
                'type' => 'Berita',
                'type_slug' => 'post',
                'title' => $post->title,
                'excerpt' => $this->excerpt($post->description ?? ''),
                'url' => route('post.show', $slug),
                'date' => $post->post_date ?? $post->updated_at,
                'category' => $post->category,
                'icon' => 'news',
            ]);
        }

        // ============ PROJECTS ============
        $projects = Project::where('published', true)
            ->with('slugs')
            ->whereHas('translations', function ($q) use ($keyword) {
                $q->where('active', true)
                    ->where(function ($q2) use ($keyword) {
                        $q2->where('title', 'like', "%{$keyword}%")
                            ->orWhere('description', 'like', "%{$keyword}%");
                    });
            })
            ->limit(20)
            ->get();

        foreach ($projects as $project) {
            $slug = $project->slugs->where('locale', $locale)->first()?->slug
                ?? $project->slugs->first()?->slug;

            if (! $slug) {
                continue;
            }

            $results->push([
                'type' => 'Project',
                'type_slug' => 'project',
                'title' => $project->title,
                'excerpt' => $this->excerpt($project->description ?? ''),
                'url' => route('project.show', $slug),
                'date' => $project->project_date ?? $project->updated_at,
                'category' => $project->category,
                'icon' => 'briefcase',
            ]);
        }

        // ============ BROSURE ============
        $brosurs = Brosur::where('published', true)
            ->with('slugs')
            ->whereHas('translations', function ($q) use ($keyword) {
                $q->where('active', true)
                    ->where(function ($q2) use ($keyword) {
                        $q2->where('title', 'like', "%{$keyword}%")
                            ->orWhere('description', 'like', "%{$keyword}%");
                    });
            })
            ->limit(20)
            ->get();

        foreach ($brosurs as $brosur) {
            $slug = $brosur->slugs->where('locale', $locale)->first()?->slug
                ?? $brosur->slugs->first()?->slug;

            if (! $slug) {
                continue;
            }

            $results->push([
                'type' => 'Brosur',
                'type_slug' => 'brosur',
                'title' => $brosur->title,
                'excerpt' => $this->excerpt($brosur->description ?? ''),
                'url' => route('brosur.show', $slug),
                'date' => $brosur->brosur_date ?? $brosur->updated_at,
                'category' => null,
                'icon' => 'document',
            ]);
        }

        // ============ AGENDA ============
        $agendas = Agenda::where('published', true)
            ->with('slugs')
            ->whereHas('translations', function ($q) use ($keyword) {
                $q->where('active', true)
                    ->where(function ($q2) use ($keyword) {
                        $q2->where('title', 'like', "%{$keyword}%")
                            ->orWhere('description', 'like', "%{$keyword}%");
                    });
            })
            ->orderBy('agenda_date', 'desc')
            ->limit(20)
            ->get();

        foreach ($agendas as $agenda) {
            $slug = $agenda->slugs->where('locale', $locale)->first()?->slug
                ?? $agenda->slugs->first()?->slug;

            if (! $slug) {
                continue;
            }

            $results->push([
                'type' => 'Agenda',
                'type_slug' => 'agenda',
                'title' => $agenda->title,
                'excerpt' => $this->excerpt($agenda->description ?? ''),
                'url' => route('agenda.show', $slug),
                'date' => $agenda->agenda_date ?? $agenda->updated_at,
                'category' => null,
                'icon' => 'calendar',
            ]);
        }

        // ============ EKSTRAKURIKULER ============
        $ekskuls = Extracurricular::where('published', true)
            ->with('slugs')
            ->whereHas('translations', function ($q) use ($keyword) {
                $q->where('active', true)
                    ->where(function ($q2) use ($keyword) {
                        $q2->where('title', 'like', "%{$keyword}%")
                            ->orWhere('description', 'like', "%{$keyword}%");
                    });
            })
            ->limit(20)
            ->get();

        foreach ($ekskuls as $ekskul) {
            $slug = $ekskul->slugs->where('locale', $locale)->first()?->slug
                ?? $ekskul->slugs->first()?->slug;

            if (! $slug) {
                continue;
            }

            $results->push([
                'type' => 'Ekstrakurikuler',
                'type_slug' => 'extracurricular',
                'title' => $ekskul->title,
                'excerpt' => $this->excerpt($ekskul->description ?? ''),
                'url' => route('extracurricular.show', $slug),
                'date' => $ekskul->updated_at,
                'category' => $ekskul->category,
                'icon' => 'star',
            ]);
        }

        // ============ FASILITAS ============
        $facilities = Facility::where('published', true)
            ->with('slugs')
            ->whereHas('translations', function ($q) use ($keyword) {
                $q->where('active', true)
                    ->where(function ($q2) use ($keyword) {
                        $q2->where('title', 'like', "%{$keyword}%")
                            ->orWhere('description', 'like', "%{$keyword}%");
                    });
            })
            ->limit(20)
            ->get();

        foreach ($facilities as $facility) {
            $slug = $facility->slugs->where('locale', $locale)->first()?->slug
                ?? $facility->slugs->first()?->slug;

            if (! $slug) {
                continue;
            }

            $results->push([
                'type' => 'Fasilitas',
                'type_slug' => 'facility',
                'title' => $facility->title,
                'excerpt' => $this->excerpt($facility->description ?? ''),
                'url' => route('facility.show', $slug),
                'date' => $facility->updated_at,
                'category' => $facility->badge,
                'icon' => 'building',
            ]);
        }

        // ============ SORT BY RELEVANCE ============
        // Judul yang mengandung keyword = prioritas
        $results = $results->sortByDesc(function ($item) use ($keyword) {
            $titleMatch = mb_stripos($item['title'], $keyword) !== false;

            return $titleMatch ? 1 : 0;
        })->values();

        return view('site.search-results', [
            'keyword' => $keyword,
            'results' => $results,
            'total' => $results->count(),
        ]);
    }

    /**
     * Helper: buat excerpt dari HTML
     */
    protected function excerpt(string $html, int $length = 160): string
    {
        $text = trim(strip_tags($html));
        $text = preg_replace('/\s+/', ' ', $text);

        if (mb_strlen($text) <= $length) {
            return $text;
        }

        return mb_substr($text, 0, $length).'…';
    }
}
