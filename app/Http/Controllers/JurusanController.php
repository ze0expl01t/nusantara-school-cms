<?php

namespace App\Http\Controllers;

use App\Models\Jurusan;

class JurusanController extends Controller
{
    public function index()
    {
        $jurusans = Jurusan::where('published', true)
            ->orderBy('position')
            ->get();

        return view('site.jurusan-list', compact('jurusans'));
    }

    public function show($slug)
    {
        $jurusan = Jurusan::forSlug($slug)
            ->where('published', true)
            ->firstOrFail();

        // Next berdasarkan position
        $nextJurusan = Jurusan::where('published', true)
            ->where('id', '!=', $jurusan->id)
            ->where('position', '>', $jurusan->position)
            ->orderBy('position', 'asc')
            ->first();

        // Looping ke awal kalau tidak ada next
        if (! $nextJurusan) {
            $nextJurusan = Jurusan::where('published', true)
                ->where('id', '!=', $jurusan->id)
                ->orderBy('position', 'asc')
                ->first();
        }

        return view('site.jurusan-detail', compact('jurusan', 'nextJurusan'));
    }
}
