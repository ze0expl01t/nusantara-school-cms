<?php

namespace App\Http\Controllers;

use App\Models\Facility;

class FacilityFrontendController extends Controller
{
    public function index()
    {
        $facilities = Facility::where('published', true)
            ->orderBy('position')
            ->get();

        return view('site.facility-list', compact('facilities'));
    }

    public function show($slug)
    {
        $facility = Facility::forSlug($slug)
            ->where('published', true)
            ->firstOrFail();

        $nextFacility = Facility::where('published', true)
            ->where('id', '!=', $facility->id)
            ->where('position', '>', $facility->position)
            ->orderBy('position', 'asc')
            ->first();

        if (! $nextFacility) {
            $nextFacility = Facility::where('published', true)
                ->where('id', '!=', $facility->id)
                ->orderBy('position', 'asc')
                ->first();
        }

        return view('site.facility-detail', compact('facility', 'nextFacility'));
    }
}
