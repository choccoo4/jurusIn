<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Question;
use App\Services\RiasecInterpretationService;

class QuestionnaireController extends Controller
{
    private RiasecInterpretationService $riasecService;

    public function __construct(RiasecInterpretationService $riasecService)
    {
        $this->riasecService = $riasecService;
    }

    public function index(): View
    {
        $questions = Question::orderBy('order_number')->get([
            'id',
            'question_text',
            'riasec_category',
            'riasec_weight',
        ])->toArray();

        return view('pages.questionnaire', [
            'questions' => $questions,
        ]);
    }

    // ========== API: SAVE ANSWERS ==========
    public function save(Request $request)
    {
        $validated = $request->validate([
            'session_id'       => 'required|string',
            'questionnaire_id' => 'required|integer',
            'answers'          => 'required|array',
            'answers.*.question_id' => 'required|integer',
            'answers.*.value'       => 'required|integer|min:1|max:5',
            'answers.*.category'    => 'required|string|in:R,I,A,S,E,C',
        ]);

        DB::beginTransaction();
        try {
            // 1. Simpan session
            $sessionId = DB::table('questionnaire_sessions')->insertGetId([
                'session_id'       => $validated['session_id'],
                'questionnaire_id' => $validated['questionnaire_id'],
                'status'           => 'completed',
                'created_at'       => now(),
                'updated_at'       => now(),
            ]);

            // 2. Hitung RIASEC scores
            $scores = $this->calculateRIASEC($validated['answers']);

            // 3. Generate profile text ← TAMBAHIN INI
            $profileText = $this->riasecService->generateProfileText($scores);

            // 4. Update scores di session
            DB::table('questionnaire_sessions')
                ->where('id', $sessionId)
                ->update([
                    'r_score' => $scores['R'],
                    'i_score' => $scores['I'],
                    'a_score' => $scores['A'],
                    's_score' => $scores['S'],
                    'e_score' => $scores['E'],
                    'c_score' => $scores['C'],
                ]);

            // 5. Simpan jawaban per pertanyaan
            foreach ($validated['answers'] as $answer) {
                DB::table('user_answers')->insert([
                    'questionnaire_session_id' => $sessionId,
                    'question_id'              => $answer['question_id'],
                    'answer_value'             => $answer['value'],
                    'created_at'               => now(),
                    'updated_at'               => now(),
                ]);
            }

            DB::commit();

            return response()->json([
                'success'    => true,
                'session_id' => $validated['session_id'],
                'scores'     => $scores,
                'profile_text' => $profileText,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    // ========== RIASEC CALCULATOR ==========
    private function calculateRIASEC(array $answers): array
    {
        $scores = ['R' => 0, 'I' => 0, 'A' => 0, 'S' => 0, 'E' => 0, 'C' => 0];
        $counts = ['R' => 0, 'I' => 0, 'A' => 0, 'S' => 0, 'E' => 0, 'C' => 0];

        foreach ($answers as $answer) {
            $category = $answer['category'];
            if (isset($scores[$category])) {
                $scores[$category] += $answer['value'];
                $counts[$category]++;
            }
        }

        // Konversi ke persentase (max 30 per category: 6 soal × 5)
        foreach ($scores as $key => $total) {
            $maxScore = $counts[$key] * 5;
            $scores[$key] = $maxScore > 0 ? round(($total / $maxScore) * 100, 2) : 0;
        }

        return $scores;
    }
}
