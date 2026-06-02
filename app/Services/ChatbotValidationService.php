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

    public function __construct()
    {
        $config = config('chatbot');
        $this->questions = $config['questions'];
        $this->blacklist = $config['blacklist'];
        $this->minWords = $config['min_words'];
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

        // Cek apakah ini pertanyaan terakhir
        if ($nextQuestionId > count($this->questions)) {
            return [
                'valid' => true,
                'completed' => true,
                'answers' => $answers,
                'message' => 'Semua pertanyaan selesai!',
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

    /**
     * Generate combined profile text.
     */
    public function generateChatProfileText(array $answers): string
    {
        return implode("\n", array_map(function ($a) {
            return "- {$a['answer']}";
        }, $answers));
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
        if ($wordCount < $this->minWords) {
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
