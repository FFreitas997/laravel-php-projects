export interface Pagination<S> {
    data: S[];
    current_page: number;
    first_page_url: string;
    last_page_url: string;
    next_page_url: string;
    prev_page_url: string;
    from: number;
    to: number;
    last_page: number;
    total: number;
    per_page: number;
}
