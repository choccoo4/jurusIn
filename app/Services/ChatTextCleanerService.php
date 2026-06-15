<?php

namespace App\Services;

class ChatTextCleanerService
{
    private const LAUGHTER_PATTERNS = [
        '/\b(?:w[ka]){2,}\b/iu',      // wkwk, wakaka, wkwkwk, wkwkwkwk
        '/\bha(?:ha)+\b/iu',           // haha, hahaha, hahahaha
        '/\bhe(?:he)+\b/iu',           // hehe, hehehe
        '/\bhi(?:hi)+\b/iu',           // hihi, hihihi
        '/\bho(?:ho)+\b/iu',           // hoho, hohoho
        '/\bxi(?:xi)+\b/iu',           // xixi, xixixi
        '/\blo(?:l)+\b/iu',            // lol, loll
        '/\b(?:hi+y+|wo+w+)\b/iu',     // hiyy, woww
    ];

    private const FILLER_WORDS = [
        'wkwk',
        'wkwkwk',
        'wkwkwkwk',
        'haha',
        'hehe',
        'hihi',
        'hoho',
        'lol',
        'lmao',
        'xixi',
        'btw',
        'wkwkwkwkwk',
        'anjay',
        'anjir',
        'astaga',
        'duh',
        'eh',
        'nah',
        'sih',
        'deh',
        'kak',
        'gan',
        'bro',
        'sis',
        'guys',
    ];

    public function cleanAnswers(array $answers): array
    {
        foreach ($answers as &$answer) {
            if (isset($answer['answer']) && is_string($answer['answer'])) {
                $cleaned = $this->clean($answer['answer']);

                // Fallback: kalau hasil cleaning kosong (misal user cuma kirim emoji),
                // pertahankan teks asli supaya tidak kehilangan jawaban sama sekali.
                $answer['answer'] = $cleaned !== '' ? $cleaned : trim($answer['answer']);
            }
        }

        return $answers;
    }

    private function removeEmojis(string $text): string
    {
        // Rentang unicode untuk emoji, simbol, pictograph, transport, flags, dingbats, dll.
        $pattern = '/[\x{1F000}-\x{1FFFF}\x{2600}-\x{27BF}\x{2190}-\x{21FF}\x{2B00}-\x{2BFF}\x{FE00}-\x{FE0F}\x{200D}]/u';

        return preg_replace($pattern, '', $text) ?? $text;
    }


    private function removeLaughter(string $text): string
    {
        foreach (self::LAUGHTER_PATTERNS as $pattern) {
            $text = preg_replace($pattern, '', $text) ?? $text;
        }

        return $text;
    }

    private function removeFillerWords(string $text): string
    {
        $words = preg_split('/\s+/u', $text, -1, PREG_SPLIT_NO_EMPTY);
        if (!$words) {
            return $text;
        }

        $filtered = array_filter($words, function (string $word) {
            $stripped = mb_strtolower(trim($word, ".,!?;:\"'()"));
            return !in_array($stripped, self::FILLER_WORDS, true);
        });

        return implode(' ', $filtered);
    }

    private function normalizeWhitespaceAndPunctuation(string $text): string
    {
        // Tanda baca berulang -> satu karakter
        $text = preg_replace('/([!?.,])\1+/u', '$1', $text) ?? $text;

        // Spasi berlebih -> satu spasi
        $text = preg_replace('/\s+/u', ' ', $text) ?? $text;

        return $text;
    }

    public function clean(string $text): string
    {
        $text = $this->removeEmojis($text);
        $text = $this->removeLaughter($text);
        $text = $this->removeFillerWords($text);
        $text = $this->normalizeWhitespaceAndPunctuation($text);

        return trim($text);
    }
}
