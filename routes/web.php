<?php

use App\Http\Controllers\LandingController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChatbotController;
use App\Http\Controllers\QuestionnaireController;
use App\Http\Controllers\ResultsController;
// Landing page
Route::get('/', [LandingController::class, 'index'])->name('home');

// chatbot & kuesioner
Route::get('/mulai', [QuestionnaireController::class, 'index'])->name('questionnaire');
Route::get('/chat',      [ChatbotController::class, 'index'])->name('quiz.chat')->middleware('quiz.done');
Route::get('/hasil', [ResultsController::class, 'index'])->name('results')->middleware('chat.done');
Route::post('/questionnaire/save', [QuestionnaireController::class, 'save']);

// Chatbot API
Route::post('/chatbot/process', [ChatbotController::class, 'processAnswer']);
Route::post('/chatbot/submit-subjects', [ChatbotController::class, 'submitSubjects']);
Route::post('/chatbot/finalize', [ChatbotController::class, 'finalize']);
Route::get('/chatbot/start', [ChatbotController::class, 'startChat']);
Route::post('/chatbot/save-to-db', [ChatbotController::class, 'saveToDatabase']);

Route::get('/privasi',   fn() => view('pages.privacy'))->name('privacy');
Route::get('/ketentuan', fn() => view('pages.terms'))->name('terms');
