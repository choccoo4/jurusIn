import os
from contextlib import asynccontextmanager

from dotenv import load_dotenv
from fastapi import FastAPI, HTTPException
from fastapi.middleware.cors import CORSMiddleware

from app.recommender import load_resources, recommend, is_loaded, df
from app.schemas import RecommendRequest, RecommendResponse, HealthResponse

load_dotenv()

DATASET_PATH    = os.getenv("DATASET_PATH", "data/Dataset.csv")
EMBEDDINGS_PATH = os.getenv("EMBEDDINGS_PATH", "data/dataset_embeddings.npy")
MODEL_PATH      = os.getenv("MODEL_PATH", "models")
CHATBOT_WEIGHT  = float(os.getenv("CHATBOT_WEIGHT", 0.70))
RIASEC_WEIGHT   = float(os.getenv("RIASEC_WEIGHT", 0.30))


# ── Startup & Shutdown ─────────────────────────────────────────────────────────

@asynccontextmanager
async def lifespan(app: FastAPI):
    """Load semua resource saat server start — hanya sekali."""
    print("🚀 JurusanIn API starting...")
    print(f"   Dataset   : {DATASET_PATH}")
    print(f"   Embeddings: {EMBEDDINGS_PATH}")
    print(f"   Model     : {MODEL_PATH}")
    try:
        load_resources(DATASET_PATH, EMBEDDINGS_PATH, MODEL_PATH)
        print("✅ Semua resource berhasil di-load. API siap menerima request.")
    except Exception as e:
        print(f"❌ Gagal load resource: {e}")
        raise
    yield
    print("👋 JurusanIn API shutdown.")


# ── App Instance ───────────────────────────────────────────────────────────────

app = FastAPI(
    title="JurusanIn API",
    description="API rekomendasi jurusan kuliah berbasis SBERT + RIASEC",
    version="2.0.0",
    lifespan=lifespan,
)

app.add_middleware(
    CORSMiddleware,
    allow_origins=["*"],
    allow_methods=["*"],
    allow_headers=["*"],
)


# ── Endpoints ──────────────────────────────────────────────────────────────────

@app.get("/", tags=["Root"])
def root():
    return {"message": "JurusanIn API v2.0 — gunakan POST /api/recommend"}


@app.get("/api/health", response_model=HealthResponse, tags=["Health"])
def health_check():
    """
    Cek apakah API hidup dan semua resource sudah ter-load.
    Laravel sebaiknya hit endpoint ini sebelum kirim request rekomendasi.
    """
    from app.recommender import df as loaded_df
    loaded = is_loaded()
    return HealthResponse(
        status="ok" if loaded else "loading",
        model_loaded=loaded,
        dataset_loaded=loaded_df is not None,
        total_jurusan=len(loaded_df) if loaded_df is not None else 0,
    )


@app.post("/api/recommend", response_model=RecommendResponse, tags=["Recommend"])
def get_recommendation(body: RecommendRequest):
    """
    Endpoint utama — terima skor RIASEC + jawaban chatbot, kembalikan Top-K rekomendasi jurusan.

    **Request body:**
    ```json
    {
        "riasec_scores": {"R": 18, "I": 28, "A": 15, "S": 12, "E": 10, "C": 14},
        "chatbot_answers": [
            "Saya sangat tertarik dengan teknologi dan pemrograman.",
            "Saya suka memecahkan masalah logika dan analisis data."
        ],
        "top_k": 3
    }
    ```

    **Notes:**
    - `riasec_scores` wajib berisi semua 6 key: R, I, A, S, E, C
    - `chatbot_answers` bisa berupa list string atau satu string panjang
    - `top_k` default 3, maksimal 10
    """
    if not is_loaded():
        raise HTTPException(status_code=503, detail="Model belum selesai di-load, coba beberapa saat lagi.")

    try:
        result = recommend(
            scores=body.riasec_scores,
            answers=body.chatbot_answers,
            top_k=body.top_k,
            chatbot_weight=CHATBOT_WEIGHT,
            riasec_weight=RIASEC_WEIGHT,
        )
        return RecommendResponse(**result)
    except ValueError as e:
        raise HTTPException(status_code=422, detail=str(e))
    except Exception as e:
        raise HTTPException(status_code=500, detail=f"Internal server error: {str(e)}")


@app.get("/api/jurusan", tags=["Jurusan"])
def list_jurusan():
    """
    Kembalikan daftar semua jurusan dan bidang yang ada di dataset.
    Berguna untuk frontend menampilkan daftar jurusan.
    """
    if not is_loaded():
        raise HTTPException(status_code=503, detail="Model belum selesai di-load.")

    from app.recommender import df as loaded_df
    data = loaded_df[['Jurusan', 'Bidang']].to_dict(orient='records')
    return {
        "success": True,
        "total": len(data),
        "data": data,
    }
