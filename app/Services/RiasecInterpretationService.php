<?php

namespace App\Services;

class RiasecInterpretationService
{
    /**
     * Create a new class instance.
     */
    private array $narratives = [
        'R' => [
            'high' => 'lebih nyaman belajar melalui praktik dan penerapan langsung',
            'medium' => 'cukup nyaman mencoba dan mengeksplorasi secara langsung',
            'low' => 'lebih nyaman pada aktivitas yang tidak terlalu berorientasi praktik'
        ],

        'I' => [
            'high' => 'menikmati memahami suatu hal secara mendalam dan mengeksplorasi berbagai kemungkinan',
            'medium' => 'cukup tertarik pada aktivitas berpikir dan eksplorasi ide',
            'low' => 'lebih nyaman pada aktivitas yang tidak terlalu menuntut analisis mendalam'
        ],

        'A' => [
            'high' => 'menikmati kreativitas dan menemukan cara yang berbeda dalam menyelesaikan sesuatu',
            'medium' => 'cukup nyaman menggunakan ide dan kreativitas',
            'low' => 'lebih nyaman pada aktivitas yang memiliki struktur jelas'
        ],

        'S' => [
            'high' => 'menikmati berinteraksi, bekerja bersama, dan memberi kontribusi kepada orang lain',
            'medium' => 'cukup nyaman dengan aktivitas kolaboratif',
            'low' => 'lebih nyaman bekerja secara mandiri'
        ],

        'E' => [
            'high' => 'nyaman mengambil inisiatif, mempengaruhi orang lain, dan mengarahkan jalannya aktivitas',
            'medium' => 'cukup percaya diri dalam menyampaikan ide',
            'low' => 'lebih nyaman berperan sebagai pendukung'
        ],

        'C' => [
            'high' => 'menyukai keteraturan dan proses yang terorganisir',
            'medium' => 'cukup nyaman dengan aktivitas yang terstruktur',
            'low' => 'lebih nyaman pada lingkungan yang fleksibel'
        ]
    ];

    public function generateProfileText(array $scores): string
    {
        arsort($scores);

        $categories = array_keys($scores);
        $values = array_values($scores);

        $selected = [
            $categories[0] => $values[0], // Top 1
            $categories[1] => $values[1], // Top 2
            $categories[3] => $values[3], // Middle
            $categories[5] => $values[5], // Bottom 1
        ];

        $parts = [];

        foreach ($selected as $category => $score) {
            $level = $this->getLevel($score);

            $parts[] = 'Pengguna ' . $this->narratives[$category][$level];
        }

        return implode(".\n", $parts) . '.';
    }

    private function getLevel(float $score): string
    {
        if ($score >= 60) return 'high';
        if ($score >= 40) return 'medium';
        return 'low';
    }
}
