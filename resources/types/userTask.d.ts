import type { Resource } from './general'

export interface UserTask extends Resource {
    id: number
    user_id: number
    project_id: number
    task_id: number
}

export interface UserTaskExtended extends UserTask {
    project: {
        id: number
        name: string
        start_date: string | null
        end_date: string | null
    }
    task: {
        id: number
        name: string
    }
}
