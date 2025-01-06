import { createI18n, type I18nOptions } from 'vue-i18n'
import * as vuetifyMessages from 'vuetify/locale'
import messages from '#/locales.json'

export type SupportedLocale = keyof typeof messages
export type MessageSchema = (typeof messages)['en'] & {
    $vuetify: typeof vuetifyMessages.en
}

const options: I18nOptions = {
    legacy: false,
    locale: 'en',
    messages: {
        // Combine messages from the `lang` directory with Vuetify messages
        ...(Object.fromEntries(
            (Object.keys(messages) as SupportedLocale[]).map(
                (locale): [SupportedLocale, MessageSchema] => [
                    locale,
                    { ...messages[locale], $vuetify: vuetifyMessages[locale] },
                ],
            ),
        ) as Record<SupportedLocale, MessageSchema>),

        // Overwrite some missing Vuetify translations
        nl: {
            ...messages.nl,
            $vuetify: {
                ...vuetifyMessages.nl,
                dismiss: 'Sluiten',
                timePicker: {
                    ...vuetifyMessages.nl.timePicker,
                    title: 'Selecteer tijd',
                },
                fileUpload: {
                    ...vuetifyMessages.nl.fileUpload,
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
