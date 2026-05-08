// resources/js/components/resultPage.js

export function resultPage(recommendations, insight) {
    return {
        recommendations,
        insight,

        // Derived trait scores from recommendations
        traits: [
            { label: 'Kemampuan Analitis',  val: 88 },
            { label: 'Pemikiran Logis',     val: 85 },
            { label: 'Kreativitas',         val: 72 },
            { label: 'Orientasi Sosial',    val: 65 },
        ],

        universities: [
            { abbr: 'UI',    name: 'Universitas Indonesia',        location: 'Depok, Jawa Barat' },
            { abbr: 'ITB',   name: 'Institut Teknologi Bandung',   location: 'Bandung, Jawa Barat' },
            { abbr: 'ITS',   name: 'Institut Teknologi Sepuluh',   location: 'Surabaya, Jawa Timur' },
            { abbr: 'UGM',   name: 'Universitas Gadjah Mada',     location: 'Yogyakarta' },
        ],

        get top() {
            return this.recommendations[0]
        },

        get others() {
            return this.recommendations.slice(1)
        },

        init() {
            // If quiz answers exist in sessionStorage, we could use them
            // to personalise traits. Left as extension point.
            const raw = sessionStorage.getItem('quiz_questions')
            if (raw) {
                // Future: call backend with answers to get real scores
                console.log('Quiz answers available for personalisation')
            }
        },
    }
}
