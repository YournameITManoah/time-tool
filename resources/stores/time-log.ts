import { defineStore } from 'pinia'
import type { PagedResult, UserTaskExtended } from '@/types'

interface State {
    userTasks: UserTaskExtended[]
}

export const useTimeLogStore = defineStore('time-log', {
    state: (): State => ({ userTasks: [] }),
    actions: {
        async getUserTasks(refresh?: boolean) {
            if (this.userTasks.length && !refresh) return this.userTasks
            try {
                const result = await window.axios<
                    PagedResult<UserTaskExtended>
                >('/api/user-task/my', { params: { limit: -1 } })

                this.userTasks = result.data.data
            } catch (e) {
                console.error(e)
            }
            return this.userTasks
        },
    },
})
