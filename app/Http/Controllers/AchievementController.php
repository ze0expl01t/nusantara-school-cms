<?php

namespace App\Http\Controllers;

use App\Models\Achievement;

class AchievementController extends Controller
{
    public function show($slug)
    {
        $achievement = Achievement::forSlug($slug)
            ->where('published', true)
            ->firstOrFail();

        $nextAchievement = Achievement::where('published', true)
            ->where('id', '!=', $achievement->id)
            ->where('position', '>', $achievement->position)
            ->orderBy('position', 'asc')
            ->first();

        if (! $nextAchievement) {
            $nextAchievement = Achievement::where('published', true)
                ->where('id', '!=', $achievement->id)
                ->orderBy('position', 'asc')
                ->first();
        }

        return view('site.achievement-detail', compact('achievement', 'nextAchievement'));
    }
}
