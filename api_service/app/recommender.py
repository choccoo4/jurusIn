import numpy as np
import pandas as pd
from sentence_transformers import SentenceTransformer
from sklearn.feature_extraction.text import TfidfVectorizer

from app.preprocessing import (
    preprocess_for_tfidf,
    extract_user_keywords,
    find_matching_keywords,
    extract_keywords_list,
)


# ── Konstanta ──────────────────────────────────────────────────────────────────

RIASEC_KEYS = ['R', 'I', 'A', 'S', 'E', 'C']

RIASEC_MAPPING = {
    "R": "aktivitas teknis praktik alat mekanik",
    "I": "logika analisis penelitian problem solving",
    "A": "kreativitas desain seni ekspresi",
    "S": "komunikasi membantu orang interaksi sosial",
    "E": "leadership bisnis persuasi manajemen",
    "C": "administrasi detail data keteraturan",
}

RIASEC_LABELS = {
    "R": "Praktis dan Teknis",
    "I": "Analitis dan Investigatif",
    "A": "Kreatif dan Ekspresif",
    "S": "Kolaboratif dan Interpersonal",
    "E": "Inisiatif dan Kepemimpinan",
    "C": "Terstruktur dan Sistematis",
}

COLUMN_ALIASES = {
    'jurusan': 'Jurusan', 'nama_jurusan': 'Jurusan', 'major': 'Jurusan',
    'bidang': 'Bidang', 'field': 'Bidang',
    'deskripsi': 'Deskripsi',
    'minat': 'Minat', 'interest': 'Minat',
    'keywords': 'Keywords', 'keyword': 'Keywords',
    'combined_text': 'combined_text', 'combinedtext': 'combined_text',
    'teks_gabungan': 'combined_text',
}

BASE_REQUIRED_COLUMNS = ['Jurusan', 'Bidang', 'Deskripsi', 'Minat', 'Keywords']
CANONICAL_COLUMNS = BASE_REQUIRED_COLUMNS + ['combined_text']


# ── State Global — di-load sekali saat startup ────────────────────────────────
# Variabel ini diisi oleh fungsi load_resources() yang dipanggil di main.py

df: pd.DataFrame = None
dataset_embeddings: np.ndarray = None
sbert_model: SentenceTransformer = None
tfidf: TfidfVectorizer = None
tfidf_matrix = None
riasec_embeddings_cache: dict = {}   # cache RIASEC — encode sekali, pakai selamanya


# ── Load Resources ─────────────────────────────────────────────────────────────

def load_resources(dataset_path: str, embeddings_path: str, model_path: str):
    """
    Dipanggil SEKALI saat FastAPI startup.
    Load dataset, embeddings, model SBERT, TF-IDF, dan cache RIASEC embeddings.
    """
    global df, dataset_embeddings, sbert_model, tfidf, tfidf_matrix, riasec_embeddings_cache

    # 1. Load dataset
    raw_df = pd.read_csv(dataset_path)
    raw_df.rename(
        columns={col: COLUMN_ALIASES.get(str(col).strip().lower().replace(' ', '_'), str(col).strip())
                 for col in raw_df.columns},
        inplace=True,
    )
    raw_df = raw_df.loc[:, ~raw_df.columns.duplicated()].copy()

    missing = [c for c in BASE_REQUIRED_COLUMNS if c not in raw_df.columns]
    if missing:
        raise RuntimeError(f"Kolom wajib tidak ditemukan di dataset: {missing}")

    for col in BASE_REQUIRED_COLUMNS:
        raw_df[col] = raw_df[col].fillna('').astype(str).str.strip()

    if 'combined_text' not in raw_df.columns:
        raw_df['combined_text'] = ''
    else:
        raw_df['combined_text'] = raw_df['combined_text'].fillna('').astype(str).str.strip()

    empty_mask = raw_df['combined_text'].eq('')
    if empty_mask.any():
        raw_df.loc[empty_mask, 'combined_text'] = (
            raw_df.loc[empty_mask, 'Deskripsi'] + ' ' +
            raw_df.loc[empty_mask, 'Minat'] + ' ' +
            raw_df.loc[empty_mask, 'Keywords']
        ).str.replace(r'\s+', ' ', regex=True).str.strip()

    raw_df.drop_duplicates(subset='Jurusan', inplace=True)
    raw_df.reset_index(drop=True, inplace=True)

    raw_combined = (
        raw_df['Deskripsi'].astype(str) + ' ' +
        raw_df['Minat'].astype(str) + ' ' +
        raw_df['Keywords'].astype(str)
    )
    raw_df['processed_text'] = raw_combined.apply(preprocess_for_tfidf)
    raw_df['keywords_list'] = raw_df['Keywords'].apply(extract_keywords_list)
    df = raw_df

    # 2. Load pre-computed embeddings
    dataset_embeddings = np.load(embeddings_path)
    if dataset_embeddings.shape[0] != len(df):
        raise RuntimeError(
            f"Jumlah embedding ({dataset_embeddings.shape[0]}) "
            f"tidak cocok dengan jumlah jurusan ({len(df)}). "
            f"Jalankan ulang Cell 8-9 di Colab lalu download ulang .npy"
        )

    # 3. Load model SBERT
    sbert_model = SentenceTransformer(model_path)

    # 4. Build TF-IDF matrix
    tfidf = TfidfVectorizer(max_features=5000)
    tfidf_matrix = tfidf.fit_transform(df['processed_text'])

    # 5. Cache RIASEC embeddings — encode sekali, tidak perlu ulang tiap request
    for key, text in RIASEC_MAPPING.items():
        riasec_embeddings_cache[key] = sbert_model.encode(
            [text], normalize_embeddings=True, convert_to_numpy=True
        )[0]


def is_loaded() -> bool:
    return df is not None and dataset_embeddings is not None and sbert_model is not None


# ── User Profile ───────────────────────────────────────────────────────────────

def build_user_profile(scores: dict, answers: list[str]) -> dict:
    riasec_weights = {k: scores[k] / sum(scores.values()) for k in RIASEC_KEYS}
    dominant_riasec = sorted(RIASEC_KEYS, key=lambda k: scores[k], reverse=True)
    riasec_text = " ".join(RIASEC_MAPPING[k] for k in RIASEC_KEYS if scores[k] > 0)
    chatbot_text = " ".join(answers)
    raw_profile = f"{riasec_text} {chatbot_text}".strip()

    return {
        'questionnaire_scores': scores,
        'chatbot_answers': answers,
        'riasec_weights': riasec_weights,
        'dominant_riasec': dominant_riasec,
        'riasec_text': riasec_text,
        'chatbot_text': chatbot_text,
        'raw_profile': raw_profile,
        'tfidf_profile': preprocess_for_tfidf(raw_profile),
        'sbert_profile': raw_profile,
    }


# ── Embedding Helpers ──────────────────────────────────────────────────────────

def build_weighted_riasec_embedding(scores: dict) -> np.ndarray:
    total = sum(scores.values())
    weights = {k: scores[k] / total for k in RIASEC_KEYS}

    dim = sbert_model.get_sentence_embedding_dimension()
    weighted_emb = np.zeros(dim)
    for key in RIASEC_KEYS:
        weighted_emb += weights[key] * riasec_embeddings_cache[key]

    return weighted_emb / np.linalg.norm(weighted_emb)


# ── Confidence & Reasoning ─────────────────────────────────────────────────────

def get_confidence_label(score: float) -> str:
    if score >= 0.75:
        return "Sangat Cocok"
    if score >= 0.60:
        return "Cocok"
    if score >= 0.45:
        return "Cukup Cocok"
    return "Kurang Cocok"


def generate_recommendation_reason(
    row: pd.Series,
    matching_keywords: list,
    dominant_riasec_codes: list,
) -> str:
    dominant_text = ", ".join(RIASEC_LABELS[c] for c in dominant_riasec_codes[:2])
    if matching_keywords:
        keyword_text = ", ".join(matching_keywords[:4])
        return (
            f"Jurusan ini cocok karena minat kamu pada {keyword_text} "
            f"selaras dengan bidang {row['Bidang']}. "
            f"Profil belajarmu juga menunjukkan kecenderungan {dominant_text}."
        )
    if row['chatbot_similarity'] >= row['riasec_similarity']:
        return (
            f"Berdasarkan cerita kamu, jurusan ini paling dekat dengan "
            f"bidang {row['Bidang']}. Gaya belajar kamu juga mendukung ke arah {dominant_text}."
        )
    return (
        f"Profil RIASEC kamu menunjukkan kecenderungan kuat ke {dominant_text}, "
        f"yang sangat relevan dengan bidang {row['Bidang']}."
    )


# ── Fungsi Rekomendasi Utama ───────────────────────────────────────────────────

def recommend(
    scores: dict,
    answers: list[str],
    top_k: int = 3,
    chatbot_weight: float = 0.70,
    riasec_weight: float = 0.30,
) -> dict:
    """
    Fungsi utama yang dipanggil endpoint FastAPI.
    Menerima skor RIASEC dan jawaban chatbot, mengembalikan dict hasil rekomendasi.
    """
    profile = build_user_profile(scores, answers)
    user_keywords = extract_user_keywords(profile['raw_profile'])

    # Encode chatbot text user
    chatbot_emb = sbert_model.encode(
        [profile['chatbot_text']],
        normalize_embeddings=True,
        convert_to_numpy=True,
    )[0]

    # Ambil RIASEC embedding dari cache (tidak encode ulang)
    riasec_emb = build_weighted_riasec_embedding(scores)

    # Hitung similarity
    chatbot_sim = np.dot(dataset_embeddings, chatbot_emb)
    riasec_sim = np.dot(dataset_embeddings, riasec_emb)
    final_scores = chatbot_weight * chatbot_sim + riasec_weight * riasec_sim

    # Ambil top-k
    top_indices = np.argsort(final_scores)[::-1][:top_k]

    recommendations = []
    for rank, idx in enumerate(top_indices, start=1):
        row = df.iloc[idx]
        jurusan_keywords = row.get('keywords_list') or []
        matching_kw = find_matching_keywords(user_keywords, jurusan_keywords)

        row_data = pd.Series({
            'Bidang': row['Bidang'],
            'chatbot_similarity': float(chatbot_sim[idx]),
            'riasec_similarity': float(riasec_sim[idx]),
        })

        recommendations.append({
            'rank': rank,
            'jurusan': row['Jurusan'],
            'bidang': row['Bidang'],
            'final_score': round(float(final_scores[idx]), 4),
            'chatbot_similarity': round(float(chatbot_sim[idx]), 4),
            'riasec_similarity': round(float(riasec_sim[idx]), 4),
            'confidence': get_confidence_label(float(final_scores[idx])),
            'matched_keywords': ", ".join(matching_kw[:5]) if matching_kw else "-",
            'reasoning': generate_recommendation_reason(
                row_data, matching_kw, profile['dominant_riasec']
            ),
        })

    return {
        'success': True,
        'top_k': top_k,
        'dominant_riasec': profile['dominant_riasec'][:3],
        'user_keywords': user_keywords[:12],
        'recommendations': recommendations,
    }
