import { initializeHybridly } from 'virtual:hybridly/config'
import { createHead } from '@unhead/vue'
import i18n from './i18n'
import vuetify from './vuetify'
import pinia from './pinia'
import './axios'
import '@/assets/css/main.scss'

const name = import.meta.env.VITE_APP_NAME

initializeHybridly({
    enhanceVue: (vue) => {
        const head = createHead()
        head.push({
            titleTemplate: (title) =>
                title ? `${title} — ${name}` : `${name}`,
        })
        vue.use(i18n)
        vue.use(head)
        vue.use(vuetify)
        vue.use(pinia)
    },
})
