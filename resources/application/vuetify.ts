import DateFnsAdapter from '@date-io/date-fns'
import { enUS as enDateFns } from 'date-fns/locale/en-US'
import { nl as nlDateFns } from 'date-fns/locale/nl'
import { useI18n } from 'vue-i18n'
import { createVuetify } from 'vuetify'
import * as components from 'vuetify/components'
import * as directives from 'vuetify/directives'
import { aliases, mdi } from 'vuetify/iconsets/mdi'
import { VDateInput } from 'vuetify/labs/VDateInput'
import { VTimePicker } from 'vuetify/labs/VTimePicker'
import { createVueI18nAdapter } from 'vuetify/locale/adapters/vue-i18n'

import i18n from './i18n'

// CSS
import 'vuetify/styles'
import '@mdi/font/css/materialdesignicons.css'

// Shared input defaults
const input = { color: 'primary', hideDetails: 'auto' }

const vuetify = createVuetify({
    components: { ...components, VDateInput, VTimePicker },
    date: {
        adapter: DateFnsAdapter,
        // Define date-fns language packs for all supported locales here
        locale: {
            en: enDateFns,
            nl: nlDateFns,
        },
    },
    // Component defaults
    defaults: {
        VAlert: { ...input, type: 'info' },
        VAutocomplete: { ...input },
        VBtnToggle: { ...input },
        VCheckbox: { ...input },
        VCombobox: { ...input },
        VDateInput: { ...input },
        VDatePicker: { ...input, showAdjacentMonths: true },
        VField: { ...input },
        VFileInput: { ...input },
        VOtpInput: { ...input },
        VProgressLinear: { ...input },
        VSelect: { ...input },
        VSelectionControl: { ...input },
        VSlider: { ...input },
        VSwitch: { ...input },
        VTabs: { ...input, showArrows: true },
        VTextarea: { ...input, rows: 4 },
        VTextField: { ...input },
    },

    directives,
    icons: { aliases, defaultSet: 'mdi', sets: { mdi } },
    locale: { adapter: createVueI18nAdapter({ i18n, useI18n }) },

    // Theme colors
    theme: {
        defaultTheme: 'light',
        themes: {
            dark: {
                colors: {
                    error: '#E5321B',
                    info: '#0E9594',
                    primary: '#D30240',
                    secondary: '#003C50',
                    warning: '#F29100',
                },
                dark: true,
            },
            light: {
                colors: {
                    error: '#E5321B',
                    info: '#0E9594',
                    primary: '#D30240',
                    secondary: '#003C50',
                    warning: '#F29100',
                },
                dark: false,
            },
        },
    },
})

export default vuetify
