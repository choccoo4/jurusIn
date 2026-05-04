// resources/js/components/navbar.js

export function navbar() {
    return {
        scrolled: false,

        onScroll() {
            this.scrolled = window.scrollY > 20
        },
    }
}
