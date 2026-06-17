# JurusIn – AI-Based Major Recommendation System

## Deskripsi Singkat Proyek

JurusIn adalah aplikasi web berbasis AI yang membantu pengguna mendapatkan rekomendasi jurusan kuliah berdasarkan minat, kepribadian, dan preferensi belajar pengguna. Sistem menggunakan kombinasi metode RIASEC, chatbot interaktif, serta semantic similarity berbasis SBERT untuk menghasilkan rekomendasi jurusan yang lebih relevan dan personal.

Sistem bekerja dalam 3 tahap:

1. **Kuesioner RIASEC (36 soal)**  
   Menghasilkan profil minat pengguna dalam 6 dimensi RIASEC (Realistic, Investigative, Artistic, Social, Enterprising, Conventional).

2. **Chatbot Interaktif (8 pertanyaan)**  
   Menggali preferensi pengguna terkait cara belajar, cara bekerja, dan lingkungan yang disukai.

3. **Mata Pelajaran Favorit**  
   Pengguna menginput 3–4 mata pelajaran favorit beserta nilainya sebagai informasi tambahan untuk memperkaya profil pengguna.

Hasil dari ketiga tahap tersebut digabung menjadi `input_profile_text`, kemudian diproses menggunakan model SBERT dan cosine similarity untuk mencari kecocokan dengan dataset jurusan.

---

# Teknologi yang Digunakan

## Frontend
- Laravel Blade
- Tailwind CSS
- JavaScript (Alpine.js)

## Backend
- Laravel (PHP)
- FastAPI (Python)

## AI / Recommendation System

| Komponen | Teknologi |
|---|---|
| Model Utama | Sentence-BERT (SBERT) |
| Model | `paraphrase-multilingual-MiniLM-L12-v2` |
| Metode Rekomendasi | Cosine Similarity |
| Penyimpanan Embedding | NumPy Array (.npy) |
| AI Service | FastAPI |

## Library Utama

### Python
- fastapi
- uvicorn
- sentence-transformers
- numpy
- pandas
- scikit-learn

### PHP
- laravel/framework
- guzzlehttp/guzzle

---

# Tautan Model

Model SBERT yang digunakan:

- HuggingFace Model:  
  https://huggingface.co/sentence-transformers/paraphrase-multilingual-MiniLM-L12-v2

Model dapat diunduh otomatis menggunakan library `sentence-transformers`:

```python
from sentence_transformers import SentenceTransformer

model = SentenceTransformer(
    "paraphrase-multilingual-MiniLM-L12-v2"
)
```

File embedding jurusan disimpan dalam format:

- `dataset_embeddings.npy`
- `jurusan_index.csv`

---

# Petunjuk Setup Environment

## Prasyarat

Pastikan perangkat telah terinstall:

- PHP 8.1+
- Composer
- Python 3.9+
- MySQL / MariaDB
- Node.js & NPM

---

## 1. Clone Repository

```bash
git clone https://github.com/choccoo4/jurusIn
cd jurusIn-v2
```

---

## 2. Setup Laravel

Copy file environment:

```bash
cp .env.example .env
```

Install dependency Laravel:

```bash
composer install
composer dump-autoload
composer update
```

Generate application key:

```bash
php artisan key:generate
```

Install dependency frontend:

```bash
npm install
npm install alpinejs @alpinejs/collapse @alpinejs/intersect
npm run dev
```

Menjalankan Laravel:

```bash
php artisan serve
```

Konfigurasi database pada file `.env`, lalu jalankan migration:

```bash
php artisan migrate
php artisan db:seed --class=QuestionnaireSeeder
php artisan db:seed --class=MajorSeeder
```

## 3. Setup FastAPI

Masuk ke folder FastAPI:

```bash
cd jurusin-fastapi
```

Buat virtual environment:

```bash
python -m venv venv
```

Aktifkan virtual environment:

### Windows

```bash
venv\Scripts\activate
```

### Linux / MacOS

```bash
source venv/bin/activate
```

Install dependency Python:

```bash
pip install -r requirements.txt
```

Download model SBERT:

```bash
python -c "from sentence_transformers import SentenceTransformer; SentenceTransformer('paraphrase-multilingual-MiniLM-L12-v2')"
```

---

## 4. Setup Environment Variables

### Laravel (.env)

```env
APP_NAME=JurusIn
APP_ENV=local
APP_URL=http://127.0.0.1:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=jurusin
DB_USERNAME=root
DB_PASSWORD=

FASTAPI_URL=http://127.0.0.1:8001
```

### FastAPI (.env)

```env
HOST=0.0.0.0
PORT=8001
MODEL_NAME=paraphrase-multilingual-MiniLM-L12-v2
```

---

# Cara Menjalankan Aplikasi

## 1. Jalankan Laravel

```bash
php artisan serve --port=8000
```

Laravel akan berjalan di:

```text
http://127.0.0.1:8000
```

---

## 2. Jalankan FastAPI

```bash
uvicorn app:app --host 127.0.0.1 --port 8001 --reload
```

FastAPI akan berjalan di:

```text
http://127.0.0.1:8001
```

---

## 3. Gunakan Aplikasi

1. Buka aplikasi melalui browser
2. Isi kuesioner RIASEC
3. Jawab pertanyaan chatbot
4. Input mata pelajaran yang disukai dan nilai nya
5. Sistem akan menampilkan rekomendasi jurusan berdasarkan hasil analisis AI

---

# Struktur Sistem

```text
Frontend (Laravel)
        ↓
Backend Laravel
        ↓
FastAPI (AI Service)
        ↓
SBERT + Cosine Similarity
        ↓
Hasil rekomendasi jurusan
```

---

# Tim Pengembang

- Putri Aulia Anggraini
- Muhammad Razzan Rianda Putra
- Aqila Luthfiyya Muadzah Subhan
- Wahyudi
- Yesaya Situmorang
