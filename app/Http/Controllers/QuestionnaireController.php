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

    // -------------------------------------------------------------------------
    // Move to a QuestionRepository or database when data grows
    // -------------------------------------------------------------------------

    private function questions(): array
    {
        return [
            [
                'question' => 'Kamu lebih tertarik pada aktivitas seperti apa?',
                'icon'     => 'zap',
                'options'  => [
                    'Mengolah data dan logika',
                    'Berinteraksi dengan orang',
                    'Membuat karya kreatif',
                    'Mengelola bisnis',
                ],
            ],
            [
                'question' => 'Pelajaran apa yang paling kamu sukai?',
                'icon'     => 'file-text',
                'options'  => [
                    'Matematika & Sains',
                    'Bahasa & Komunikasi',
                    'Seni & Desain',
                    'Ekonomi & Bisnis',
                ],
            ],
            [
                'question' => 'Bagaimana cara kamu menyelesaikan masalah?',
                'icon'     => 'layers',
                'options'  => [
                    'Analisis dan logika',
                    'Diskusi dengan orang lain',
                    'Pendekatan kreatif',
                    'Strategi praktis',
                ],
            ],
            [
                'question' => 'Lingkungan kerja seperti apa yang kamu inginkan?',
                'icon'     => 'star',
                'options'  => [
                    'Teknis dan sistematis',
                    'Kolaboratif dan sosial',
                    'Fleksibel dan kreatif',
                    'Kompetitif dan dinamis',
                ],
            ],
            [
                'question' => 'Kamu lebih suka output pekerjaan berupa apa?',
                'icon'     => 'check-circle',
                'options'  => [
                    'Produk digital / software',
                    'Dampak sosial nyata',
                    'Karya seni / desain',
                    'Pertumbuhan bisnis',
                ],
            ],
        ];
    }
}
