import type { MessageSchema, SupportedLocale } from '@/application/i18n'

declare module 'vue-i18n' {
    export type Locale = SupportedLocale
    // define the locale messages schema
    // eslint-disable-next-line @typescript-eslint/no-empty-object-type
    export interface DefineLocaleMessage extends MessageSchema {}

    // define the datetime format schema
    export interface DefineDateTimeFormat {
        short: {
            hour: 'numeric'
            minute: 'numeric'
            second: 'numeric'
            timeZoneName: 'short'
            timezone: string
        }
    }

    // define the number format schema
    export interface DefineNumberFormat {
        currency: {
            style: 'currency'
            currencyDisplay: 'symbol'
            currency: string
        }
    }
}
