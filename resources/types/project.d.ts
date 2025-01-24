import type { Resource } from './general'

export interface Project extends Resource {
    available_hours: null | number
    client_id: number
    end_date: Date | null
    id: number
    name: string
    start_date: Date | null
}

export interface ProjectExtended extends Project {
    client: { id: number; name: string }
}
