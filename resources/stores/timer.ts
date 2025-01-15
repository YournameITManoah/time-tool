import type { TimeLog } from '@/types'

import { defineStore } from 'pinia'

interface State {
    startTime: Date | null
    timeLog: Partial<TimeLog>
}

export const useTimerStore = defineStore('timer', {
    state: (): State => ({
        startTime: null,
        timeLog: { date: new Date().toLocaleDateString('en-CA') },
    }),
})
