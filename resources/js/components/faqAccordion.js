// resources/js/components/faqAccordion.js

export function faqAccordion() {
    return {
        openIndex: null,

        toggle(i) {
            this.openIndex = this.openIndex === i ? null : i
        },

        isOpen(i) {
            return this.openIndex === i
        },
    }
}
