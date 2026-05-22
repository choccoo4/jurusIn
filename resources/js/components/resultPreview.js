// resources/js/components/resultPreview.js

export function resultPreview() {
    return {
        visible: false,

        items: [
            {
                rank: 1,
                label: 'Teknik Informatika',
                description: 'Logika, coding, problem solving',
                color: '#4f46e5',
                bg: '#eef2ff',
                tags: ['Logika', 'Coding', 'Problem Solving'],
            },
            {
                rank: 2,
                label: 'Sistem Informasi',
                description: 'Bisnis & teknologi',
                color: '#7c3aed',
                bg: '#f5f3ff',
                tags: ['Bisnis', 'Teknologi', 'Manajemen'],
            },
            {
                rank: 3,
                label: 'Data Science',
                description: 'Data, statistik, AI',
                color: '#6366f1',
                bg: '#eef2ff',
                tags: ['Statistik', 'AI', 'Analisis'],
            },
        ],

        animate() {
            this.$nextTick(() => {
                this.visible = true
            })
        },
    }
}