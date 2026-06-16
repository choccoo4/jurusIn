import re
import string
import pandas as pd


# ── Konstanta ──────────────────────────────────────────────────────────────────

FRASA_KEYWORDS = [
    "problem solving", "data science", "machine learning", "deep learning",
    "artificial intelligence", "software engineering", "business intelligence",
    "big data", "cloud computing", "internet of things", "cyber security",
    "user experience", "user interface", "project management", "supply chain",
    "system analyst", "quality control", "research and development",
    "pemecahan masalah", "pengembangan perangkat lunak", "kecerdasan buatan"
]

REASONING_STOPWORDS = {
    "saya", "aku", "dan", "atau", "tapi", "namun", "karena", "jika", "maka",
    "sangat", "cukup", "agak", "sedikit", "banyak", "selalu", "sering",
    "ingin", "akan", "telah", "sudah", "belum", "sedang", "pernah",
    "bekerja", "sendiri", "tim", "kecil", "menonjol", "merupakan", "yakni",
    "maupun", "kemampuan", "aktivitas", "kegiatan", "hal", "sesuatu", "semua",
    "dengan", "dari", "untuk", "pada", "dalam", "oleh", "kepada", "bagi",
    "problem", "solving"
}

NORMALIZATION_DICT = {
    "gk": "tidak", "ga": "tidak", "nggak": "tidak",
    "dll": "dan lain lain", "yg": "yang", "yng": "yang", "dgn": "dengan",
    "bgt": "banget", "sdh": "sudah", "udah": "sudah",
    "aja": "saja", "si": "sih", "jg": "juga",
    "gw": "saya",
}


# ── Pipeline TF-IDF ────────────────────────────────────────────────────────────

def case_folding(text: str) -> str:
    return text.lower()


def cleaning_text(text: str) -> str:
    text = re.sub(r'http\S+|www\S+', '', text)
    text = re.sub(r'\d+', '', text)
    return text


def remove_special_characters(text: str) -> str:
    return text.translate(str.maketrans('', '', string.punctuation))


def remove_whitespace(text: str) -> str:
    return " ".join(text.split())


def normalize_text(text: str) -> str:
    return " ".join(NORMALIZATION_DICT.get(w, w) for w in text.split())


def preprocess_for_tfidf(text: str) -> str:
    """Pipeline agresif khusus untuk TF-IDF."""
    text = str(text)
    text = case_folding(text)
    text = cleaning_text(text)
    text = remove_special_characters(text)
    text = remove_whitespace(text)
    text = normalize_text(text)
    return text


# ── Keyword Extraction ─────────────────────────────────────────────────────────

def preserve_frasa(text: str) -> str:
    text_lower = str(text).lower()
    for frasa in FRASA_KEYWORDS:
        text_lower = text_lower.replace(frasa, frasa.replace(" ", "_"))
    return text_lower


def restore_frasa(text: str) -> str:
    for frasa in FRASA_KEYWORDS:
        text = text.replace(frasa.replace(" ", "_"), frasa)
    return text


def extract_user_keywords(user_text: str) -> list[str]:
    text_preserved = preserve_frasa(user_text)
    keywords = []
    for word in text_preserved.split():
        restored = restore_frasa(word)
        is_phrase = "_" in word and restored in FRASA_KEYWORDS
        if is_phrase:
            keywords.append(restored)
        elif len(word) > 3 and word not in REASONING_STOPWORDS and not word.isdigit():
            keywords.append(restored)

    seen = set()
    unique = []
    for kw in keywords:
        if kw not in seen:
            seen.add(kw)
            unique.append(kw)
    return unique


def find_matching_keywords(user_keywords: list, jurusan_keywords_list: list) -> list:
    matches = []
    for jurusan_kw in jurusan_keywords_list:
        jurusan_kw_lower = str(jurusan_kw).lower()
        for user_kw in user_keywords:
            user_kw_lower = str(user_kw).lower()
            if (
                jurusan_kw_lower == user_kw_lower
                or jurusan_kw_lower in user_kw_lower
                or user_kw_lower in jurusan_kw_lower
            ):
                if jurusan_kw not in matches:
                    matches.append(jurusan_kw)
    return matches


def extract_keywords_list(keywords_str) -> list:
    if pd.isna(keywords_str):
        return []
    return [k.strip().lower() for k in str(keywords_str).split(',') if k.strip()]
