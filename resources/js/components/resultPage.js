// resources/js/components/resultPage.js

export function resultPage(recommendations, insight) {
    return {
        recommendations,
        insight,

        get top() {
            return this.recommendations[0] || {
                rank: 1,
                major: 'N/A',
                description: '',
                tags: [],
                color: '#4f46e5'
            }
        },

        get others() {
            return this.recommendations.slice(1)
        },

        get traits() {
            if (this.top && this.top.traits) {
                return this.top.traits
            }
            return []
        },

        init() {
            const raw = sessionStorage.getItem('quiz_questions')
            if (raw) {
                console.log('Quiz answers available for personalization')
            }
        },
    }
}