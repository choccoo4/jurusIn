// resources/js/components/questionnaire.js

export function questionnaire(questions) {
    return {
        questions,
        currentIndex: 0,
        answers: {},
        done: false,

        get currentQuestion() {
            return this.questions[this.currentIndex]
        },

        get progress() {
            return ((this.currentIndex + 1) / this.questions.length) * 100
        },

        get isLast() {
            return this.currentIndex === this.questions.length - 1
        },

        selectOption(option) {
            this.answers[this.currentIndex] = option
        },

        nextStep() {
            if (!this.answers[this.currentIndex]) return
            if (!this.isLast) this.currentIndex++
        },

        prevStep() {
            if (this.currentIndex > 0) this.currentIndex--
        },

        submit() {
            if (!this.answers[this.currentIndex]) return

            // Store answers in sessionStorage so chatbot can read them
            sessionStorage.setItem('quiz_answers', JSON.stringify(this.answers))
            sessionStorage.setItem('quiz_questions', JSON.stringify(
                this.questions.map((q, i) => ({
                    question: q.question,
                    answer: this.answers[i] ?? null,
                }))
            ))

            this.done = true
        },
    }
}
