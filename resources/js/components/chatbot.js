// resources/js/components/chatbot.js

export function chatbot() {
    return {
        input: "",
        messages: [],
        typing: false,
        showSuggestions: true,
        locked: false,
        _locking: false,
        currentQuestion: 0,

        get currentSuggestions() {
            const suggestionMap = {
                0: [ // Q1: Aktivitas yang bikin lupa waktu
                    'Membuat atau merakit sesuatu',
                    'Menganalisis masalah & cari solusi',
                    'Mendesain atau berkreasi visual',
                    'Diskusi & bantu orang lain',
                    'Memimpin atau mengatur tim',
                    'Mengatur & merapikan sistem',
                ],
                1: [ // Q2: Cara menghadapi masalah
                    'Mencoba langsung & belajar dari pengalaman',
                    'Menganalisis dulu sebelum bertindak',
                    'Berdiskusi dengan teman atau mentor',
                    'Kombinasi analisis & diskusi',
                ],
                2: [ // Q3: Lingkungan nyaman
                    'Tenang & terstruktur dengan jadwal jelas',
                    'Fleksibel & bebas bereksplorasi',
                    'Ramai & banyak interaksi sosial',
                    'Fokus sendiri tanpa gangguan',
                ],
                3: [ // Q4: Bagian yang dinikmati
                    'Membuat atau menciptakan sesuatu',
                    'Menganalisis & mencari solusi',
                    'Membantu & mendukung orang lain',
                    'Memimpin & mengatur tim',
                    'Mengatur & merapikan sistem',
                ],
                4: [ // Q5: Kemampuan diandalkan
                    'Kemampuan teknis & hands-on',
                    'Logika & analisis mendalam',
                    'Kreativitas & inovasi',
                    'Komunikasi & empati',
                    'Kepemimpinan & strategi',
                    'Ketelitian & detail',
                ],
            };

            return suggestionMap[this.currentQuestion] || [];
        },

        // ========== PERTANYAAN BOT ==========
        questions: [
            {
                id: "flowState",
                text: "Aktivitas seperti apa yang membuat kamu merasa sangat menikmati prosesnya sampai lupa waktu?",
                followUp:
                    "Coba ceritakan lebih detail — apakah aktivitas itu lebih ke membuat sesuatu, menganalisis, membantu orang, atau mengatur sesuatu?",
                keywords: {
                    making: [
                        "membuat",
                        "merakit",
                        "bangun",
                        "konstruksi",
                        "perbaiki",
                        "tangan",
                    ],
                    analyzing: [
                        "analisis",
                        "meneliti",
                        "pahami",
                        "logika",
                        "data",
                        "problem",
                        "matematika",
                    ],
                    creating: [
                        "desain",
                        "gambar",
                        "tulis",
                        "musik",
                        "seni",
                        "kreatif",
                        "visual",
                        "edit",
                    ],
                    helping: [
                        "bantu",
                        "ajar",
                        "dengar",
                        "konsultasi",
                        "orang",
                        "komunitas",
                        "sosial",
                    ],
                    leading: [
                        "pimpin",
                        "organisir",
                        "presentasi",
                        "jual",
                        "nego",
                        "bicara",
                        "strategi",
                    ],
                    organizing: [
                        "atur",
                        "jadwal",
                        "arsip",
                        "data",
                        "hitung",
                        "catat",
                        "dokumen",
                        "rapi",
                    ],
                },
            },
            {
                id: "problemStyle",
                text: "Saat menghadapi suatu masalah, kamu biasanya lebih suka mencoba langsung, menganalisis dulu, berdiskusi dengan orang lain, atau mencari cara lain? Ceritakan sedikit.",
                followUp:
                    "Apakah pendekatan itu berubah tergantung jenis masalahnya, atau kamu memang selalu nyaman dengan cara itu?",
                keywords: {
                    doing: [
                        "langsung",
                        "coba",
                        "praktek",
                        "tangan",
                        "aksi",
                        "eksekusi",
                    ],
                    thinking: [
                        "analisis",
                        "pikir",
                        "riset",
                        "data",
                        "logika",
                        "sendiri",
                        "dalam",
                    ],
                    talking: [
                        "diskusi",
                        "tanya",
                        "bicara",
                        "tim",
                        "teman",
                        "pendapat",
                        "kolaborasi",
                    ],
                    adapting: [
                        "gabung",
                        "kombinasi",
                        "fleksibel",
                        "tergantung",
                        "campur",
                        "semua",
                    ],
                },
            },
            {
                id: "environment",
                text: "Lingkungan belajar atau kerja seperti apa yang membuat kamu merasa nyaman dan produktif?",
                followUp:
                    "Apakah kamu lebih suka suasana yang tenang dan teratur, atau justru yang dinamis dan banyak interaksi?",
                keywords: {
                    structured: [
                        "teratur",
                        "rapi",
                        "tenang",
                        "jadwal",
                        "prosedur",
                        "jelas",
                        "stabil",
                    ],
                    flexible: [
                        "bebas",
                        "fleksibel",
                        "kreatif",
                        "dinamis",
                        "santai",
                        "tidak kaku",
                    ],
                    social: [
                        "interaksi",
                        "ramai",
                        "diskusi",
                        "kolaborasi",
                        "orang",
                        "tim",
                    ],
                    independent: [
                        "sendiri",
                        "fokus",
                        "privat",
                        "konsentrasi",
                        "sunyi",
                    ],
                },
            },
            {
                id: "enjoyment",
                text: "Ketika mengerjakan sesuatu, bagian mana yang paling kamu nikmati: membuat, menganalisis, membantu, memimpin, mengatur, atau hal lainnya? Jelaskan alasannya.",
                followUp:
                    'Dari semua itu, mana yang bikin kamu merasa paling "hidup" atau bersemangat?',
                keywords: {
                    making: [
                        "membuat",
                        "cipta",
                        "bangun",
                        "konstruksi",
                        "produk",
                        "nyata",
                        "fisik",
                    ],
                    analyzing: [
                        "analisis",
                        "teliti",
                        "data",
                        "logika",
                        "riset",
                        "solusi",
                        "pikir",
                    ],
                    creating: [
                        "kreatif",
                        "desain",
                        "seni",
                        "estetika",
                        "imajinasi",
                        "ekspresi",
                    ],
                    helping: [
                        "bantu",
                        "ajar",
                        "dengar",
                        "dukung",
                        "komunitas",
                        "peduli",
                        "sosial",
                    ],
                    leading: [
                        "pimpin",
                        "pengaruh",
                        "nego",
                        "strategi",
                        "bicara",
                        "motivasi",
                    ],
                    organizing: [
                        "atur",
                        "sistem",
                        "jadwal",
                        "arsip",
                        "detail",
                        "rapi",
                        "catat",
                    ],
                },
            },
            {
                id: "skillRelied",
                text: "Menurutmu, kemampuan atau cara kerja apa yang paling sering kamu andalkan saat menyelesaikan sesuatu?",
                followUp:
                    "Apakah kemampuan itu kamu dapat dari bakat alami, hasil belajar, atau pengalaman?",
                keywords: {
                    technical: [
                        "teknis",
                        "alat",
                        "tangan",
                        "mesin",
                        "bangun",
                        "perbaiki",
                    ],
                    analytical: [
                        "analisis",
                        "logika",
                        "data",
                        "riset",
                        "solusi",
                        "metode",
                    ],
                    creative: [
                        "kreatif",
                        "ide",
                        "desain",
                        "imajinasi",
                        "inovatif",
                        "unik",
                    ],
                    interpersonal: [
                        "komunikasi",
                        "empati",
                        "negosiasi",
                        "tim",
                        "hubungan",
                        "orang",
                    ],
                    leadership: [
                        "pimpin",
                        "strategi",
                        "keputusan",
                        "arahkan",
                        "motivasi",
                    ],
                    detail: [
                        "teliti",
                        "rapi",
                        "atur",
                        "cek",
                        "verifikasi",
                        "akurat",
                    ],
                },
            },
        ],

        // ========== AMBIGUITY RESPONSES ==========
        ambiguityPhrases: [
            "nggak tau",
            "ga tau",
            "tidak tahu",
            "bingu",
            "gatau",
            "entah",
            "terserah",
            "apa aja",
            "bebas",
            "semua suka",
            "bingung",
            "kurang paham",
            "ga ngerti",
            "tidak mengerti",
        ],

        knowledgeBase: [
            {
                keywords: [
                    "coding",
                    "teknologi",
                    "komputer",
                    "pemrograman",
                    "tech",
                    "software",
                    "informatika",
                    "data",
                ],
                text: "Berdasarkan minatmu di bidang teknologi, berikut jurusan yang paling cocok untukmu:",
                results: [
                    { major: "Teknik Informatika", pct: 94 },
                    { major: "Sistem Informasi", pct: 88 },
                    { major: "Data Science", pct: 85 },
                ],
            },
            {
                keywords: [
                    "bisnis",
                    "keuangan",
                    "ekonomi",
                    "manajemen",
                    "marketing",
                    "wirausaha",
                    "entrepreneur",
                ],
                text: "Minatmu di bidang bisnis sangat bagus! Ini rekomendasi jurusan untukmu:",
                results: [
                    { major: "Manajemen Bisnis", pct: 92 },
                    { major: "Bisnis Digital", pct: 87 },
                    { major: "Akuntansi", pct: 80 },
                ],
            },
            {
                keywords: [
                    "desain",
                    "seni",
                    "kreatif",
                    "gambar",
                    "visual",
                    "estetika",
                    "fotografi",
                    "ui",
                    "ux",
                ],
                text: "Jiwa kreatifmu cocok dengan jurusan-jurusan berikut:",
                results: [
                    { major: "Desain Komunikasi Visual", pct: 93 },
                    { major: "Desain Interior", pct: 85 },
                    { major: "UI/UX Design", pct: 88 },
                ],
            },
            {
                keywords: [
                    "kesehatan",
                    "dokter",
                    "medis",
                    "farmasi",
                    "biologi",
                    "sains",
                    "kedokteran",
                    "perawat",
                ],
                text: "Passion di bidang kesehatan membuka banyak peluang. Ini rekomendasinya:",
                results: [
                    { major: "Kedokteran", pct: 90 },
                    { major: "Farmasi", pct: 85 },
                    { major: "Ilmu Keperawatan", pct: 80 },
                ],
            },
        ],

        init() {
            const raw = sessionStorage.getItem("quiz_questions");
            const quizData = raw ? JSON.parse(raw) : null;

            if (quizData) {
                this.startedFromQuiz = true;
                this.pushBot(
                    `Halo! Terima kasih sudah mengisi kuesioner. Aku sudah menganalisis jawabanmu dan punya gambaran awal tentang preferensimu. Sekarang aku mau kenal lebih dekat ya.`,
                    null,
                    false,
                );
                setTimeout(() => {
                    if (!this.locked) {
                        this.currentQuestion = 0;
                        this.pushBot(this.questions[0].text);
                    }
                }, 1000);
            } else {
                this.startedFromQuiz = false;
                this.pushBot(
                    "Halo! Aku JurusIn AI — asisten pencari jurusan. Aku akan menanyakan beberapa hal untuk menemukan jurusan yang paling cocok untukmu.",
                    null,
                    false,
                );
                setTimeout(() => {
                    if (!this.locked) {
                        this.currentQuestion = 0;
                        this.pushBot(this.questions[0].text);
                    }
                }, 1000);
            }

            this.$watch('currentQuestion', () => {
                this.showSuggestions = true;
            });
        },

        autoResize(el) {
            el.style.height = "auto";
            el.style.height = Math.min(el.scrollHeight, 120) + "px";
        },

        now() {
            const d = new Date();
            return (
                d.getHours().toString().padStart(2, "0") +
                ":" +
                d.getMinutes().toString().padStart(2, "0")
            );
        },

        pushBot(text, results = null, withTyping = true) {
            if (this.locked) return;

            if (withTyping) {
                this.typing = true;
                const delay = 700 + Math.random() * 400;
                setTimeout(() => {
                    this.typing = false;
                    this.messages.push({
                        sender: "bot",
                        text,
                        results,
                        time: this.now(),
                    });
                    this.$nextTick(() => this.scrollToBottom());
                    this.turnCount++;
                    // nyoba hapus pengecekan maxTurns biar nggak lock prematur
                }, delay);
            } else {
                this.messages.push({
                    sender: "bot",
                    text,
                    results,
                    time: this.now(),
                });
                this.$nextTick(() => this.scrollToBottom());
            }
        },

        send() {
            const text = this.input.trim();
            if (!text || this.typing || this.locked) return;

            this.messages.push({
                sender: "user",
                text,
                results: null,
                time: this.now(),
            });
            this.input = "";
            this.showSuggestions = false;

            // Reset textarea
            const textarea = this.$el.querySelector("textarea");
            if (textarea) {
                textarea.style.height = "auto";
            }

            this.$nextTick(() => {
                this.scrollToBottom();
            });

            this.processInput(text);
        },

        sendChip(chip) {
            if (!this.typing && !this.locked) {
                this.messages.push({
                    sender: "user",
                    text: chip,
                    results: null,
                    time: this.now(),
                });
                this.input = "";
                this.showSuggestions = false;

                // Reset textarea
                const textarea = this.$el.querySelector("textarea");
                if (textarea) {
                    textarea.style.height = "auto";
                }

                this.$nextTick(() => {
                    this.scrollToBottom();
                });

                // Process chip text
                this.processInput(chip);
            }
        },

        // ========== PROCESS INPUT DENGAN AMBIGUITY HANDLING ==========
        processInput(text) {
            const lower = text.toLowerCase();

            // 1. Cek ambigu
            if (this.isAmbiguous(lower)) {
                this.handleAmbiguity();
                return;
            }

            // 2. Cek interest dari knowledgeBase
            for (const entry of this.knowledgeBase) {
                if (entry.keywords.some((kw) => lower.includes(kw))) {
                    this.collectedInfo.interest = entry.text;

                    if (this.turnCount < 2) {
                        // Masih awal → simpan interest, lanjut tanya
                        this.pushBot(
                            `Wah, bidang ${this.getInterestLabel(entry.keywords[0])} terdengar menarik! Aku mau kenal lebih jauh dulu ya.`,
                        );
                        setTimeout(() => {
                            if (!this.locked) {
                                this.askNextQuestion();
                            }
                        }, 1500);
                        return;
                    }

                    // Udah cukup interaksi → kasih rekomendasi
                    this.pushBot(entry.text, entry.results);
                    setTimeout(() => {
                        if (!this.locked) {
                            this.askNextQuestion();
                        }
                    }, 2500);
                    return;
                }
            }

            // 3. Nggak match → lanjut pertanyaan berikutnya
            this.askNextQuestion();
        },

        // ========== CEK AMBIGUITAS ==========
        isAmbiguous(text) {
            return this.ambiguityPhrases.some((phrase) =>
                text.includes(phrase),
            );
        },

        handleAmbiguity() {
            const followUpOptions = [
                "Gapapa kalau masih bingung! Coba bayangkan: apakah kamu lebih suka kegiatan seperti menggambar dan mendesain (kreatif), atau seperti coding dan ngoprek komputer (teknis)?",
                "Tenang, banyak orang juga masih bingung kok. Coba ceritakan mata pelajaran apa yang paling kamu enjoy di sekolah?",
                "Biar aku bantu lebih spesifik ya. Kamu lebih tertarik sama hal-hal yang berbau seni & desain, atau yang logis seperti matematika & programming?",
                "Kalau lagi santai, kamu lebih suka ngapain? Scroll medsos (visual), main game (teknis), atau olahraga (fisik)?",
            ];

            const randomFollowUp =
                followUpOptions[
                    Math.floor(Math.random() * followUpOptions.length)
                ];
            this.pushBot(randomFollowUp);
            // Jangan askNextQuestion() dulu, biar user jawab dulu
        },

        // ========== TANYA PERTANYAAN BERIKUTNYA ==========
        askNextQuestion() {
            if (this.currentQuestion < this.questions.length - 1) {
                this.currentQuestion++;
                const nextQ = this.questions[this.currentQuestion];
                this.pushBot(nextQ.text);
            } else {
                // Semua pertanyaan udah ditanyain lanjut berikan final recommendation
                this.giveFinalRecommendation();
            }
        },

        // ========== REKOMENDASI FINAL ==========
        giveFinalRecommendation() {
            let majors = [];

            // 1. Cari jurusan yang match sama interest user
            if (this.collectedInfo.interest) {
                for (const entry of this.knowledgeBase) {
                    if (entry.text === this.collectedInfo.interest) {
                        majors = entry.results.map((r) => r.major);
                        break;
                    }
                }
            }

            // 2. Kalau nggak ada yang match, ambil dari semua knowledgeBase
            if (majors.length === 0) {
                for (const entry of this.knowledgeBase) {
                    for (const r of entry.results) {
                        if (!majors.includes(r.major)) {
                            majors.push(r.major);
                        }
                    }
                }
            }

            // 3. Ambil 3 aja buat teaser, jadi ga langsung spill hasil di chatbot
            const teaserMajors = majors.slice(0, 3);

            this.pushBot(
                `Dari obrolan kita, aku udah cukup kenal sama kamu! Sepertinya jurusan seperti **${teaserMajors.join("**, **")}** cocok buat kamu. Tapi ada analisis lengkapnya lho — lengkap dengan tingkat kecocokan & alasannya!`,
                null,
            );
            setTimeout(() => this.lockConversation(), 2000);
        },

        // ========== LOCK & TAMPILKAN BUTTON ==========
        lockConversation() {
            if (this._locking) return;
            this._locking = true;
            this.locked = true;
            this.showSuggestions = false;

            // SIMPAN DATA CHATBOT KE SESSION STORAGE
            sessionStorage.setItem(
                "chat_messages",
                JSON.stringify(this.messages),
            );
            sessionStorage.setItem(
                "chat_interest",
                this.collectedInfo.interest || "",
            );
            sessionStorage.setItem("chat_turnCount", this.turnCount);

            this.pushBot(
                "🎉 Aku sudah punya cukup gambaran tentang profilmu! Klik tombol di bawah untuk melihat hasil rekomendasi lengkap & detail jurusan yang paling cocok untukmu.",
                null,
                false,
            );
            this.$nextTick(() => this.scrollToBottom());

            this.saveToDB();
        },

        buildChatData() {
            return this.messages
                .filter((m) => m.sender === "user")
                .map((m, i) => `Jawaban ${i + 1}: ${m.text}`)
                .join("\n");
        },

        async saveToDB() {
            const sessionId = sessionStorage.getItem("session_id");
            if (!sessionId) {
                console.warn("session_id tidak ditemukan.");
                return;
            }

            const chatData = this.buildChatData();

            try {
                const response = await fetch("/chatbot/save", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": document.querySelector(
                            'meta[name="csrf-token"]',
                        ).content,
                    },
                    body: JSON.stringify({
                        session_id: sessionId,
                        chat_data: chatData,
                    }),
                });

                const data = await response.json();
                if (data.success) {
                    console.log("Recommendations saved to DB: ", data);
                    sessionStorage.setItem(
                        "recommendation_id",
                        data.data?.recommendation_id ?? "",
                    );
                } else {
                    console.log("Failed saved to DB:", data);
                }
            } catch (error) {
                console.error("Error saved to db");
            }
        },

        scrollToBottom() {
            const area = this.$refs.messageArea;
            if (area) area.scrollTop = area.scrollHeight;
        },

        getInterestLabel(keyword) {
            const labels = {
                coding: "teknologi",
                teknologi: "teknologi",
                bisnis: "bisnis & keuangan",
                keuangan: "bisnis & keuangan",
                desain: "desain & seni",
                seni: "desain & seni",
                kesehatan: "kesehatan & sains",
                dokter: "kesehatan & sains",
            };
            return labels[keyword] || "ini";
        },
    };
}
