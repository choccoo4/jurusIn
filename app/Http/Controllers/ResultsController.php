<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class ResultsController extends Controller
{
    public function index(): View
    {
        session(['recommendation_completed' => true]);

        return view('pages.results', [
            'recommendations' => $this->recommendations(),
            'insight'         => $this->insight(),
        ]);
    }

    // -------------------------------------------------------------------------
    // Nanti di ganti dengan real output SBERT
    // -------------------------------------------------------------------------


    private function recommendations(): array
    {
        return [
            [
                'rank'        => 1,
                'major'       => 'Teknik Informatika',
                'description' => 'Cocok untuk kamu yang suka logika, teknologi, dan problem solving berbasis kode.',
                'color'       => '#4f46e5',
                'tags'        => ['Logika', 'Coding', 'Problem Solving'],
                'traits'      => [
                    ['label' => 'Kemampuan Analitis', 'val' => 88],
                    ['label' => 'Pemikiran Logis', 'val' => 85],
                    ['label' => 'Kreativitas', 'val' => 72],
                    ['label' => 'Orientasi Sosial', 'val' => 65],
                ],
            ],
            [
                'rank'        => 2,
                'major'       => 'Sistem Informasi',
                'description' => 'Gabungan antara bisnis dan teknologi — ideal untuk yang suka keduanya.',
                'color'       => '#7c3aed',
                'tags'        => ['Bisnis', 'Teknologi', 'Manajemen'],
            ],
            [
                'rank'        => 3,
                'major'       => 'Data Science',
                'description' => 'Mengolah data besar menjadi insight yang berdampak nyata.',
                'color'       => '#6366f1',
                'tags'        => ['Statistik', 'AI', 'Analisis'],
            ],
            [
                'rank'        => 4,
                'major'       => 'Manajemen Bisnis',
                'description' => 'Fokus pada strategi, kepemimpinan, dan pengelolaan organisasi.',
                'color'       => '#8b5cf6',
                'tags'        => ['Strategi', 'Leadership', 'Bisnis'],
            ],
            [
                'rank'        => 5,
                'major'       => 'UI/UX Design',
                'description' => 'Menggabungkan kreativitas visual dengan pemahaman pengguna.',
                'color'       => '#a78bfa',
                'tags'        => ['Desain', 'Kreatif', 'Teknologi'],
            ],
        ];
    }

    private function insight(): string
    {
        return 'Kamu memiliki kecenderungan kuat pada bidang analitis dan teknologi. Cara berpikirmu yang sistematis dan logis sangat cocok dengan lingkungan kerja yang terstruktur. Jurusan berbasis STEM dengan pendekatan problem solving akan membuat kamu berkembang pesat.';
    }
}
