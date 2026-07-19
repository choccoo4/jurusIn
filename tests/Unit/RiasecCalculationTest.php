<?php

namespace Tests\Unit;

use Tests\TestCase;

class RiasecCalculationTest extends TestCase
{
    public function test_all_max_scores_return_100_percent()
    {
        $answers = [
            ['category' => 'R', 'value' => 5],
            ['category' => 'R', 'value' => 5],
            ['category' => 'R', 'value' => 5],
            ['category' => 'R', 'value' => 5],
            ['category' => 'R', 'value' => 5],
            ['category' => 'R', 'value' => 5],
        ];

        $scores = $this->calculateRIASEC($answers);
        $this->assertEquals(100, $scores['R']);
    }

    public function test_all_min_scores_return_0_percent()
    {
        $answers = [
            ['category' => 'I', 'value' => 1],
            ['category' => 'I', 'value' => 1],
            ['category' => 'I', 'value' => 1],
            ['category' => 'I', 'value' => 1],
            ['category' => 'I', 'value' => 1],
            ['category' => 'I', 'value' => 1],
        ];

        $scores = $this->calculateRIASEC($answers);
        $this->assertEquals(20, $scores['I']); // 6/30 = 20%
    }

    public function test_mixed_categories_calculate_correctly()
    {
        $answers = [
            ['category' => 'R', 'value' => 4],
            ['category' => 'I', 'value' => 5],
            ['category' => 'A', 'value' => 3],
            ['category' => 'R', 'value' => 5],
            ['category' => 'I', 'value' => 4],
            ['category' => 'A', 'value' => 5],
        ];

        $scores = $this->calculateRIASEC($answers);
        $this->assertEquals(round((9 / 10) * 100, 2), $scores['R']); // 2 soal R: 4+5=9, max 10
    }

    public function test_empty_answers_return_zero()
    {
        $scores = $this->calculateRIASEC([]);
        foreach (['R', 'I', 'A', 'S', 'E', 'C'] as $key) {
            $this->assertEquals(0, $scores[$key]);
        }
    }

    public function test_single_answer_per_category()
    {
        $answers = [
            ['category' => 'R', 'value' => 3],
            ['category' => 'I', 'value' => 4],
            ['category' => 'A', 'value' => 5],
            ['category' => 'S', 'value' => 2],
            ['category' => 'E', 'value' => 1],
            ['category' => 'C', 'value' => 3],
        ];

        $scores = $this->calculateRIASEC($answers);
        $this->assertEquals(60, $scores['R']);  // 3/5 = 60%
        $this->assertEquals(80, $scores['I']);  // 4/5 = 80%
        $this->assertEquals(100, $scores['A']); // 5/5 = 100%
        $this->assertEquals(40, $scores['S']);  // 2/5 = 40%
        $this->assertEquals(20, $scores['E']);  // 1/5 = 20%
        $this->assertEquals(60, $scores['C']);  // 3/5 = 60%
    }

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

        foreach ($scores as $key => $total) {
            $maxScore = $counts[$key] * 5;
            $scores[$key] = $maxScore > 0 ? round(($total / $maxScore) * 100, 2) : 0;
        }

        return $scores;
    }
}
