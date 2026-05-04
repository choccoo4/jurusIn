<?php

use App\Http\Controllers\LandingController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChatbotController;

// Landing page
Route::get('/', [LandingController::class, 'index'])->name('home');

// chatbot
Route::get('/mulai', [ChatbotController::class, 'index'])->name('quiz.start');

// Legal
Route::get('/privasi',   fn() => view('pages.privacy'))->name('privacy');
Route::get('/ketentuan', fn() => view('pages.terms'))->name('terms');

