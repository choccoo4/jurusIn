<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class ResultsController extends Controller
{
    public function index(): View
    {
        return view('pages.results', [
            'recommendations' => $this->recommendations(),
            'insight'         => $this->insight(),
        ]);
    }

    // -------------------------------------------------------------------------
    // Replace with real SBERT output once backend is connected.
    // Each item maps to the resultPage Alpine component.
    // -------------------------------------------------------------------------

    private function recommendations(): array
    {
        return [
            [
                'name'        => 'Teknik Informatika',
                'score'       => 92,
                'description' => 'Cocok untuk kamu yang suka logika, teknologi, dan problem solving berbasis kode.',
                'color'       => '#4f46e5',
                'tags'        => ['Logika', 'Coding', 'Problem Solving'],
            ],
            [
                'name'        => 'Sistem Informasi',
                'score'       => 85,
                'description' => 'Gabungan antara bisnis dan teknologi — ideal untuk yang suka keduanya.',
                'color'       => '#7c3aed',
                'tags'        => ['Bisnis', 'Teknologi', 'Manajemen'],
            ],
            [
                'name'        => 'Data Science',
                'score'       => 81,
                'description' => 'Mengolah data besar menjadi insight yang berdampak nyata.',
                'color'       => '#6366f1',
                'tags'        => ['Statistik', 'AI', 'Analisis'],
            ],
            [
                'name'        => 'Manajemen Bisnis',
                'score'       => 74,
                'description' => 'Fokus pada strategi, kepemimpinan, dan pengelolaan organisasi.',
                'color'       => '#8b5cf6',
                'tags'        => ['Strategi', 'Leadership', 'Bisnis'],
            ],
        ];
    }

    private function insight(): string
    {
        return 'Kamu memiliki kecenderungan kuat pada bidang analitis dan teknologi. Cara berpikirmu yang sistematis dan logis sangat cocok dengan lingkungan kerja yang terstruktur. Jurusan berbasis STEM dengan pendekatan problem solving akan membuat kamu berkembang pesat.';
    }
}
