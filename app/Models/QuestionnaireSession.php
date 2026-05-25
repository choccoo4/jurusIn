<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use App\Models\Recommendations;

class QuestionnaireSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'session_id',
        'questionnaire_id',
        'status',
        'r_score',
        'i_score',
        'a_score',
        's_score',
        'e_score',
        'c_score',
    ];

    protected $casts = [
        'r_score' => 'decimal:2',
        'i_score' => 'decimal:2',
        'a_score' => 'decimal:2',
        's_score' => 'decimal:2',
        'e_score' => 'decimal:2',
        'c_score' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function userAnswers(): HasMany
    {
        return $this->hasMany(UserAnswer::class);
    }

    public function recommendation(): HasOne
    {
        return $this->hasOne(Recommendations::class);
    }
}
