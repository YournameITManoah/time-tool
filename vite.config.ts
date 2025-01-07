import { defineConfig } from 'vite'
import hybridly from 'hybridly/vite'
import vuetify from 'vite-plugin-vuetify'

export default defineConfig({
    plugins: [
        hybridly({ vueComponents: { directoryAsNamespace: true } }),
        vuetify({ autoImport: true }),
    ],
})
