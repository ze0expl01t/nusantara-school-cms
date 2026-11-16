<?php

namespace App\Http\Controllers;

use App\Models\Project;

class ProjectFrontendController extends Controller
{
    public function show($slug)
    {
        $project = Project::forSlug($slug)->where('published', true)->firstOrFail();

        $nextProject = Project::where('published', true)
            ->where('project_date', '>', $project->project_date ?? $project->created_at)
            ->orderBy('project_date', 'asc')
            ->first();

        return view('site.project-detail', compact('project', 'nextProject'));
    }
}
