import { initializeHybridly } from 'virtual:hybridly/config'
import { createHead } from '@unhead/vue'
import i18n from './i18n'
import vuetify from './vuetify'
import './axios'
import '@/assets/css/main.scss'

initializeHybridly({
    enhanceVue: (vue) => {
        const head = createHead()
        head.push({
            titleTemplate: (title) =>
                title ? `${title} — Hybridly` : 'Hybridly',
        })
        vue.use(i18n)
        vue.use(head)
        vue.use(vuetify)
    },
})
