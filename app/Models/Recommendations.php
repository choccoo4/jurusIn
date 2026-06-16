<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;


class Recommendations extends Model
{
    protected $fillable = [
        'input_profile_text',
        'questionnaire_session_id',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relasi ke questionnaire session
     */
    public function questionnaireSession(): BelongsTo
    {
        return $this->belongsTo(QuestionnaireSession::class);
    }

    public function details(): HasMany
    {
        return $this->hasMany(RecommendationDetail::class, 'recommendation_id');
    }
}
