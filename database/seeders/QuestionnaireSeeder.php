<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class QuestionnaireSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Buat kuesioner
        $questionnaireId = DB::table('questionnaires')->insertGetId([
            'title' => 'Kuesioner RIASEC — Temukan Jurusan yang Cocok',
            'description' => '36 pertanyaan untuk mengukur preferensi kamu di 6 kategori: Realistic, Investigative, Artistic, Social, Enterprising, Conventional.',
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 2. Daftar pertanyaan per kategori
        $questions = [
            // ========== R: REALISTIC (Teknis, Praktik, Fisik) ==========
            ['category' => 'R', 'text' => 'Saya tertarik menggunakan alat, mesin, atau perangkat teknis.'],
            ['category' => 'R', 'text' => 'Saya suka membongkar, merakit, atau memperbaiki benda secara langsung.'],
            ['category' => 'R', 'text' => 'Saya lebih suka praktik langsung dibanding hanya teori.'],
            ['category' => 'R', 'text' => 'Saya menikmati aktivitas di luar ruangan atau pekerjaan lapangan.'],
            ['category' => 'R', 'text' => 'Saya suka pekerjaan yang menghasilkan sesuatu secara nyata.'],
            ['category' => 'R', 'text' => 'Saya tertarik pada kegiatan teknis atau mekanikal.'],

            // ========== I: INVESTIGATIVE (Logika, Analisis, Penelitian) ==========
            ['category' => 'I', 'text' => 'Saya suka memecahkan masalah yang membutuhkan logika.'],
            ['category' => 'I', 'text' => 'Saya menikmati menganalisis suatu persoalan secara mendalam.'],
            ['category' => 'I', 'text' => 'Saya tertarik pada matematika, sains, atau fenomena ilmiah.'],
            ['category' => 'I', 'text' => 'Saya tertarik mencari tahu bagaimana konsep atau sistem bekerja.'],
            ['category' => 'I', 'text' => 'Saya sering mempertanyakan alasan di balik suatu fenomena.'],
            ['category' => 'I', 'text' => 'Saya senang mempelajari hal-hal baru secara mendalam.'],

            // ========== A: ARTISTIC (Kreativitas, Ekspresi, Estetika) ==========
            ['category' => 'A', 'text' => 'Saya suka membuat desain, karya visual, atau bentuk kreativitas lainnya.'],
            ['category' => 'A', 'text' => 'Saya menikmati aktivitas yang membutuhkan kreativitas dan ide baru.'],
            ['category' => 'A', 'text' => 'Saya tertarik pada seni, musik, atau media kreatif.'],
            ['category' => 'A', 'text' => 'Saya lebih suka pekerjaan yang fleksibel dibanding terlalu teratur.'],
            ['category' => 'A', 'text' => 'Saya suka mengekspresikan ide melalui karya atau cara yang unik.'],
            ['category' => 'A', 'text' => 'Saya menikmati membuat sesuatu berdasarkan imajinasi sendiri.'],

            // ========== S: SOCIAL (Komunikasi, Membantu, Interaksi) ==========
            ['category' => 'S', 'text' => 'Saya senang membantu orang lain memahami sesuatu.'],
            ['category' => 'S', 'text' => 'Saya nyaman bekerja dalam tim.'],
            ['category' => 'S', 'text' => 'Saya suka mendengarkan dan memahami masalah orang lain.'],
            ['category' => 'S', 'text' => 'Saya menikmati aktivitas yang melibatkan komunikasi dengan banyak orang.'],
            ['category' => 'S', 'text' => 'Saya mudah memahami perasaan atau kebutuhan orang lain.'],
            ['category' => 'S', 'text' => 'Saya merasa puas ketika dapat membantu orang lain.'],

            // ========== E: ENTERPRISING (Leadership, Bisnis, Persuasi) ==========
            ['category' => 'E', 'text' => 'Saya suka memimpin kelompok atau kegiatan.'],
            ['category' => 'E', 'text' => 'Saya tertarik pada dunia bisnis atau kewirausahaan.'],
            ['category' => 'E', 'text' => 'Saya percaya diri saat berbicara di depan orang banyak.'],
            ['category' => 'E', 'text' => 'Saya suka meyakinkan orang lain terhadap suatu ide.'],
            ['category' => 'E', 'text' => 'Saya tertarik mengembangkan strategi atau rencana untuk mencapai tujuan.'],
            ['category' => 'E', 'text' => 'Saya berani mengambil keputusan dalam situasi tertentu.'],

            // ========== C: CONVENTIONAL (Keteraturan, Administrasi, Detail) ==========
            ['category' => 'C', 'text' => 'Saya menyukai pekerjaan yang terstruktur dan teratur.'],
            ['category' => 'C', 'text' => 'Saya merasa nyaman ketika segala sesuatu berjalan sesuai rencana.'],
            ['category' => 'C', 'text' => 'Saya nyaman bekerja dengan data atau angka.'],
            ['category' => 'C', 'text' => 'Saya suka membuat daftar, jadwal, atau pengarsipan.'],
            ['category' => 'C', 'text' => 'Saya lebih nyaman mengikuti prosedur yang jelas.'],
            ['category' => 'C', 'text' => 'Saya menikmati pekerjaan yang membutuhkan ketelitian tinggi.'],
        ];

        // 3. Insert semua pertanyaan
        foreach ($questions as $index => $q) {
            DB::table('questions')->insert([
                'questionnaire_id' => $questionnaireId,
                'question_text'    => $q['text'],
                'order_number'     => $index + 1,
                'riasec_category'  => $q['category'],
                'riasec_weight'    => 1.0,
                'created_at'       => now(),
                'updated_at'       => now(),
            ]);
        }
    }
}
