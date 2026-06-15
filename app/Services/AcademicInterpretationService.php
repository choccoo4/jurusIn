<?php

namespace App\Services;

class AcademicInterpretationService
{
    public function interpret(int $score): string
    {
        if ($score >= 90) return 'sangat baik';
        if ($score >= 80) return 'baik';
        if ($score >= 70) return 'cukup baik';

        return 'minat';
    }

    public function describeSubject(string $name, int $score): string
    {
        $level = $this->interpret($score);

        if ($level === 'minat') {
            return "{$name} (nilai {$score} - hanya dianggap minat)";
        }

        return "{$name} (nilai {$score} - {$level})";
    }

    public function describeSubjects(array $subjects): string
    {
        $descriptions = array_map(
            fn(array $s) => $this->describeSubject($s['name'], $s['score']),
            $subjects
        );

        return implode(', ', $descriptions);
    }
}
