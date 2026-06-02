// resources/js/components/chatbot.js

export function chatbot() {
    return {
        input: '',
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

        // Teks pertanyaan (masih dipakai di init)
        questions: [
            { id: 1, text: 'Aktivitas seperti apa yang membuat kamu merasa sangat menikmati prosesnya sampai lupa waktu?' },
            { id: 2, text: 'Saat menghadapi suatu masalah, kamu biasanya lebih suka mencoba langsung, menganalisis dulu, berdiskusi dengan orang lain, atau mencari cara lain? Ceritakan sedikit.' },
            { id: 3, text: 'Lingkungan belajar atau kerja seperti apa yang membuat kamu merasa nyaman dan produktif?' },
            { id: 4, text: 'Ketika mengerjakan sesuatu, bagian mana yang paling kamu nikmati: membuat, menganalisis, membantu, memimpin, mengatur, atau hal lainnya? Jelaskan alasannya.' },
            { id: 5, text: 'Menurutmu, kemampuan atau cara kerja apa yang paling sering kamu andalkan saat menyelesaikan sesuatu?' },
        ],

        init() {
            const raw = sessionStorage.getItem('quiz_questions')
            const quizData = raw ? JSON.parse(raw) : null

            fetch('/chatbot/start', {
                method: 'GET',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                }
            }).then(() => {
                console.log('Session reset')
            })
            
            if (quizData) {
                this.pushBot(
                    `Halo! Terima kasih sudah mengisi kuesioner. Aku sudah menganalisis jawabanmu dan punya gambaran awal tentang preferensimu. Sekarang aku mau kenal lebih dekat ya.`,
                    null, false
                )
                setTimeout(() => {
                    if (!this.locked) {
                        this.currentQuestion = 0
                        this.pushBot(this.questions[0].text)
                    }
                }, 1000)
            } else {
                this.pushBot(
                    'Halo! Aku JurusIn AI — asisten pencari jurusan. Aku akan menanyakan beberapa hal untuk menemukan jurusan yang paling cocok untukmu.',
                    null, false
                )
                setTimeout(() => {
                    if (!this.locked) {
                        this.currentQuestion = 0
                        this.pushBot(this.questions[0].text)
                    }
                }, 1000)
            }

            this.$watch('currentQuestion', () => {
                this.showSuggestions = true;
            });
        },

        autoResize(el) {
            el.style.height = 'auto'
            el.style.height = Math.min(el.scrollHeight, 120) + 'px'
        },

        now() {
            const d = new Date()
            return d.getHours().toString().padStart(2, '0') + ':' + d.getMinutes().toString().padStart(2, '0')
        },

        pushBot(text, results = null, withTyping = true) {
            if (this.locked) return
            if (withTyping) {
                this.typing = true
                setTimeout(() => {
                    this.typing = false
                    this.messages.push({ sender: 'bot', text, results, time: this.now() })
                    this.$nextTick(() => this.scrollToBottom())
                }, 700 + Math.random() * 400)
            } else {
                this.messages.push({ sender: 'bot', text, results, time: this.now() })
                this.$nextTick(() => this.scrollToBottom())
            }
        },

        async send() {
            const text = this.input.trim()
            if (!text || this.typing || this.locked) return

            const questionId = this.currentQuestion + 1
            const validation = await this.processAnswer(questionId, text)

            this.messages.push({ sender: 'user', text, results: null, time: this.now() })
            this.input = ''

            const textarea = this.$el.querySelector('textarea')
            if (textarea) textarea.style.height = 'auto'

            this.$nextTick(() => this.scrollToBottom())

            if (!validation.valid) {
                // Invalid — tampilkan pesan follow-up, harus tetap di Q yang sama
                setTimeout(() => this.pushBot(validation.message, null, false), 400)
                return
            }

            // Valid — lanjut
            if (validation.completed) {
                this.showSuggestions = false
                sessionStorage.setItem('chat_profile_text', validation.chat_profile_text || '')
                sessionStorage.setItem('chat_answers', JSON.stringify(validation.answers || []))
                await this.finalizeChat()
                return
            }

            if (validation.next_question) {
                this.currentQuestion = validation.current_question - 1
                setTimeout(() => this.pushBot(validation.next_question.text), 800)
            }
        },

        async sendChip(chip) {
            if (this.typing || this.locked) return

            const questionId = this.currentQuestion + 1
            const validation = await this.processAnswer(questionId, chip)

            this.messages.push({ sender: 'user', text: chip, results: null, time: this.now() })
            this.input = ''

            const textarea = this.$el.querySelector('textarea')
            if (textarea) textarea.style.height = 'auto'

            this.$nextTick(() => this.scrollToBottom())

            if (!validation.valid) {
                setTimeout(() => {
                    this.pushBot(validation.message, null, false)
                }, 400)
                return
            }

            if (validation.completed) {
                sessionStorage.setItem('chat_profile_text', validation.chat_profile_text || '')
                sessionStorage.setItem('chat_answers', JSON.stringify(validation.answers || []))
                await this.finalizeChat()
                return
            }

            if (validation.next_question) {
                this.currentQuestion = validation.current_question - 1
                setTimeout(() => this.pushBot(validation.next_question.text), 800)
            }
        },

        async processAnswer(questionId, answer) {
            try {
                const response = await fetch('/chatbot/process', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    },
                    body: JSON.stringify({ question_id: questionId, answer }),
                })
                return await response.json()
            } catch (error) {
                console.error('Error:', error)
                return { valid: false, message: 'Ada gangguan jaringan. Coba lagi ya!' }
            }
        },

        async finalizeChat() {
            const profileText = sessionStorage.getItem('profile_text') || ''
            const sessionId = sessionStorage.getItem('session_id') || ''

            try {
                const response = await fetch('/chatbot/finalize', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    },
                    body: JSON.stringify({ profile_text: profileText }),
                })
                const data = await response.json()

                if (data.success) {
                    sessionStorage.setItem('input_profile_text', data.input_profile_text)
                    console.log('✅ Final:', data.input_profile_text)

                    //SIMPAN KE DATABSE
                    await this.saveToDatabase(data.input_profile_text, sessionId)
                }
            } catch (error) {
                console.error('Finalize error:', error)
            }
            this.lockConversation()
        },

        async saveToDatabase(inputProfileText, sessionId) {
            try {
                const response = await fetch('/chatbot/save-to-db', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    },
                    body: JSON.stringify({
                        session_id: sessionId,
                        input_profile_text: inputProfileText,
                    }),
                })
                const data = await response.json()
                console.log('✅ Saved to DB:', data)
            } catch (error) {
                console.error('Save to DB error:', error)
            }
        },

        lockConversation() {
            if (this._locking) return
            this._locking = true
            this.locked = true
            this.showSuggestions = false
            this.pushBot(
                '🎉 Aku sudah punya cukup gambaran tentang profilmu! Klik tombol di bawah untuk melihat hasil rekomendasi lengkap & detail jurusan yang paling cocok untukmu.',
                null, false
            )
            this.$nextTick(() => this.scrollToBottom())
        },

        scrollToBottom() {
            const area = this.$refs.messageArea
            if (area) area.scrollTop = area.scrollHeight
        },
    }
}