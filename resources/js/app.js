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
import { resultPage }   from './components/resultPage.js'

Alpine.data('resultPage',   resultPage)
Alpine.data('navbar',        navbar)
Alpine.data('heroCta',       heroCta)
Alpine.data('resultPreview', resultPreview)
Alpine.data('faqAccordion',  faqAccordion)
Alpine.data('chatbot',       chatbot)
Alpine.data('questionnaire', questionnaire) 

// Start Alpine
Alpine.start()
