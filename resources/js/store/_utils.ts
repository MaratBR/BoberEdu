import {AxiosError} from "axios";

export function isErrorStatus(error: AxiosError, status: number) {
    return error.response && error.response.status === status
}

export type Pagination<T> = {
    data: T[]
    current_page: number
    first_page_url: string
    from: number
    last_page: number
    last_page_url: string
    next_page_url: string | null
    path: string
    per_page: number
    prev_page_url: string | null
    to: number
    total: number
}
