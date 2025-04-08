import { Pagination } from '@/types/common/Pagination';
import {Review} from "@/types/review/Review";

export interface Book {
    id: number;
    title: string;
    author: string;
    reviews: Array<Review>;
    reviews_avg_rating: number;
    reviews_count: number;
    created_at: string;
    updated_at: string;
}

export type BookPagination = Pagination<Book>
