import type { Resource } from './general'

export interface UserTask extends Resource {
    id: number
    project_id: number
    task_id: number
    user_id: number
}

export interface UserTaskExtended extends UserTask {
    project: {
        end_date: null | string
        id: number
        name: string
        start_date: null | string
    }
    task: {
        id: number
        name: string
    }
}
