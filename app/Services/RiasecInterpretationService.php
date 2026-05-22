<?php

namespace App\Services;

class RiasecInterpretationService
{
    /**
     * Create a new class instance.
     */
    private array $narratives = [
        'R' => [
            'high'   => 'cenderung menyukai aktivitas praktis, eksplorasi langsung, dan penyelesaian masalah nyata',
            'medium' => 'cukup nyaman dengan aktivitas praktis dan teknis',
            'low'    => 'kurang tertarik pada aktivitas teknis dan pekerjaan lapangan',
        ],
        'I' => [
            'high'   => 'memiliki ketertarikan terhadap analisis, logika, penelitian, dan eksplorasi ide',
            'medium' => 'cukup tertarik pada aktivitas berpikir dan analisis',
            'low'    => 'kurang menikmati aktivitas analitis dan penelitian mendalam',
        ],
        'A' => [
            'high'   => 'menikmati kreativitas, ekspresi diri, dan aktivitas artistik',
            'medium' => 'cukup nyaman dengan aktivitas kreatif dan ekspresif',
            'low'    => 'kurang tertarik pada aktivitas artistik dan ekspresi kreatif',
        ],
        'S' => [
            'high'   => 'menikmati interaksi sosial, membantu orang lain, dan bekerja dalam tim',
            'medium' => 'cukup nyaman dengan interaksi sosial dan aktivitas kolaboratif',
            'low'    => 'kurang tertarik pada aktivitas sosial dan interaksi kelompok',
        ],
        'E' => [
            'high'   => 'nyaman dalam aktivitas kepemimpinan, komunikasi, persuasi, dan pengambilan keputusan',
            'medium' => 'cukup percaya diri dalam memimpin dan berkomunikasi',
            'low'    => 'kurang tertarik pada peran kepemimpinan dan persuasi',
        ],
        'C' => [
            'high'   => 'menyukai keteraturan, detail, dan pekerjaan yang terstruktur',
            'medium' => 'cukup nyaman dengan pekerjaan yang teratur dan sistematis',
            'low'    => 'kurang tertarik pada pekerjaan yang sangat terstruktur dan administratif',
        ],
    ];

    public function generateProfileText(array $scores): string
    {
        arsort($scores);
        $categories = array_keys($scores);
        $values = array_values($scores);

        $selected = [
            $categories[0] => $values[0],  // Top 1
            $categories[1] => $values[1],  // Top 2
            $categories[3] => $values[3],  // Middle
            $categories[5] => $values[5],  // Bottom 1
        ];

        $parts = [];
        $i = 0;
        $prevLevel = null;

        foreach ($selected as $category => $score) {
            $level = $this->getLevel($score);
            $baseNarrative = $this->narratives[$category][$level];

            if ($i === 0) {
                // Kalimat pertama
                $parts[] = "Pengguna " . $baseNarrative;
            } elseif ($level === 'high' && $prevLevel === 'high') {
                // Masih high → "juga"
                $parts[] = "Juga, pengguna " . $baseNarrative;
            } elseif ($level === 'low') {
                // Ada kontras → "Namun" atau "Di sisi lain"
                $parts[] = "Namun, pengguna " . $baseNarrative;
            } elseif ($level === 'medium' && in_array($prevLevel, ['high', 'low'])) {
                // Transisi ke medium
                $parts[] = "Di sisi lain, pengguna " . $baseNarrative;
            } else {
                // Default
                $parts[] = "Selain itu, pengguna " . $baseNarrative;
            }

            $prevLevel = $level;
            $i++;
        }

        return implode(". ", $parts) . '.';
    }

    private function getLevel(float $score): string
    {
        if ($score >= 60) return 'high';
        if ($score >= 40) return 'medium';
        return 'low';
    }
}
