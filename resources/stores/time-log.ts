import type { ConnectionExtended, PagedResult } from '@/types'

import { defineStore } from 'pinia'

interface State {
    connections: ConnectionExtended[]
}

export const useTimeLogStore = defineStore('time-log', {
    actions: {
        /**
         * Fetch all connections trough API
         * @param refresh Whether to refresh when already fetched
         * @returns The connections
         */
        async getConnections(refresh?: boolean) {
            if (this.connections.length && !refresh) return this.connections
            try {
                const result = await window.axios<
                    PagedResult<ConnectionExtended>
                >('/api/connection', { params: { limit: -1 } })

                this.connections = result.data.data
            } catch (e) {
                console.error(e)
            }
            return this.connections
        },
    },
    state: (): State => ({
        connections: [],
    }),
})
