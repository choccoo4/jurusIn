<?php

use App\Http\Controllers\LandingController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChatbotController;
use App\Http\Controllers\QuestionnaireController;
use App\Http\Controllers\ResultsController;

Route::get('/', [LandingController::class, 'index'])->name('home');
Route::get('/privasi',   fn() => view('pages.privacy'))->name('privacy');
Route::get('/ketentuan', fn() => view('pages.terms'))->name('terms');

Route::get('/mulai', [QuestionnaireController::class, 'index'])->name('questionnaire')->middleware('block.if.recommend');
Route::post('/questionnaire/save', [QuestionnaireController::class, 'save'])->middleware('block.if.recommend');

Route::middleware('quiz.done')->group(function () {
    Route::get('/chat', [ChatbotController::class, 'index'])->name('quiz.chat');
    Route::get('/chatbot/start',              [ChatbotController::class, 'startChat']);
    Route::post('/chatbot/process',           [ChatbotController::class, 'processAnswer']);
    Route::post('/chatbot/finalize',          [ChatbotController::class, 'finalize']);
    Route::post('/chatbot/save-to-db',        [ChatbotController::class, 'saveToDatabase']);
});

Route::get('/hasil', [ResultsController::class, 'index'])->name('results')->middleware('chat.done');
Route::post('/tes-ulang', [QuestionnaireController::class, 'resetTest'])->name('test.reset');
