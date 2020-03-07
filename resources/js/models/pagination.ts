export interface IPagination<T> extends Object {
    current_page: number
    data: T[]
    first_page_url: string
    from: any
    last_page: number
    last_page_url: string
    next_page_url: string | null
    path: string
    per_page: number
    prev_page_url: string | null
    to: any
    total: number
}

export default class Pagination<T> implements IPagination<T> {
    readonly current_page: number;
    readonly data: T[];
    readonly first_page_url: string;
    readonly from: any;
    readonly last_page: number;
    readonly last_page_url: string;
    readonly next_page_url: string | null;
    readonly path: string;
    readonly per_page: number;
    readonly prev_page_url: string | null;
    readonly to: any;
    readonly total: number;

    constructor(response: IPagination<T>) {
        for (let k in response) {
            if (response.hasOwnProperty(k))
                this[k] = response[k];
        }
    }

    get isLast() { return this.current_page === this.last_page }
    get isFirst() { return this.current_page === 1 }
    get isOnly() { return this.last_page === 1 }
    get isOutside() { return this.current_page > this.last_page }

    get hasNext() { return !this.isLast && !this.isOutside }
    get hasPrev() { return !this.isFirst }
}
