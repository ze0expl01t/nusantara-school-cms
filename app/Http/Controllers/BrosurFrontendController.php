<?php

namespace App\Http\Controllers;

use App\Models\Brosur;

class BrosurFrontendController extends Controller
{
    public function show($slug)
    {
        $brosur = Brosur::forSlug($slug)->where('published', true)->firstOrFail();

        $referenceDate = $brosur->brosur_date ?? $brosur->created_at;

        // Navigasi Next dengan Seamless Looping
        $nextBrosur = Brosur::where('published', true)
            ->where('id', '!=', $brosur->id)
            ->where('brosur_date', '>', $referenceDate)
            ->orderBy('brosur_date', 'asc')
            ->first();

        if (! $nextBrosur) {
            $nextBrosur = Brosur::where('published', true)
                ->where('id', '!=', $brosur->id)
                ->orderBy('brosur_date', 'asc')
                ->first();
        }

        return view('site.brosur-detail', compact('brosur', 'nextBrosur'));
    }
}
