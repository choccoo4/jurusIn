// resources/js/components/chatbot.js

export function chatbot() {
    return {
        input: '',
        messages: [],
        typing: false,
        showSuggestions: true,
        locked: false,
        turnCount: 0,
        maxTurns: 4,            // Maksimal 4 bot replies sebelum lock
        collectedInfo: {
            interest: null,     // Minat utama
            workStyle: null,    // Tim atau sendiri
            learningStyle: null,// Teori atau praktik
            personality: null,  // Santai atau serius
        },
        currentQuestion: 0,     // Track pertanyaan ke berapa

        suggestions: [
            'Saya suka coding dan teknologi',
            'Saya tertarik bisnis dan keuangan',
            'Saya suka desain dan seni',
            'Saya suka kesehatan dan sains',
        ],

        // ========== PERTANYAAN BOT ==========
        questions: [
            {
                id: 'interest',
                text: 'Hal apa yang paling menarik buat kamu?',
                followUp: 'Coba ceritakan lebih spesifik, misalnya: apakah kamu lebih suka kegiatan kreatif seperti menggambar, atau kegiatan teknis seperti coding?',
                keywords: {
                    tech: ['coding', 'teknologi', 'komputer', 'programming', 'it', 'software'],
                    creative: ['desain', 'seni', 'gambar', 'kreatif', 'visual', 'fotografi'],
                    business: ['bisnis', 'keuangan', 'marketing', 'jualan', 'wirausaha'],
                    health: ['kesehatan', 'dokter', 'medis', 'biologi', 'sains'],
                },
            },
            {
                id: 'workStyle',
                text: 'Kamu lebih suka bekerja sendiri atau dalam tim?',
                followUp: 'Maksudku, apakah kamu merasa lebih produktif saat sendiri atau justru lebih bersemangat saat diskusi dengan orang lain?',
                keywords: {
                    solo: ['sendiri', 'solo', 'mandiri', 'individu', 'fokus sendiri'],
                    team: ['tim', 'bareng', 'bersama', 'kolaborasi', 'diskusi', 'orang lain'],
                },
            },
            {
                id: 'learningStyle',
                text: 'Kamu lebih suka belajar dari teori dulu atau langsung praktik?',
                followUp: 'Contohnya, apakah kamu tipe yang baca buku/manual dulu sebelum mencoba, atau justru langsung coba-coba dan belajar dari pengalaman?',
                keywords: {
                    theory: ['teori', 'baca', 'belajar dulu', 'pahami', 'konsep', 'buku'],
                    practice: ['praktik', 'langsung', 'coba', 'praktek', 'hands-on', 'pengalaman'],
                },
            },
        ],

        // ========== AMBIGUITY RESPONSES ==========
        ambiguityPhrases: [
            'nggak tau', 'ga tau', 'tidak tahu', 'bingu', 'gatau', 'entah',
            'terserah', 'apa aja', 'bebas', 'semua suka', 'bingung',
            'kurang paham', 'ga ngerti', 'tidak mengerti',
        ],

        knowledgeBase: [
            {
                keywords: ['coding', 'teknologi', 'komputer', 'pemrograman', 'tech', 'software', 'informatika', 'data'],
                text: 'Berdasarkan minatmu di bidang teknologi, berikut jurusan yang paling cocok untukmu:',
                results: [
                    { major: 'Teknik Informatika', pct: 94 },
                    { major: 'Sistem Informasi', pct: 88},
                    { major: 'Data Science', pct: 85},
                ],
            },
            {
                keywords: ['bisnis', 'keuangan', 'ekonomi', 'manajemen', 'marketing', 'wirausaha', 'entrepreneur'],
                text: 'Minatmu di bidang bisnis sangat bagus! Ini rekomendasi jurusan untukmu:',
                results: [
                    { major: 'Manajemen Bisnis', pct: 92},
                    { major: 'Bisnis Digital', pct: 87},
                    { major: 'Akuntansi', pct: 80},
                ],
            },
            {
                keywords: ['desain', 'seni', 'kreatif', 'gambar', 'visual', 'estetika', 'fotografi', 'ui', 'ux'],
                text: 'Jiwa kreatifmu cocok dengan jurusan-jurusan berikut:',
                results: [
                    { major: 'Desain Komunikasi Visual', pct: 93},
                    { major: 'Desain Interior', pct: 85},
                    { major: 'UI/UX Design', pct: 88,}
                ],
            },
            {
                keywords: ['kesehatan', 'dokter', 'medis', 'farmasi', 'biologi', 'sains', 'kedokteran', 'perawat'],
                text: 'Passion di bidang kesehatan membuka banyak peluang. Ini rekomendasinya:',
                results: [
                    { major: 'Kedokteran', pct: 90},
                    { major: 'Farmasi', pct: 85},
                    { major: 'Ilmu Keperawatan', pct: 80}
                ],
            },
        ],

        init() {
            const raw = sessionStorage.getItem('quiz_questions')
            const quizData = raw ? JSON.parse(raw) : null

            if (quizData) {
                const summary = quizData
                    .filter(q => q.answer)
                    .map(q => `• ${q.question}: **${q.answer}**`)
                    .join('\n')

                this.pushBot(
                    `Halo! Aku JurusIn AI. Aku sudah membaca jawaban kuesionermu. Berdasarkan pilihanmu, sepertinya kamu punya profil yang menarik! Ceritakan lebih lanjut tentang minat atau aktivitas yang paling kamu suka.`,
                    null,
                    false
                )
            } else {
                // Mulai dengan pertanyaan pertama
                this.pushBot(
                    'Halo! Aku JurusIn AI. Aku akan bantu kamu menemukan jurusan yang paling cocok. Yuk, kita mulai! ' + this.questions[0].text,
                    null,
                    false
                )
                this.currentQuestion = 0
            }
        },

        // ========== AUTO RESIZE ==========
        autoResize(el) {
            el.style.height = 'auto';
            el.style.height = Math.min(el.scrollHeight, 120) + 'px';
        },

        now() {
            const d = new Date()
            return d.getHours().toString().padStart(2, '0') + ':' + d.getMinutes().toString().padStart(2, '0')
        },

        pushBot(text, results = null, withTyping = true) {
            if (withTyping) {
                this.typing = true
                const delay = 700 + Math.random() * 400
                setTimeout(() => {
                    this.typing = false
                    this.messages.push({ sender: 'bot', text, results, time: this.now() })
                    this.$nextTick(() => this.scrollToBottom())
                    this.turnCount++

                    // Cek apakah sudah waktunya lock
                    if (this.turnCount >= this.maxTurns) {
                        setTimeout(() => this.lockConversation(), 1500)
                    }
                }, delay)
            } else {
                this.messages.push({ sender: 'bot', text, results, time: this.now() })
                this.$nextTick(() => this.scrollToBottom())
            }
        },

        send() {
            const text = this.input.trim()
            if (!text || this.typing || this.locked) return

            this.messages.push({ sender: 'user', text, results: null, time: this.now() })
            this.input = ''
            this.showSuggestions = false

            // Reset textarea
            const textarea = this.$el.querySelector('textarea');
            if (textarea) {
                textarea.style.height = 'auto';
            }

            this.$nextTick(() => {
                this.scrollToBottom();
            });

            this.processInput(text)
        },

        sendChip(chip) {
            this.input = chip
            this.send()
        },

        // ========== PROCESS INPUT DENGAN AMBIGUITY HANDLING ==========
        processInput(text) {
            const lower = text.toLowerCase()

            // 1. Cek apakah jawaban ambigu
            if (this.isAmbiguous(lower)) {
                this.handleAmbiguity()
                return
            }

            // 2. Cek apakah user mention interest langsung
            for (const entry of this.knowledgeBase) {
                if (entry.keywords.some(kw => lower.includes(kw))) {
                    // Dapet interest! Simpan & kasih hasil
                    this.collectedInfo.interest = entry.text

                    // Kalau udah cukup info (minimal 2-3 data), langsung kasih hasil
                    if (this.turnCount >= 2) {
                        this.pushBot(entry.text, entry.results)
                        setTimeout(() => {
                            this.lockConversation()
                        }, 2500)
                    } else {
                        // Kasih hasil + lanjut tanya
                        this.pushBot(entry.text, entry.results)
                        setTimeout(() => {
                            if (this.turnCount < this.maxTurns && this.collectedInfo.workStyle === null) {
                                this.askNextQuestion()
                            }
                        }, 2500)
                    }
                    return
                }
            }

            // 3. Kalau nggak matching, tanya pertanyaan berikutnya
            this.askNextQuestion()
        },

        // ========== CEK AMBIGUITAS ==========
        isAmbiguous(text) {
            return this.ambiguityPhrases.some(phrase => text.includes(phrase))
        },

        handleAmbiguity() {
            const followUpOptions = [
                'Gapapa kalau masih bingung! Coba bayangkan: apakah kamu lebih suka kegiatan seperti menggambar dan mendesain (kreatif), atau seperti coding dan ngoprek komputer (teknis)?',
                'Tenang, banyak orang juga masih bingung kok. Coba ceritakan mata pelajaran apa yang paling kamu enjoy di sekolah?',
                'Biar aku bantu lebih spesifik ya. Kamu lebih tertarik sama hal-hal yang berbau seni & desain, atau yang logis seperti matematika & programming?',
                'Kalau lagi santai, kamu lebih suka ngapain? Scroll medsos (visual), main game (teknis), atau olahraga (fisik)?',
            ]

            const randomFollowUp = followUpOptions[Math.floor(Math.random() * followUpOptions.length)]
            this.pushBot(randomFollowUp)
        },

        // ========== TANYA PERTANYAAN BERIKUTNYA ==========
        askNextQuestion() {
            if (this.currentQuestion < this.questions.length - 1) {
                this.currentQuestion++
                const nextQ = this.questions[this.currentQuestion]
                this.pushBot(nextQ.text)
            } else {
                // Udah tanya semua, kasih hasil
                this.giveFinalRecommendation()
            }
        },

        // ========== REKOMENDASI FINAL ==========
        giveFinalRecommendation() {
            // Cari match terbaik dari knowledgeBase
            // Buat dummy, ambil yang pertama aja
            const recommendation = this.knowledgeBase[0]
            this.pushBot(
                'Dari obrolan kita, aku udah cukup kenal sama kamu! Ini rekomendasi yang paling cocok:',
                recommendation.results
            )
            setTimeout(() => this.lockConversation(), 2000)
        },

        // ========== LOCK & TAMPILKAN BUTTON ==========
        lockConversation() {
            this.locked = true
            this.showSuggestions = false
            this.pushBot(
                '🎉 Aku sudah punya cukup gambaran tentang profilmu! Klik tombol di bawah untuk melihat hasil rekomendasi lengkap & detail jurusan yang paling cocok untukmu.',
                null,
                false
            )
            this.$nextTick(() => this.scrollToBottom())
        },

        scrollToBottom() {
            const area = this.$refs.messageArea
            if (area) area.scrollTop = area.scrollHeight
        },
    }
}