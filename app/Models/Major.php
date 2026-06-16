<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Major extends Model
{
    use HasFactory;

    protected $fillable = [
        'major_name',      // dari CSV: Jurusan
        'field',           // dari CSV: Bidang
        'description',     // dari CSV: Deskripsi
        'interests',       // dari CSV: Minat
        'keywords',        // dari CSV: Keywords
        'combined_text',   // dari CSV: combined_text
    ];

    public function recommendationDetails()
    {
        return $this->hasMany(RecommendationDetail::class, 'major_id');
    }
}
