{{-- resources/views/pages/landing.blade.php --}}
@extends('layouts.app')

@section('title', 'JurusIn — Temukan Jurusan yang Cocok')

@push('head')
    {{-- Page-specific meta --}}
    <meta name="description" content="JurusIn membantu kamu menemukan jurusan kuliah yang cocok melalui analisis AI berbasis minat dan kepribadian.">
@endpush

@section('content')
    @include('partials.landing.hero')
    @include('partials.landing.how-it-works', ['steps' => $steps])
    @include('partials.landing.stats-bar', ['stats' => $stats])
    @include('partials.landing.faq', ['faqs' => $faqs])
    @include('partials.landing.cta')
@endsection

@push('scripts')
    {{-- No extra scripts needed; Vite handles everything --}}
@endpush
