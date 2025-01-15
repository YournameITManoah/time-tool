import type DateFnsAdapter from '@date-io/date-fns'

export interface VDataTableOptions {
    groupBy: never[]
    itemsPerPage: number
    page: number
    search: string | undefined
    sortBy: { key: string; order: 'asc' | 'desc' }[]
}

export interface VFormRef {
    errors: VFormRefError[]
    isDisabled: boolean
    isReadonly: boolean
    isValid: boolean
    isValidating: boolean
    items: {
        errorMessages: string[]
        id: number | string
        isValid: boolean | null
        reset: () => Promise<void>
        resetValidation: () => Promise<void>
        validate: () => Promise<string[]>
        vm: Raw<ComponentInternalInstance>
    }[]
    reset: () => void
    resetValidation: () => void
    validate: () => Promise<{
        errors: VFormRefError[]
        valid: boolean
    }>
}

interface VFormRefError {
    errorMessages: string[]
    id: number | string
}

declare module 'vuetify' {
    namespace DateModule {
        // eslint-disable-next-line @typescript-eslint/no-empty-object-type
        interface Adapter extends DateFnsAdapter {}
    }
}
