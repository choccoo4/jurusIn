// resources/js/components/chatbot.js

export function chatbot() {
    return {
        input: '',
        messages: [],
        typing: false,
        showSuggestions: true,

        suggestions: [
            'Saya suka coding dan teknologi',
            'Saya tertarik bisnis dan keuangan',
            'Saya suka desain dan seni',
            'Saya suka kesehatan dan sains',
        ],

        knowledgeBase: [
            {
                keywords: ['coding', 'teknologi', 'komputer', 'pemrograman', 'tech', 'software', 'informatika', 'data'],
                text: 'Berdasarkan minatmu di bidang teknologi, berikut jurusan yang paling cocok untukmu:',
                results: [
                    { major: 'Teknik Informatika', pct: 94, unis: 'UI, ITS, BINUS, Telkom' },
                    { major: 'Sistem Informasi', pct: 88, unis: 'UI, UNPAD, BINUS' },
                    { major: 'Data Science', pct: 85, unis: 'ITB, UGM, UI' },
                ],
            },
            {
                keywords: ['bisnis', 'keuangan', 'ekonomi', 'manajemen', 'marketing', 'wirausaha', 'entrepreneur'],
                text: 'Minatmu di bidang bisnis sangat bagus! Ini rekomendasi jurusan untukmu:',
                results: [
                    { major: 'Manajemen Bisnis', pct: 92, unis: 'UI, UGM, Prasetiya Mulya' },
                    { major: 'Bisnis Digital', pct: 87, unis: 'Binus, Telkom, IPMI' },
                    { major: 'Akuntansi', pct: 80, unis: 'UI, UNPAD, Trisakti' },
                ],
            },
            {
                keywords: ['desain', 'seni', 'kreatif', 'gambar', 'visual', 'estetika', 'fotografi', 'ui', 'ux'],
                text: 'Jiwa kreatifmu cocok dengan jurusan-jurusan berikut:',
                results: [
                    { major: 'Desain Komunikasi Visual', pct: 93, unis: 'ITB, Binus, UK Petra' },
                    { major: 'Desain Interior', pct: 85, unis: 'ITB, Trisakti, FSRD' },
                    { major: 'UI/UX Design', pct: 88, unis: 'Binus, Telkom, ITDP' },
                ],
            },
            {
                keywords: ['kesehatan', 'dokter', 'medis', 'farmasi', 'biologi', 'sains', 'kedokteran', 'perawat'],
                text: 'Passion di bidang kesehatan membuka banyak peluang. Ini rekomendasinya:',
                results: [
                    { major: 'Kedokteran', pct: 90, unis: 'UI, UGM, UNPAD' },
                    { major: 'Farmasi', pct: 85, unis: 'ITB, UI, UNAIR' },
                    { major: 'Ilmu Keperawatan', pct: 80, unis: 'UI, UGM, UNPAD' },
                ],
            },
        ],

        followUps: [
            'Ceritakan lebih detail tentang aktivitas yang paling kamu nikmati!',
            'Apakah kamu lebih suka bekerja sendiri atau dalam tim?',
            'Kamu lebih suka belajar dari teori atau langsung praktik?',
            'Ada mata pelajaran favorit di sekolah yang ingin kamu dalami?',
        ],

        init() {
            this.pushBot(
                'Halo! Aku JurusIn AI. Aku akan bantu kamu menemukan jurusan yang paling cocok. Ceritakan minat, hobi, atau hal yang kamu suka lakukan!',
                null,
                false
            )
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
                }, delay)
            } else {
                this.messages.push({ sender: 'bot', text, results, time: this.now() })
                this.$nextTick(() => this.scrollToBottom())
            }
        },

        send() {
            const text = this.input.trim()
            if (!text || this.typing) return

            this.messages.push({ sender: 'user', text, results: null, time: this.now() })
            this.input = ''
            this.showSuggestions = false
            this.$nextTick(() => this.scrollToBottom())

            this.processInput(text)
        },

        sendChip(chip) {
            this.input = chip
            this.send()
        },

        processInput(text) {
            const lower = text.toLowerCase()

            for (const entry of this.knowledgeBase) {
                if (entry.keywords.some(kw => lower.includes(kw))) {
                    this.pushBot(entry.text, entry.results)
                    setTimeout(() => {
                        this.pushBot('Ada bidang lain yang ingin kamu eksplorasi? Atau mau ceritakan lebih tentang dirimu?')
                        this.showSuggestions = true
                    }, 2000)
                    return
                }
            }

            const fallback = this.followUps[Math.floor(Math.random() * this.followUps.length)]
            this.pushBot(fallback)
        },

        scrollToBottom() {
            const area = this.$refs.messageArea
            if (area) area.scrollTop = area.scrollHeight
        },
    }
}