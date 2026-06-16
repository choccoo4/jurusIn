from pydantic import BaseModel, field_validator, model_validator
from typing import Optional


# ── Konstanta ──────────────────────────────────────────────────────────────────

RIASEC_KEYS = ['R', 'I', 'A', 'S', 'E', 'C']


# ── Request Schema ─────────────────────────────────────────────────────────────

class RecommendRequest(BaseModel):
    """
    Body yang dikirim Laravel ke FastAPI.

    Contoh:
    {
        "riasec_scores": {"R": 18, "I": 28, "A": 15, "S": 12, "E": 10, "C": 14},
        "chatbot_answers": [
            "Saya sangat tertarik dengan teknologi dan pemrograman.",
            "Saya suka memecahkan masalah logika dan analisis data."
        ],
        "top_k": 3
    }
    """
    riasec_scores: dict[str, float]
    chatbot_answers: list[str] | str
    top_k: Optional[int] = 3

    @field_validator('riasec_scores')
    @classmethod
    def validate_riasec(cls, v):
        missing = [k for k in RIASEC_KEYS if k not in v]
        if missing:
            raise ValueError(f"Skor RIASEC belum lengkap: {missing}")

        extra = [k for k in v if k not in RIASEC_KEYS]
        if extra:
            raise ValueError(f"Key RIASEC tidak dikenal: {extra}")

        for key, val in v.items():
            if not isinstance(val, (int, float)):
                raise ValueError(f"Skor {key} harus numerik")
            if val < 0:
                raise ValueError(f"Skor {key} tidak boleh negatif")

        if sum(v.values()) == 0:
            raise ValueError("Total skor RIASEC tidak boleh 0")

        return {k: float(v[k]) for k in RIASEC_KEYS}

    @field_validator('chatbot_answers')
    @classmethod
    def validate_chatbot(cls, v):
        if isinstance(v, str):
            v = [v]
        cleaned = [s.strip() for s in v if str(s).strip()]
        if not cleaned:
            raise ValueError("chatbot_answers tidak boleh kosong")
        return cleaned

    @field_validator('top_k')
    @classmethod
    def validate_top_k(cls, v):
        if v is None:
            return 3
        if v < 1:
            raise ValueError("top_k minimal 1")
        if v > 10:
            raise ValueError("top_k maksimal 10")
        return v


# ── Response Schema ────────────────────────────────────────────────────────────

class RecommendationItem(BaseModel):
    """Satu item rekomendasi jurusan."""
    rank: int
    jurusan: str
    bidang: str
    final_score: float
    chatbot_similarity: float
    riasec_similarity: float
    confidence: str
    matched_keywords: str
    reasoning: str


class RecommendResponse(BaseModel):
    """Response lengkap yang diterima Laravel."""
    success: bool
    top_k: int
    dominant_riasec: list[str]
    user_keywords: list[str]
    recommendations: list[RecommendationItem]


class HealthResponse(BaseModel):
    """Response untuk endpoint health check."""
    status: str
    model_loaded: bool
    dataset_loaded: bool
    total_jurusan: int
