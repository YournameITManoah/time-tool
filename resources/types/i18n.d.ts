import type { MessageSchema, SupportedLocale } from '@/application/i18n'

declare module 'vue-i18n' {
    // Define the datetime format schema
    export interface DefineDateTimeFormat {
        short: {
            hour: 'numeric'
            minute: 'numeric'
            second: 'numeric'
            timezone: string
            timeZoneName: 'short'
        }
    }

    // Define the locale messages schema
    // eslint-disable-next-line @typescript-eslint/no-empty-object-type
    export interface DefineLocaleMessage extends MessageSchema {}

    // Define the number format schema
    export interface DefineNumberFormat {
        currency: {
            currency: string
            currencyDisplay: 'symbol'
            style: 'currency'
        }
    }

    export type Locale = SupportedLocale
}
