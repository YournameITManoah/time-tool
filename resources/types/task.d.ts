import type { Resource } from './general'

export interface Task extends Resource {
    id: number
    name: string
    billable: boolean
}
