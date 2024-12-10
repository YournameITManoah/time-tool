import '@mdi/font/css/materialdesignicons.css'
import 'vuetify/styles'
import { createVuetify } from 'vuetify'
import * as components from 'vuetify/components'
import { VDateInput } from 'vuetify/labs/VDateInput'
import { VTimePicker } from 'vuetify/labs/VTimePicker'
import * as directives from 'vuetify/directives'
import { aliases, mdi } from 'vuetify/iconsets/mdi'
import DateFnsAdapter from '@date-io/date-fns'
import { enUS } from 'date-fns/locale/en-US'

const input = { color: 'primary', hideDetails: 'auto' }

const vuetify = createVuetify({
    date: {
        adapter: DateFnsAdapter,
        locale: {
            en: enUS,
        },
    },
    components: {
        ...components,
        VDateInput,
        VTimePicker,
    },
    theme: {
        defaultTheme: 'light',
        themes: {
            light: {
                colors: {
                    primary: '#D30240',
                    secondary: '#003C50',
                    warning: '#F29100',
                    error: '#E5321B',
                    info: '#0E9594',
                },
                dark: false,
            },
            dark: {
                colors: {
                    primary: '#D30240',
                    secondary: '#003C50',
                    warning: '#F29100',
                    error: '#E5321B',
                    info: '#0E9594',
                },
                dark: true,
            },
        },
    },
    directives,
    icons: {
        defaultSet: 'mdi',
        aliases,
        sets: {
            mdi,
        },
    },
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
})

export default vuetify
