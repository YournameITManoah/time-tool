import type { Resource } from './general'

export interface TimeLog extends Resource {
    date: string
    description: string
    id: number
    project_id: number
    start_time: string
    stop_time: string
    task_id: number
    user_id: number
}

export interface TimeLogExtended extends TimeLog {
    project: { id: number; name: string }
    task: { id: number; name: string }
}
