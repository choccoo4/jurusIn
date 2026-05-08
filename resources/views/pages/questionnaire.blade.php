{{-- resources/views/pages/questionnaire.blade.php --}}
@extends('layouts.app')

@section('title', 'Kuesioner — JurusIn')

@section('content')
    @include('partials.questionnaire.quiz-section', ['questions' => $questions])
@endsection
