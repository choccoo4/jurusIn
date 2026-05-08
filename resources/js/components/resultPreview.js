// resources/js/components/resultPreview.js

export function resultPreview() {
    return {
        visible: false,

        items: [
            {
                label: 'Teknik Informatika',
                pct: 92,
                color: '#4f46e5',
                bg: '#eef2ff',
            },
            {
                label: 'Sistem Informasi',
                pct: 87,
                color: '#7c3aed',
                bg: '#f5f3ff',
            },
            {
                label: 'Data Science',
                pct: 85,
                color: '#6366f1',
                bg: '#eef2ff',
            },
        ],

        animate() {
            // Delay sedikit supaya DOM sudah settle dulu baru width di-set
            this.$nextTick(() => {
                this.visible = true
            })
        },
    }
}
