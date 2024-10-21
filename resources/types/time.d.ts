import type { Resource } from './general'

export interface TimeLog extends Resource {
    id: number
    user_id: number
    project_id: number
    date: string
    start_time: string
    stop_time: string
    description: string
}

export interface TimeLogWithProject extends TimeLog {
    project: {
        id: number
        name: string
    }
}
