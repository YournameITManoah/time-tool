import type { Resource } from './general'

export interface Project extends Resource {
    id: number
    name: string
    client_id: number
    start_date: Date
    end_date: Date
    available_hours: number
}

export interface ProjectExtended extends Project {
    client: {
        id: number
        name: string
    }
}
