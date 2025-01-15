import type { Resource } from './general'

export interface Project extends Resource {
    available_hours: number
    client_id: number
    end_date: Date
    id: number
    name: string
    start_date: Date
}

export interface ProjectExtended extends Project {
    client: {
        id: number
        name: string
    }
}
