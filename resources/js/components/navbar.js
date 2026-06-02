// resources/js/components/navbar.js

export function navbar() {
    return {
        scrolled: false,
        open: false, 

        onScroll() {
            this.scrolled = window.scrollY > 20
        },
    }
}
