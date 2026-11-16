<?php

namespace App\Http\Controllers;

use App\Models\Extracurricular;

class ExtracurricularFrontendController extends Controller
{
    public function index()
    {
        $extracurriculars = Extracurricular::where('published', true)
            ->orderBy('position')
            ->get();

        return view('site.extracurricular-list', compact('extracurriculars'));
    }

    public function show($slug)
    {
        $extracurricular = Extracurricular::forSlug($slug)
            ->where('published', true)
            ->firstOrFail();

        // Next berdasarkan position
        $nextExtracurricular = Extracurricular::where('published', true)
            ->where('id', '!=', $extracurricular->id)
            ->where('position', '>', $extracurricular->position)
            ->orderBy('position', 'asc')
            ->first();

        // Looping ke awal kalau tidak ada next
        if (! $nextExtracurricular) {
            $nextExtracurricular = Extracurricular::where('published', true)
                ->where('id', '!=', $extracurricular->id)
                ->orderBy('position', 'asc')
                ->first();
        }

        return view('site.extracurricular-detail', compact('extracurricular', 'nextExtracurricular'));
    }
}
