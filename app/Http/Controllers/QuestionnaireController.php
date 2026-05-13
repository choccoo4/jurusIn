<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class QuestionnaireController extends Controller
{
    public function index(): View
    {
        return view('pages.questionnaire', [
            'questions' => $this->questions(),
        ]);
    }

    private function questions(): array
    {
        return [
            [
                'question' => 'Saya suka memperbaiki atau merakit sesuatu.',
                'icon'     => 'zap',
            ],
            [
                'question' => 'Saya tertarik mempelajari cara kerja mesin atau alat elektronik.',
                'icon'     => 'file-text',
            ],
            [
                'question' => 'Saya suka memecahkan masalah logika.',
                'icon'     => 'layers',
            ],
            [
                'question' => 'Saya menikmati menganalisis suatu persoalan secara mendalam.',
                'icon'     => 'star',
            ],
            [
                'question' => 'Saya suka membuat desain atau karya visual.',
                'icon'     => 'check-circle',
            ],
            [
                'question' => 'Saya tertarik dengan dunia bisnis dan kewirausahaan.',
                'icon'     => 'zap',
            ],
            [
                'question' => 'Saya suka berinteraksi dan membantu orang lain.',
                'icon'     => 'file-text',
            ],
            [
                'question' => 'Saya lebih suka bekerja dengan data dan angka.',
                'icon'     => 'layers',
            ],
        ];
    }
}
