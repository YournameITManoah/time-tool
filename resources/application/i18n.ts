import { createI18n, type I18nOptions } from 'vue-i18n'
import { en as enVuetify, nl as nlVuetify } from 'vuetify/locale'
import messages from '#/locales.json'

export type SupportedLocale = keyof typeof messages
export type MessageSchema = (typeof messages)['en'] & {
    $vuetify: typeof enVuetify
}

const options: I18nOptions = {
    legacy: false,
    locale: 'en',
    messages: {
        en: { ...messages.en, $vuetify: { ...enVuetify } },
        nl: {
            ...messages.nl,
            $vuetify: {
                ...nlVuetify,
                dismiss: 'Sluiten',
                timePicker: {
                    ...nlVuetify.timePicker,
                    title: 'Selecteer tijd',
                },
                fileUpload: {
                    title: 'Sleep en zet bestanden hier neer',
                    divider: 'of',
                    browse: 'Blader door bestanden',
                },
            },
        },
    },
}

const i18n = createI18n<false, typeof options>(options)

export default i18n
