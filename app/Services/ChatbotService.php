<?php

namespace App\Services;

use App\Models\Recommendations;
use App\Models\QuestionnaireSession;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use RuntimeException;

class ChatbotService
{
    private const RIASEC_PROFILE_TAG   = '[PROFIL RIASEC]';
    private const ADDITIONAL_PREF_TAG  = '[PREFERENSI TAMBAHAN]';
    private const SESSION_STATUS_DONE  = 'completed';

    public function __construct(
        private readonly RiasecInterpretationService $riasecService,
    ) {}

    /**
     * Save or update recommendation for a completed questionnaire session.
     *
     * @throws ModelNotFoundException When session is not found or not completed.
     * @throws RuntimeException       When recommendation cannot be persisted.
     */
    public function saveRecommendation(string $sessionId, string $chatData): Recommendations
    {
        $session = $this->findCompletedSession($sessionId);

        $questionnaireText = $this->resolveQuestionnaireText($session);

        $combinedText = $this->combineProfileTexts($questionnaireText, $chatData);

        return $this->upsertRecommendation($session, $combinedText);
    }

    private function findCompletedSession(string $sessionId): QuestionnaireSession
    {
        $session = QuestionnaireSession::where('session_id', $sessionId)
            ->where('status', self::SESSION_STATUS_DONE)
            ->first();

        if (!$session) {
            throw new ModelNotFoundException(
                'Session kuesioner tidak ditemukan atau belum selesai.'
            );
        }

        return $session;
    }

    private function resolveQuestionnaireText(QuestionnaireSession $session): string
    {
        $existing = $session->recommendation;

        if ($existing) {
            return $this->extractQuestionnaireText($existing->input_profile_text);
        }

        $scores = [
            'R' => $session->r_score,
            'I' => $session->i_score,
            'A' => $session->a_score,
            'S' => $session->s_score,
            'E' => $session->e_score,
            'C' => $session->c_score,
        ];

        return $this->riasecService->generateProfileText($scores);
    }

    private function upsertRecommendation(
        QuestionnaireSession $session,
        string $combinedText,
    ): Recommendations {
        $existing = $session->recommendation;

        if ($existing) {
            $existing->update(['input_profile_text' => $combinedText]);
            return $existing->fresh();
        }

        return Recommendations::create([
            'questionnaire_session_id' => $session->id,
            'input_profile_text'       => $combinedText,
        ]);
    }

    private function combineProfileTexts(string $questionnaireText, string $chatText): string
    {
        return implode("\n", [
            self::RIASEC_PROFILE_TAG,
            $questionnaireText,
            '',
            self::ADDITIONAL_PREF_TAG,
            $chatText,
        ]);
    }

    private function extractQuestionnaireText(string $combinedText): string
    {
        $pattern = sprintf(
            '/%s\s*(.*?)\s*%s/s',
            preg_quote(self::RIASEC_PROFILE_TAG, '/'),
            preg_quote(self::ADDITIONAL_PREF_TAG, '/'),
        );

        if (preg_match($pattern, $combinedText, $matches)) {
            return trim($matches[1]);
        }

        return $combinedText;
    }
}
