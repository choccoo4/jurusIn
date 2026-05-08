{{-- resources/views/pages/results.blade.php --}}
@extends('layouts.app')

@section('title', 'Hasil Rekomendasi — JurusIn')

@section('content')
    @include('partials.results.result-section', [
        'recommendations' => $recommendations,
        'insight'         => $insight,
    ])
@endsection
