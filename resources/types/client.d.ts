import type { Resource } from './general'

export interface Client extends Resource {
    email: string
    id: number
    name: string
}
