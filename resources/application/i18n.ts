import messages from '#/locales.json'
import { createI18n, type I18nOptions } from 'vue-i18n'
import * as vuetifyMessages from 'vuetify/locale'

export type MessageSchema = (typeof messages)['en'] & {
    $vuetify: typeof vuetifyMessages.en
}
export type SupportedLocale = keyof typeof messages

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
                fileUpload: {
                    ...vuetifyMessages.nl.fileUpload,
                    browse: 'Blader door bestanden',
                    divider: 'of',
                    title: 'Sleep en zet bestanden hier neer',
                },
                timePicker: {
                    ...vuetifyMessages.nl.timePicker,
                    title: 'Selecteer tijd',
                },
            },
        },
    },
}

const i18n = createI18n<false, typeof options>(options)

export default i18n
