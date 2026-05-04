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

Alpine.data('navbar',        navbar)
Alpine.data('heroCta',       heroCta)
Alpine.data('resultPreview', resultPreview)
Alpine.data('faqAccordion',  faqAccordion)
Alpine.data('chatbot',       chatbot)

// Start Alpine
Alpine.start()
