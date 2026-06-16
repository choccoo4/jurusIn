<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecommendationDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'recommendation_id',
        'major_id',
        'similarity_score',
        'riasec_match_score',
        'rank',
        'reasoning',
        'matched_keywords',
    ];

    public function recommendation()
    {
        return $this->belongsTo(Recommendations::class);
    }

    public function major()
    {
        return $this->belongsTo(Major::class);
    }
}
