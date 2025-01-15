import type { Resource } from './general'

export interface Task extends Resource {
    billable: boolean
    id: number
    name: string
}
