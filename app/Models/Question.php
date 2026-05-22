<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $fillable = [
        'questionnaire_id',
        'question_text',
        'order_number',
        'riasec_category',
        'riasec_weight',
    ];

    protected $casts = [
        'riasec_weight' => 'float',
    ];
}
