import { createHead } from '@unhead/vue'
import { initializeHybridly } from 'virtual:hybridly/config'

import i18n from './i18n'
import pinia from './pinia'
import vuetify from './vuetify'
import './axios'

import '@/assets/css/main.scss'

// Product name
const name = import.meta.env.VITE_APP_NAME

initializeHybridly({
    enhanceVue: (vue) => {
        // Global head properties
        const head = createHead()
        head.push({
            titleTemplate: (title) =>
                title ? `${title} — ${name}` : `${name}`,
        })
        // Vue plugins
        vue.use(i18n)
        vue.use(head)
        vue.use(vuetify)
        vue.use(pinia)
    },
})
