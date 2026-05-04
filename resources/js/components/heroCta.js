// resources/js/components/heroCta.js

export function heroCta() {
    return {
        loading: false,

        start() {
            this.loading = true
            // Redirect after short delay — replace with router push if using Inertia/SPA
            setTimeout(() => {
                window.location.href = '/mulai'
            }, 800)
        },

        scrollTo(id) {
            document.getElementById(id)?.scrollIntoView({ behavior: 'smooth' })
        },
    }
}
