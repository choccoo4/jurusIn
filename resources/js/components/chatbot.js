export function chatbot() {
    return {
        input: "",
        messages: [],
        typing: false,
        showSuggestions: true,
        locked: false,
        _locking: false,
        _sending: false,
        currentQuestion: 0,

        get currentSuggestions() {
            const suggestionMap = {
                0: [
                    // Q1: Aktivitas yang bikin lupa waktu
                    "Membuat atau merakit sesuatu",
                    "Menganalisis masalah & cari solusi",
                    "Mendesain atau berkreasi visual",
                    "Diskusi & bantu orang lain",
                    "Memimpin atau mengatur tim",
                    "Mengatur & merapikan sistem",
                ],
                1: [
                    // Q2: Cara menghadapi masalah
                    "Mencoba langsung & belajar dari pengalaman",
                    "Menganalisis dulu sebelum bertindak",
                    "Berdiskusi dengan teman atau mentor",
                    "Kombinasi analisis & diskusi",
                ],
                2: [
                    // Q3: Lingkungan nyaman
                    "Tenang & terstruktur dengan jadwal jelas",
                    "Fleksibel & bebas bereksplorasi",
                    "Ramai & banyak interaksi sosial",
                    "Fokus sendiri tanpa gangguan",
                ],
                3: [
                    // Q4: Bagian yang dinikmati
                    "Membuat atau menciptakan sesuatu",
                    "Menganalisis & mencari solusi",
                    "Membantu & mendukung orang lain",
                    "Memimpin & mengatur tim",
                    "Mengatur & merapikan sistem",
                ],
                4: [
                    // Q5: Kemampuan diandalkan
                    "Kemampuan teknis & hands-on",
                    "Logika & analisis mendalam",
                    "Kreativitas & inovasi",
                    "Komunikasi & empati",
                    "Kepemimpinan & strategi",
                    "Ketelitian & detail",
                ],
                5: [
                    // Q6: Topik penasaran
                    "Teknologi, AI, dan inovasi digital",
                    "Sains, penelitian, dan eksperimen",
                    "Seni, kreativitas, dan desain",
                    "Bisnis, pemasaran, dan kewirausahaan",
                    "Kesehatan, medis, dan pelayanan",
                    "Isu sosial dan kehidupan masyarakat",
                    "Psikologi dan perilaku manusia",
                    "Hukum, politik, dan hubungan internasional",
                ],
                6: [
                    // Q7: Pekerjaan impian
                    "Menjadi pengusaha dan membangun bisnis",
                    "Menjadi peneliti atau ilmuwan",
                    "Menjadi dokter atau tenaga kesehatan",
                    "Menjadi seniman atau kreator",
                    "Menjadi pengacara atau diplomat",
                    "Menjadi relawan dan membantu masyarakat",
                ],
                7: [
                    // Q8: Aktivitas harian
                    "Membuat atau menciptakan sesuatu",
                    "Menganalisis & memecahkan masalah",
                    "Berdiskusi dan bekerja sama",
                    "Mengajar dan membimbing orang lain",
                    "Memimpin dan menyusun strategi",
                    "Menulis dan mendokumentasikan informasi",
                    "Meneliti atau mencari informasi baru",
                ],
            };

            return suggestionMap[this.currentQuestion] || [];
        },

        // Teks pertanyaan (masih dipakai di init)
        questions: [
            {
                id: 1,
                text: "Aktivitas seperti apa yang membuat kamu merasa sangat menikmati prosesnya sampai lupa waktu?",
            },
            {
                id: 2,
                text: "Saat menghadapi suatu masalah, kamu biasanya lebih suka mencoba langsung, menganalisis dulu, berdiskusi dengan orang lain, atau mencari cara lain? Ceritakan sedikit.",
            },
            {
                id: 3,
                text: "Lingkungan belajar atau kerja seperti apa yang membuat kamu merasa nyaman dan produktif?",
            },
            {
                id: 4,
                text: "Ketika mengerjakan sesuatu, bagian mana yang paling kamu nikmati: membuat, menganalisis, membantu, memimpin, mengatur, atau hal lainnya? Jelaskan alasannya.",
            },
            {
                id: 5,
                text: "Menurutmu, kemampuan atau cara kerja apa yang paling sering kamu andalkan saat menyelesaikan sesuatu?",
            },
            {
                id: 6,
                text: "Topik atau bidang apa yang paling sering membuat kamu penasaran dan ingin mempelajarinya lebih jauh?",
            },
            {
                id: 7,
                text: "Jika bebas memilih pekerjaan di masa depan tanpa memikirkan gaji atau nilai, pekerjaan seperti apa yang ingin kamu lakukan?",
            },
            {
                id: 8,
                text: "Pekerjaan atau aktivitas seperti apa yang paling menarik untuk kamu bayangkan dilakukan setiap hari?",
            },
        ],

        init() {
            const raw = sessionStorage.getItem("quiz_questions");
            const quizData = raw ? JSON.parse(raw) : null;

            fetch("/chatbot/start", {
                method: "GET",
                headers: {
                    "X-CSRF-TOKEN": document.querySelector(
                        'meta[name="csrf-token"]',
                    ).content,
                },
            }).then(() => {
                console.log("Session reset");
            });

            if (quizData) {
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

            this.$watch("currentQuestion", () => {
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
                setTimeout(
                    () => {
                        this.typing = false;
                        this.messages.push({
                            sender: "bot",
                            text,
                            results,
                            time: this.now(),
                        });
                        requestAnimationFrame(() => {
                            this.scrollToBottom();
                        });
                    },
                    700 + Math.random() * 400,
                );
            } else {
                this.messages.push({
                    sender: "bot",
                    text,
                    results,
                    time: this.now(),
                });
                requestAnimationFrame(() => {
                    this.scrollToBottom();
                });
            }
        },

        async send() {
            const text = this.input.trim();
            if (!text || this.typing || this.locked || this._sending) return;

            this._sending = true;
            this.showSuggestions = false;

            const questionId = this.currentQuestion + 1;
            const validation = await this.processAnswer(questionId, text);

            this.messages.push({
                sender: "user",
                text,
                results: null,
                time: this.now(),
            });
            this.input = "";

            const textarea = this.$el.querySelector("textarea");
            if (textarea) textarea.style.height = "auto";

            this.$nextTick(() => this.scrollToBottom());

            if (!validation.valid) {
                // Invalid — tampilkan pesan follow-up, harus tetap di Q yang sama
                setTimeout(
                    () => this.pushBot(validation.message, null, false),
                    400,
                );
                this._sending = false;
                this.showSuggestions = true;
                return;
            }

            // Valid — lanjut
            if (validation.completed) {
                this.showSuggestions = false;
                sessionStorage.setItem(
                    "chat_profile_text",
                    validation.chat_profile_text || "",
                );
                sessionStorage.setItem(
                    "chat_answers",
                    JSON.stringify(validation.answers || []),
                );
                this.showSubjectModal = true;
                this._sending = false;
                return;
            }

            if (validation.next_question) {
                this.currentQuestion = validation.current_question - 1;
                setTimeout(
                    () => this.pushBot(validation.next_question.text),
                    800,
                );
            }

            this._sending = false;
        },

        async sendChip(chip) {
            if (this.typing || this.locked || this._sending) return;

            this._sending = true;
            this.showSuggestions = false;

            const questionId = this.currentQuestion + 1;
            const validation = await this.processAnswer(questionId, chip);

            this.messages.push({
                sender: "user",
                text: chip,
                results: null,
                time: this.now(),
            });
            this.input = "";

            const textarea = this.$el.querySelector("textarea");
            if (textarea) textarea.style.height = "auto";

            this.$nextTick(() => this.scrollToBottom());

            if (!validation.valid) {
                setTimeout(() => {
                    this.pushBot(validation.message, null, false);
                }, 400);
                this._sending = false;
                this.showSuggestions = true;
                return;
            }

            if (validation.completed) {
                sessionStorage.setItem(
                    "chat_profile_text",
                    validation.chat_profile_text || "",
                );
                sessionStorage.setItem(
                    "chat_answers",
                    JSON.stringify(validation.answers || []),
                );
                this.showSubjectModal = true;
                this._sending = false;
                return;
            }

            if (validation.next_question) {
                this.currentQuestion = validation.current_question - 1;
                setTimeout(
                    () => this.pushBot(validation.next_question.text),
                    800,
                );
            }

            this._sending = false;
        },

        async processAnswer(questionId, answer) {
            try {
                const response = await fetch("/chatbot/process", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": document.querySelector(
                            'meta[name="csrf-token"]',
                        ).content,
                    },
                    body: JSON.stringify({ question_id: questionId, answer }),
                });
                return await response.json();
            } catch (error) {
                console.error("Error:", error);
                return {
                    valid: false,
                    message: "Ada gangguan jaringan. Coba lagi ya!",
                };
            }
        },

        async finalizeChat() {
            // Ambil session_id yang benar — disimpan saat questionnaire selesai
            const sessionId = sessionStorage.getItem("session_id") || "";
            const subjects = JSON.parse(
                sessionStorage.getItem("selected_subjects") || "[]",
            );

            try {
                const response = await fetch("/chatbot/finalize", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": document.querySelector(
                            'meta[name="csrf-token"]',
                        ).content,
                    },
                    body: JSON.stringify({ subjects: subjects }),
                });
                const data = await response.json();

                if (data.success) {
                    const inputProfileText = data.chat_summary || "";
                    sessionStorage.setItem(
                        "input_profile_text",
                        inputProfileText,
                    );

                    // Simpan ke DB — gunakan session_id dari sessionStorage
                    const saved = await this.saveToDatabase(
                        inputProfileText,
                        sessionId,
                    );
                    if (saved) {
                        this.lockConversation();
                    }
                } else {
                    console.error("Finalize gagal:", data);
                    this.lockConversation();
                }
            } catch (error) {
                console.error("Finalize error:", error);
                this.lockConversation();
            }
        },

        async saveToDatabase(inputProfileText, sessionId) {
            try {
                const response = await fetch("/chatbot/save-to-db", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": document.querySelector(
                            'meta[name="csrf-token"]',
                        ).content,
                    },
                    body: JSON.stringify({
                        session_id: sessionId,
                        input_profile_text: inputProfileText,
                    }),
                });
                const data = await response.json();
                if (data.success) {
                    console.log("Saved to DB:", data);
                    return true;
                } else {
                    console.error("Save to DB gagal:", data.error);
                    return false;
                }
            } catch (error) {
                console.error("Save to DB error:", error);
                return false;
            }
        },

        lockConversation() {
            if (this._locking) return;
            this._locking = true;
            this.locked = true;
            this.showSuggestions = false;
            this.pushBot(
                "🎉 Aku sudah punya cukup gambaran tentang profilmu! Klik tombol di bawah untuk melihat hasil rekomendasi lengkap & detail jurusan yang paling cocok untukmu.",
                null,
                false,
            );
            this.$nextTick(() => this.scrollToBottom());
        },

        scrollToBottom() {
            const area = document.querySelector('[x-ref="messageArea"]');
            if (area) {
                area.scrollTo({
                    top: area.scrollHeight,
                    behavior: "smooth",
                });
            }
        },

        // ========== SUBJECT MODAL ==========
        showSubjectModal: false,
        subjectInput: "",
        selectedSubjects: [],
        pendingSubject: null,
        pendingScore: null,

        popularSubjects: [
            "Matematika",
            "Bahasa Inggris",
            "Bahasa Indonesia",
            "Fisika",
            "Kimia",
            "Biologi",
            "Ekonomi",
            "Geografi",
            "Sejarah",
            "Sosiologi",
            "Teknologi Informasi",
            "Pemrograman",
            "Basis Data",
            "Jaringan Komputer",
            "Desain Grafis",
            "Akuntansi",
            "Kewirausahaan",
        ],

        subjectNormalizeMap: {
            mtk: "Matematika",
            math: "Matematika",
            bing: "Bahasa Inggris",
            english: "Bahasa Inggris",
            bind: "Bahasa Indonesia",
            fis: "Fisika",
            kim: "Kimia",
            bio: "Biologi",
            eko: "Ekonomi",
            geo: "Geografi",
            sej: "Sejarah",
            sosio: "Sosiologi",
            tik: "Teknologi Informasi",
            asj: "Administrasi Sistem Jaringan",
            tkj: "Teknik Komputer Jaringan",
            rpl: "Rekayasa Perangkat Lunak",
            dkv: "Desain Komunikasi Visual",
            pkn: "Pendidikan Kewarganegaraan",
            sbk: "Seni Budaya",
            pai: "Pendidikan Agama Islam",
        },

        normalizeSubject(input) {
            let cleaned = input.toLowerCase().trim();
            if (this.subjectNormalizeMap[cleaned]) {
                return this.subjectNormalizeMap[cleaned];
            }
            return cleaned
                .split(" ")
                .map((w) => w.charAt(0).toUpperCase() + w.slice(1))
                .join(" ");
        },

        selectSubject(subject) {
            this.pendingSubject = subject;
            this.pendingScore = null;
        },

        confirmSubject() {
            if (!this.pendingSubject || !this.pendingScore) return;
            if (this.pendingScore < 0 || this.pendingScore > 100) return;

            const name = this.normalizeSubject(this.pendingSubject);
            if (
                !this.selectedSubjects.find((s) => s.name === name) &&
                this.selectedSubjects.length < 4
            ) {
                this.selectedSubjects.push({
                    name,
                    score: parseInt(this.pendingScore),
                });
            }
            this.pendingSubject = null;
            this.pendingScore = null;
        },

        addCustomSubject() {
            const input = this.subjectInput.trim();
            if (!input) return;

            // Parse: "Matematika 85" atau "Matematika"
            const parts = input.match(/^(.*?)\s*(\d+)?$/);
            const name = parts[1]?.trim();
            const score = parts[2] ? parseInt(parts[2]) : null;

            if (!name) return;

            if (score) {
                // Ada nilai — langsung tambah
                const normalized = this.normalizeSubject(name);
                if (
                    !this.selectedSubjects.find((s) => s.name === normalized) &&
                    this.selectedSubjects.length < 4
                ) {
                    this.selectedSubjects.push({ name: normalized, score });
                }
                this.subjectInput = "";
            } else {
                // Nggak ada nilai — pilih mapel dulu
                this.pendingSubject = this.normalizeSubject(name);
                this.pendingScore = null;
                this.subjectInput = "";
            }
        },

        submitSubjects() {
            if (this.selectedSubjects.length < 3) return;

            sessionStorage.setItem(
                "selected_subjects",
                JSON.stringify(this.selectedSubjects),
            );
            this.showSubjectModal = false;
            this.finalizeChat();
        },
    };
}
