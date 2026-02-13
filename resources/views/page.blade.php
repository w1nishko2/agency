@extends('layouts.main')

@section('title', $page->meta_title ?? $page->title)
@section('description', $page->meta_description ?? '')

@section('content')
<div data-page-id="{{ $page->id }}">
<!-- Hero -->
@php
    $heroImageUrl = $page->image ? asset('storage/' . $page->image) : asset('imgsite/photo/photo_2_2026-01-24_11-43-44.webp');
@endphp
<section class="py-5" style="background: linear-gradient(rgba(255,255,255,0.9), rgba(255,255,255,0.9)), url('{{ $heroImageUrl }}') center/cover;">
    <div class="container text-center">
        <h1 class="mb-3">{{ strtoupper($page->title) }}</h1>
    </div>
</section>

<!-- Контент -->
<section class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-10 mx-auto">
                <div class="content">
                    {!! $page->description !!}
                </div>
            </div>
        </div>
    </div>
</section>
</div>

@endsection

@push('styles')
<style>
.content h2 {
    margin-top: 2rem;
    margin-bottom: 1rem;
    font-size: 2rem;
    font-weight: 600;
}

.content h3 {
    margin-top: 1.5rem;
    margin-bottom: 0.75rem;
    font-size: 1.5rem;
    font-weight: 600;
}

.content h4 {
    margin-top: 1.25rem;
    margin-bottom: 0.5rem;
    font-size: 1.25rem;
    font-weight: 600;
}

.content p {
    margin-bottom: 1rem;
    line-height: 1.6;
    color: #6c757d;
}

.content ul, .content ol {
    margin-bottom: 1rem;
    padding-left: 2rem;
}

.content li {
    margin-bottom: 0.5rem;
    line-height: 1.6;
    color: #6c757d;
}

.content ul {
    list-style-type: disc;
}

.content ol {
    list-style-type: decimal;
}

.content a {
    color: #000;
    text-decoration: underline;
}

.content a:hover {
    color: #666;
}

.content strong {
    font-weight: 600;
    color: #000;
}

.content blockquote {
    border-left: 4px solid #000;
    padding-left: 1rem;
    margin: 1.5rem 0;
    font-style: italic;
    color: #6c757d;
}
</style>
@endpush
