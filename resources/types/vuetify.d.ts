import type DateFnsAdapter from '@date-io/date-fns'

interface VFormRefError {
    id: string | number
    errorMessages: string[]
}

export interface VFormRef {
    errors: VFormRefError[]
    isDisabled: boolean
    isReadonly: boolean
    isValid: boolean
    isValidating: boolean
    items: {
        id: string | number
        validate: () => Promise<string[]>
        reset: () => Promise<void>
        resetValidation: () => Promise<void>
        vm: Raw<ComponentInternalInstance>
        isValid: boolean | null
        errorMessages: string[]
    }[]
    reset: () => void
    resetValidation: () => void
    validate: () => Promise<{
        valid: boolean
        errors: VFormRefError[]
    }>
}

export interface VDataTableOptions {
    groupBy: never[]
    itemsPerPage: number
    page: number
    search: string | undefined
    sortBy: { key: string; order: 'asc' | 'desc' }[]
}

declare module 'vuetify' {
    namespace DateModule {
        // eslint-disable-next-line @typescript-eslint/no-empty-object-type
        interface Adapter extends DateFnsAdapter {}
    }
}
