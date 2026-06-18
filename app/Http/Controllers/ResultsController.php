<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\View\View;
use Illuminate\Support\Facades\Log;
use App\Models\Recommendations;
use App\Models\RecommendationDetail;
use App\Models\QuestionnaireSession;
use App\Models\Major;

class ResultsController extends Controller
{
    protected $fastapiUrl;

    public function __construct()
    {
        $this->fastapiUrl = env('FASTAPI_URL', 'http://l127.0.0.1:8001');
    }

    public function index()
    {
        // 1. Validasi questionnaire_session_id
        $questionnaireSessionId = session('questionnaire_session_id');

        if (!$questionnaireSessionId) {
            return redirect()->to('/mulai')
                ->with('error', 'Data kuesioner tidak ditemukan.');
        }

        // 2. Validasi questionnaire session
        $questionnaireSession = QuestionnaireSession::find($questionnaireSessionId);
        if (!$questionnaireSession) {
            return redirect()->to('/mulai')
                ->with('error', 'Session kuesioner tidak valid.');
        }

        // 3. Cek cache — sudah pernah direkomendasikan?
        $existingRecommendation = Recommendations::where('questionnaire_session_id', $questionnaireSessionId)
            ->with('details.major')
            ->first();

        if ($existingRecommendation && $existingRecommendation->details->count() > 0) {
            $recommendations = $this->transformFromDatabase($existingRecommendation, $questionnaireSession);
            $insight = $this->generateInsight($recommendations);
            return view('pages.results', compact('recommendations', 'insight'));
        }

        // 4. Validasi input_profile_text
        $inputProfileText = session('input_profile_text');
        if (!$inputProfileText) {
            return redirect()->to('/chat')
                ->with('error', 'Data profil tidak ditemukan.');
        }

        // 5. Siapkan data buat FastAPI
        $riasecScores = [
            'R' => $questionnaireSession->r_score,
            'I' => $questionnaireSession->i_score,
            'A' => $questionnaireSession->a_score,
            'S' => $questionnaireSession->s_score,
            'E' => $questionnaireSession->e_score,
            'C' => $questionnaireSession->c_score,
        ];

        // 6. Panggil FastAPI
        $fastApiResponse = $this->callFastAPI($riasecScores, $inputProfileText);

        Log::info('FastAPI response', ['data' => json_encode($fastApiResponse)]);

        if (!$fastApiResponse['success']) {
            Log::error('FastAPI error: ' . ($fastApiResponse['message'] ?? 'Unknown'));
            return redirect()->to('/mulai')
                ->with('error', 'Gagal mendapatkan rekomendasi. Silakan coba lagi.');
        }

        // 7. Simpan ke database
        $recommendation = $this->saveToDatabase(
            $questionnaireSessionId,
            $inputProfileText,
            $fastApiResponse['data']
        );

        // 8. Tampilkan
        $recommendation = Recommendations::where('questionnaire_session_id', $questionnaireSessionId)
            ->with('details.major')
            ->latest()
            ->first();

        $recommendations = $this->transformFromDatabase($recommendation, $questionnaireSession);
    }

    private function callFastAPI(array $riasecScores, string $inputProfileText, int $topK = 5): array
    {
        try {
            // 1. Health check
            $health = Http::timeout(5)->get($this->fastapiUrl . '/api/health');
            if (!$health->successful() || ($health['status'] ?? '') !== 'ok') {
                return ['success' => false, 'message' => 'AI service tidak tersedia'];
            }

            // 2. Kirim request rekomendasi
            $response = Http::timeout(30)->post($this->fastapiUrl . '/api/recommend', [
                'riasec_scores' => $riasecScores,
                'chatbot_answers' => $inputProfileText,
                'top_k' => $topK,
            ]);

            if ($response->successful()) {
                return [
                    'success' => true,
                    'data' => $response->json(),
                ];
            }

            return [
                'success' => false,
                'message' => 'FastAPI returned status: ' . $response->status(),
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
            ];
        }
    }

    private function saveToDatabase(int $sessionId, string $inputText, array $response): void
    {
        $recommendationId = session('recommendation_id');

        $recommendations = $response['recommendations'] ?? [];

        foreach ($recommendations as $item) {
            $major = Major::where('major_name', $item['jurusan'])->first();
            if (!$major) continue;

            RecommendationDetail::create([
                'recommendation_id' => $recommendationId,
                'major_id' => $major->id,
                'similarity_score' => $item['final_score'] ?? $item['chatbot_similarity'] ?? 0,
                'riasec_match_score' => $item['riasec_similarity'] ?? null,
                'rank' => $item['rank'],
                'reasoning' => $item['reasoning'] ?? '',
                'matched_keywords' => $item['matched_keywords'] ?? '',
            ]);
        }
    }

    private function transformFromDatabase(Recommendations $recommendation, QuestionnaireSession $questionnaireSession): array
    {
        $transformed = [];
        $traits = $this->getTraitsFromQuestionnaire($questionnaireSession);

        foreach ($recommendation->details as $detail) {
            $matchedKeywords = json_decode($detail->matched_keywords, true) ?? [];

            $transformed[] = [
                'rank' => $detail->rank,
                'major' => $detail->major->major_name,
                'bidang' => $detail->major->field ?? 'Umum',
                'score' => $detail->similarity_score,
                'confidence' => $this->getConfidenceLabel($detail->similarity_score),
                'matched_keywords' => $matchedKeywords,
                'reasoning' => $detail->reasoning ?? '',
                'description' => $detail->reasoning ?? '',
                'color' => $this->getColorByRank($detail->rank),
                'tags' => $this->getTagsFromMajor($detail->major),
                'traits' => $traits,
            ];
        }

        usort($transformed, fn($a, $b) => $a['rank'] <=> $b['rank']);

        return $transformed;
    }

    private function getTraitsFromQuestionnaire(QuestionnaireSession $session): array
    {
        $scores = [
            'Realistic' => $session->r_score ?? 0,
            'Investigative' => $session->i_score ?? 0,
            'Artistic' => $session->a_score ?? 0,
            'Social' => $session->s_score ?? 0,
            'Enterprising' => $session->e_score ?? 0,
            'Conventional' => $session->c_score ?? 0,
        ];

        $maxScore = max($scores);
        if ($maxScore > 0) {
            foreach ($scores as $key => $value) {
                $scores[$key] = round(($value / $maxScore) * 100);
            }
        }

        $result = [];
        foreach ($scores as $label => $val) {
            $result[] = [
                'label' => $label,
                'val' => $val,
            ];
        }

        return $result;
    }

    private function getTagsFromMajor(Major $major): array
    {
        if ($major->keywords && $major->keywords !== '') {
            $keywords = explode(',', $major->keywords);
            $tags = array_map('trim', $keywords);
            return array_slice($tags, 0, 3);
        }

        if ($major->interests && $major->interests !== '') {
            $interests = explode(',', $major->interests);
            $tags = array_map('trim', $interests);
            return array_slice($tags, 0, 3);
        }

        return [];
    }

    private function getConfidenceLabel(float $score): string
    {
        if ($score >= 0.75) return 'Sangat Cocok';
        if ($score >= 0.60) return 'Cocok';
        if ($score >= 0.45) return 'Cukup Cocok';
        return 'Kurang Cocok';
    }

    private function getColorByRank(int $rank): string
    {
        return match ($rank) {
            1 => '#4f46e5',
            2 => '#7c3aed',
            3 => '#6366f1',
            4 => '#8b5cf6',
            5 => '#a78bfa',
            default => '#818cf8',
        };
    }

    private function generateInsight(array $recommendations): string
    {
        if (empty($recommendations)) {
            return "Berdasarkan analisis profil, rekomendasi jurusan di atas memiliki kesesuaian dengan minat dan kepribadian Anda.";
        }

        $top = $recommendations[0];
        $confidence = $top['confidence'];
        $bidang = $top['bidang'];
        $major = $top['major'];

        return match ($confidence) {
            'Sangat Cocok' => "Profil Anda menunjukkan kesesuaian yang sangat tinggi dengan bidang {$bidang}. Jurusan {$major} sangat direkomendasikan karena selaras dengan minat Anda.",
            'Cocok' => "Profil Anda memiliki kesesuaian yang baik dengan bidang {$bidang}. Jurusan {$major} menawarkan peluang yang sejalan dengan minat Anda.",
            'Cukup Cocok' => "Profil Anda menunjukkan potensi di bidang {$bidang}. Jurusan {$major} bisa menjadi pilihan yang baik.",
            default => "Berdasarkan analisis profil, jurusan {$major} di bidang {$bidang} memiliki relevansi dengan minat Anda."
        };
    }
}
