<?php

namespace App\Services;

class ChatbotValidationService
{
    /**
     * Create a new class instance.
     */
    private array $questions;
    private array $blacklist;
    private int $minWords;
    private array $subjects;
    private string $subjectPrompt;
    private int $subjectRequired;

    public function __construct()
    {
        $config = config('chatbot');
        $this->questions = $config['questions'];
        $this->blacklist = $config['blacklist'];
        $this->minWords = $config['min_words'];
        $this->subjects = $config['subjects'];
        $this->subjectPrompt = $config['subject_selection_prompt'];
        $this->subjectRequired = $config['subject_selection_required'];
    }

    /**
     * Validate + return next question.
     */
    public function processAnswer(int $questionId, string $answer, array $sessionState = []): array
    {
        $question = $this->findQuestion($questionId);
        if (!$question) {
            return ['valid' => false, 'message' => 'Pertanyaan tidak ditemukan.'];
        }

        // Layer 1: Basic check
        $basic = $this->basicCheck($answer);
        if (!$basic['valid']) {
            return $this->invalidResponse($basic['message'], $questionId);
        }

        // Layer 2: Context check
        $context = $this->contextCheck($answer, $question['context']);
        if (!$context['valid']) {
            return $this->invalidResponse($question['follow_up'], $questionId);
        }

        // VALID — simpan & lanjut
        $answers = $sessionState['answers'] ?? [];
        $answers[] = [
            'question_id' => $questionId,
            'question' => $question['question'],
            'answer' => $answer,
        ];

        $nextQuestionId = $questionId + 1;
        $isLastQuestion = $nextQuestionId > count($this->questions);

        // last questions. meminta data nilai dari 4 mata pelajaran yang dikuasai.
        if ($isLastQuestion) {
            return [
                'valid' => true,
                'completed' => false,
                'awaiting_subjects' => true,
                'answers' => $answers,
                'subject_prompt' => $this->subjectPrompt,
                'subjects' => $this->subjects,
                'subject_required' => $this->subjectRequired,
                'current_question' => $questionId,
                'message' => 'Kuisoner selesai, pilih mata pelajaran.',
            ];
        }

        // Return next question
        $nextQuestion = $this->findQuestion($nextQuestionId);

        return [
            'valid' => true,
            'completed' => false,
            'matched' => $context['matched'],
            'match_count' => count($context['matched']),
            'next_question' => [
                'id' => $nextQuestion['id'],
                'text' => $nextQuestion['question'],
            ],
            'current_question' => $nextQuestionId,
            'total_questions' => count($this->questions),
            'answers' => $answers,
        ];
    }

    public function processSubjectSelection(array $subjects, array $sessionState = []): array
    {
        if (count($subjects) !== $this->subjectRequired) {
            return [
                'valid' => false,
                'message' => "Pilih tepat {$this->subjectRequired} mata pelajaran ya!",
            ];
        }

        $validSubjectNames = $this->subjects;
        $cleaned = [];

        foreach ($subjects as $index => $item) {
            $no = $index + 1;

            if (!isset($item['name']) || !isset($item['grade'])) {
                return [
                    'valid' => false,
                    'message' => "Data mata pelajaran ke-{$no} tidak lengkap.",
                ];
            }

            $name = trim($item['name']);
            $grade = $item['grade'];

            if (!in_array($name, $validSubjectNames, true)) {
                return [
                    'valid' => false,
                    'message' => "Mata pelajaran \"{$name}\" tidak dikenali, pilih dari daftar yang tersedia.",
                ];
            }

            if (!is_numeric($grade)) {
                return [
                    'valid' => false,
                    'message' => "Nilai untuk \"{$name}\" harus berupa angka.",
                ];
            }

            $gradeFloat = (float) $grade;
            if ($gradeFloat < 0 || $gradeFloat > 100) {
                return [
                    'valid' => false,
                    'message' => "Nilai untuk \"{$name}\" harus antara 0 sampai 100.",
                ];
            }

            $cleaned[] = [
                'name' => $name,
                'grade' => round($gradeFloat, 1),
            ];
        }

        // duplikat mapel
        $names = array_column($cleaned, 'name');
        if (count(array_unique($names)) !== count($names)) {
            return [
                'valid' => false,
                'message' => 'Tidak bisa memilih mapel yang sama.',
            ];
        }

        $answers = $sessionState['answers'] ?? [];
        $chatProfileText = $this->generateChatProfileText($answers, $cleaned);

        return [
            'valid' => true,
            'completed' => true,
            'answers' => $answers,
            'selected_subjects' => $cleaned,
            'chat_profile_text' => $chatProfileText,
            'message' => 'Semua chat selesai.'
        ];
    }

    /**
     * Generate combined profile text.
     */
    public function generateChatProfileText(array $answers, array $selectedSubjects = []): string
    {
        $lines = array_map(fn($a) => "- {$a['answer']}", $answers);
        $text = implode("\n", $lines);

        if (!empty($selectedSubjects)) {
            $subjectLines = array_map(
                fn($s) => "{$s['name']} (nilai: {$s['grade']})",
                $selectedSubjects
            );
            $text .= "\n\nMata pelajaran favorit: " . implode(', ', $subjectLines) . '.';
        }

        return $text;
    }

    /**
     * Get all questions (for initial load).
     */
    public function getQuestions(): array
    {
        return array_map(function ($q) {
            return [
                'id' => $q['id'],
                'question' => $q['question'],
            ];
        }, $this->questions);
    }

    // return daftar mata pelajaran + prompt

    public function getSubjectSelectionMeta(): array
    {
        return [
            'prompt' => $this->subjectPrompt,
            'subjects' => $this->subjects,
            'required' => $this->subjectRequired,
        ];
    }

    /**
     * Get first question.
     */
    public function getFirstQuestion(): array
    {
        $first = $this->questions[0];
        return [
            'id' => $first['id'],
            'text' => $first['question'],
        ];
    }

    private function invalidResponse(string $message, int $currentQuestionId): array
    {
        return [
            'valid' => false,
            'completed' => false,
            'message' => $message,
            'current_question' => $currentQuestionId, // TETAP di pertanyaan yang sama
        ];
    }

    private function basicCheck(string $answer): array
    {
        $answer = trim($answer);

        if (empty($answer)) {
            return ['valid' => false, 'message' => 'Silakan isi jawaban kamu dulu ya!'];
        }

        $lowerAnswer = strtolower($answer);
        foreach ($this->blacklist as $badWord) {
            if (str_contains($lowerAnswer, $badWord)) {
                return ['valid' => false, 'message' => 'Coba ceritakan lebih spesifik ya. Jangan ragu untuk elaborasi!'];
            }
        }

        $wordCount = str_word_count($lowerAnswer);
        // Hitung juga karakter sebagai fallback (kata bahasa Indonesia kadang dihitung berbeda)
        $charCount = strlen(trim($lowerAnswer));
        if ($wordCount < $this->minWords && $charCount < 10) {
            return ['valid' => false, 'message' => 'Boleh ceritakan lebih detail? Jawaban yang lebih panjang membantu AI memahami kamu lebih baik.'];
        }

        return ['valid' => true];
    }

    private function contextCheck(string $answer, array $context): array
    {
        $lowerAnswer = strtolower($answer);
        $matched = [];

        foreach ($context as $keyword) {
            if (str_contains($lowerAnswer, $keyword)) {
                $matched[] = $keyword;
            }
        }

        return [
            'valid' => count($matched) >= 1,
            'matched' => $matched,
        ];
    }

    private function findQuestion(int $id): ?array
    {
        foreach ($this->questions as $q) {
            if ($q['id'] === $id) return $q;
        }
        return null;
    }
}
