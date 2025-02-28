import { Pagination } from '@/types/common/Pagination';

export interface Book {
    id: number;
    title: string;
    author: string;
    created_at: string;
    updated_at: string;
}

export type BookPagination = Pagination<Book>
