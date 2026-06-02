// resources/js/components/questionnaire.js

export function questionnaire(questions) {
    return {
        questions,
        currentIndex: 0,
        answers: {}, // { 0: 4, 1: 5, 2: 3, ... }
        done: false,

        // Skala Likert
        scale: [
            { value: 1 },
            { value: 2 },
            { value: 3 },
            { value: 4 },
            { value: 5 },
        ],

        get currentQuestion() {
            return this.questions[this.currentIndex];
        },

        get progress() {
            return ((this.currentIndex + 1) / this.questions.length) * 100;
        },

        get isLast() {
            return this.currentIndex === this.questions.length - 1;
        },

        get isFirst() {
            return this.currentIndex === 0;
        },

        // Cek apakah jawaban sudah dipilih
        get hasAnswer() {
            return this.answers[this.currentIndex] !== undefined;
        },

        // Pilih skala
        selectOption(value) {
            this.answers[this.currentIndex] = value;
        },

        // Dapatkan label dari value
        getLabel(value) {
            const scale = this.scale.find((s) => s.value === value);
            return scale ? scale.label : "";
        },

        nextStep() {
            if (!this.hasAnswer) return;
            if (!this.isLast) this.currentIndex++;
        },

        prevStep() {
            if (!this.isFirst) this.currentIndex--;
        },

        submit() {
            if (!this.hasAnswer) return;

            const formattedAnswers = this.questions.map((q, i) => ({
                question_id: q.id,
                question: q.question_text,
                category: q.riasec_category,
                answer: this.answers[i] ?? null,
                label: this.answers[i] ? this.getLabel(this.answers[i]) : null,
                value: this.answers[i] ?? null,
            }));

            // Generate session ID
            const sessionId =
                "ses-" +
                Date.now() +
                "-" +
                Math.random().toString(36).substr(2, 9);

            // Simpan ke sessionStorage
            sessionStorage.setItem("session_id", sessionId);
            sessionStorage.setItem(
                "quiz_answers",
                JSON.stringify(this.answers),
            );
            sessionStorage.setItem(
                "quiz_questions",
                JSON.stringify(formattedAnswers),
            );

            // Kirim ke backend
            this.saveToDatabase(sessionId, formattedAnswers);

            this.done = true;
        },

        async saveToDatabase(sessionId, answers) {
            try {
                const response = await fetch("/questionnaire/save", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": document.querySelector(
                            'meta[name="csrf-token"]',
                        ).content,
                    },
                    body: JSON.stringify({
                        session_id: sessionId,
                        questionnaire_id: 1,
                        answers: answers,
                    }),
                });

                const data = await response.json();
                console.log("Saved to DB:", data);

                if (data.profile_text) {
                    sessionStorage.setItem(
                        "riasec_profile_text",
                        data.profile_text,
                    );
                }
            } catch (error) {
                console.error("Failed to save:", error);
            }
        },
    };
}
