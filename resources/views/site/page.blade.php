@extends('site.layouts.master')

@php
    // $blocks di-pass dari controller (sudah string, sudah dirender)
    $__blocks = $blocks ?? '';

    // ===== Untuk breadcrumb =====
    $pageTitle = isset($item) && $item->title ? $item->title : 'Halaman';

    // ===== SEO =====
    $metaTitle = (isset($item) && $item->meta_title)
        ? $item->meta_title
        : ($pageTitle . ' | ' . config('app.name'));

    $metaDesc = (isset($item) && $item->meta_description)
        ? $item->meta_description
        : '';
@endphp

@section('meta_title', $metaTitle)
@section('meta_description', $metaDesc)

@if(isset($item) && $item->hasImage('og_image', 'default'))
    @section('og_image', $item->image('og_image', 'default'))
@endif

@section('content')

    {{-- Breadcrumb Navigation --}}
    <div class="border-b border-slate-200 bg-white/50 backdrop-blur-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 sm:py-5">
            @include('site.partials.breadcrumb', ['items' => [
                ['label' => $pageTitle, 'url' => null],
            ]])
        </div>
    </div>

    {{-- Blocks --}}
    {!! $__blocks !!}

@endsection
