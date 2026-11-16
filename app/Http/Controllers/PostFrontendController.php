<?php

namespace App\Http\Controllers;

use App\Models\Post;

class PostFrontendController extends Controller
{
    public function show($slug)
    {
        // Mengambil data post berdasarkan slug dan status published
        $post = Post::forSlug($slug)->where('published', true)->firstOrFail();

        $referenceDate = $post->post_date ?? $post->created_at;

        // Mengambil post berikutnya (berdasarkan tanggal setelah post saat ini)
        $nextPost = Post::where('published', true)
            ->where('id', '!=', $post->id)
            ->where('post_date', '>', $referenceDate)
            ->orderBy('post_date', 'asc')
            ->first();

        // Opsi Looping: Jika tidak ada post setelahnya, ambil post pertama (paling awal)
        if (! $nextPost) {
            $nextPost = Post::where('published', true)
                ->where('id', '!=', $post->id)
                ->orderBy('post_date', 'asc')
                ->first();
        }

        return view('site.post-detail', compact('post', 'nextPost'));
    }
}
