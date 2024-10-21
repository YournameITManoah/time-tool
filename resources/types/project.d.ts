import type { Resource } from './general'

export interface Project extends Resource {
    id: number
    name: string
    client_id: number
    max_hours: number
}

export interface ProjectWithClient extends Project {
    client: {
        id: number
        name: string
    }
}
