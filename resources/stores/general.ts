import type { Theme } from '@/types'
import type { RemovableRef } from '@vueuse/core'

import { defineStore } from 'pinia'

interface State {
    theme: RemovableRef<Theme>
}

export const useGeneralStore = defineStore('general', {
    state: (): State => ({
        theme: useLocalStorage<Theme>('theme', 'system'),
    }),
})
