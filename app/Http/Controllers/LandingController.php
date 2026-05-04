<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class LandingController extends Controller
{
    public function index(): View
    {
        return view('pages.landing', [
            'steps'            => $this->steps(),
            'stats'            => $this->stats(),
            'faqs'             => $this->faqs(),
            'footerCategories' => $this->footerCategories(),
        ]);
    }

    // -------------------------------------------------------------------------
    // Private data methods
    // Move these to a Repository, Service, or config file once data is dynamic
    // -------------------------------------------------------------------------

    private function steps(): array
    {
        return [
            [
                'badge'       => 'Langkah 1',
                'title'       => 'Isi Kuesioner',
                'description' => 'Jawab pertanyaan tentang minat, preferensi belajar, dan cara kamu berpikir. Hanya butuh 5–7 menit.',
                'icon'        => 'file-text',
                'meta_icon'   => 'clock',
                'meta'        => '~5 menit',
                'accent'      => '#6366f1',
                'icon_bg'     => '#4f46e5',
                'badge_bg'    => '#eef2ff',
                'card_bg'     => '#faf8ff',
                'card_border' => '#e8e0ff',
            ],
            [
                'badge'       => 'Langkah 2',
                'title'       => 'Analisis AI',
                'description' => 'Sistem menganalisis jawabanmu menggunakan semantic similarity (SBERT) untuk mencocokkan profil dengan jurusan.',
                'icon'        => 'zap',
                'meta_icon'   => 'zap',
                'meta'        => 'Analisis instan',
                'accent'      => '#7c3aed',
                'icon_bg'     => '#7c3aed',
                'badge_bg'    => '#f5f3ff',
                'card_bg'     => '#fdf8ff',
                'card_border' => '#e9d5ff',
            ],
            [
                'badge'       => 'Langkah 3',
                'title'       => 'Hasil Rekomendasi',
                'description' => 'Dapatkan daftar jurusan yang paling sesuai, lengkap dengan tingkat kecocokan dan info universitas terkait.',
                'icon'        => 'check-circle',
                'meta_icon'   => 'star',
                'meta'        => 'Hasil personal',
                'accent'      => '#16a34a',
                'icon_bg'     => '#16a34a',
                'badge_bg'    => '#dcfce7',
                'card_bg'     => '#f0fdf4',
                'card_border' => '#bbf7d0',
            ],
        ];
    }

    private function stats(): array
    {
        return [
            ['value' => '2.4K+', 'label' => 'Siswa telah mencoba'],
            ['value' => '50+',   'label' => 'Jurusan tersedia'],
            ['value' => '4.9',   'label' => 'Rating rata-rata'],
            ['value' => '5 mnt', 'label' => 'Waktu pengerjaan'],
        ];
    }

    private function faqs(): array
    {
        return [
            [
                'question' => 'Bagaimana cara kerja rekomendasi AI?',
                'answer'   => 'JurusIn menggunakan SBERT (Sentence-BERT) untuk membandingkan profil minat dan cara berpikirmu dengan karakteristik tiap jurusan secara semantik, bukan hanya keyword matching.',
            ],
            [
                'question' => 'Apakah hasilnya akurat?',
                'answer'   => 'Hasil rekomendasi sangat bergantung pada kejujuran jawabanmu. Semakin jujur dan reflektif jawabanmu, semakin akurat rekomendasinya. Ini bukan ujian, tidak ada jawaban salah.',
            ],
            [
                'question' => 'Apakah JurusIn gratis?',
                'answer'   => 'Ya, JurusIn 100% gratis untuk semua fitur dasar. Kami percaya setiap siswa berhak mendapat panduan pemilihan jurusan yang baik tanpa biaya.',
            ],
            [
                'question' => 'Data saya aman?',
                'answer'   => 'Data jawabanmu hanya digunakan untuk keperluan analisis dan tidak akan dibagikan ke pihak ketiga. Kamu bisa menghapus data kapan saja.',
            ],
        ];
    }

    private function footerCategories(): array
    {
        return [
            ['label' => 'IPA & Teknologi', 'url' => '#'],
            ['label' => 'IPS & Bisnis',    'url' => '#'],
            ['label' => 'Seni & Desain',   'url' => '#'],
            ['label' => 'Kesehatan',        'url' => '#'],
        ];
    }
}
