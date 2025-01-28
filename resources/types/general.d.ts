export interface PagedParams {
    limit: number
    page: number
    search?: string
    sort: { key: string; order: 'asc' | 'desc' }
}

export interface PagedResult<T> {
    current_page: number
    data: T[]
    first_page_url: string
    from: number
    last_page: number
    last_page_url: string
    links: { active: boolean; label: string; url: null | string }[]
    next_page_url: null | string
    path: string
    per_page: number
    prev_page_url: null | string
    to: number
    total: number
}

export interface Resource {
    created_at: string
    updated_at: string
}

export type Theme = 'dark' | 'light' | 'system'
