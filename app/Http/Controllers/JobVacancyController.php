<?php

namespace App\Http\Controllers;

use App\Models\JobVacancy;

class JobVacancyController extends Controller
{
    public function show($slug)
    {
        $jobVacancy = JobVacancy::forSlug($slug)
            ->where('published', true)
            ->firstOrFail();

        $relatedVacancies = JobVacancy::where('published', true)
            ->where('id', '!=', $jobVacancy->id)
            ->where(function ($q) {
                $q->whereNull('deadline')
                    ->orWhere('deadline', '>=', now()->toDateString());
            })
            ->orderBy('position')
            ->limit(3)
            ->get();

        return view('site.job-vacancy-detail', compact('jobVacancy', 'relatedVacancies'));
    }
}
