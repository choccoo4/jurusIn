// resources/js/app.js
// Vite entry point — import Alpine + all modular components

import Alpine from 'alpinejs'
import collapse from '@alpinejs/collapse'
import intersect from '@alpinejs/intersect'

// Register Alpine plugins
Alpine.plugin(collapse)
Alpine.plugin(intersect)

// Register modular Alpine components
import { navbar }        from './components/navbar.js'
import { heroCta }       from './components/heroCta.js'
import { resultPreview } from './components/resultPreview.js'
import { faqAccordion }  from './components/faqAccordion.js'
import { chatbot }       from './components/chatbot.js'
import { questionnaire } from './components/questionnaire.js'

Alpine.data('navbar',        navbar)
Alpine.data('heroCta',       heroCta)
Alpine.data('resultPreview', resultPreview)
Alpine.data('faqAccordion',  faqAccordion)
Alpine.data('chatbot',       chatbot)
Alpine.data('questionnaire', () => questionnaire([
    { question: 'Apa aktivitas yang paling kamu nikmati?', options: ['Coding', 'Bisnis', 'Desain', 'Kesehatan'] },
    { question: 'Apa nilai yang paling kamu hargai dalam bekerja?', options: ['Inovasi', 'Stabilitas', 'Kreativitas', 'Kontribusi Sosial'] },
    { question: 'Bagaimana gaya belajar yang paling efektif untukmu?', options: ['Praktis', 'Teoritis', 'Visual', 'Kolaboratif'] },
    { question: 'Apa tujuan karier jangka panjangmu?', options: ['Membangun Startup', 'Bekerja di Perusahaan Besar', 'Menjadi Freelancer', 'Kontribusi di Sektor Publik'] },
])) 

// Start Alpine
Alpine.start()
