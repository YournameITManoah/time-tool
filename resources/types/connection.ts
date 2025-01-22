import type { Resource } from './general'

export interface Connection extends Resource {
    id: number
    project_id: number
    task_id: number
    user_id: number
}

export interface ConnectionExtended extends Connection {
    project: {
        client: { id: number; name: string }
        client_id: number
        end_date: null | string
        id: number
        name: string
        start_date: null | string
    }
    task: { id: number; name: string }
}
