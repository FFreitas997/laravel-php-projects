<script setup lang="ts">
import { BookPagination } from "@/types/book/Book";
import {router} from "@inertiajs/vue3";
import moment from "moment";

let options = { page: 1, size: 10, title: null }

const { books } = defineProps<{ books: BookPagination }>();

function pagination(page: number) {
    options = { ...options, page: page }
    router.reload({
        data: options,
        onFinish () { }
    })
}

function navigateDetails(id: number) {

}
</script>

<template>
    <div class="w-screen h-screen">
        <div class="w-full h-16 p-3 bg-green-400 dark:bg-green-800 border-b border-green-100 dark:border-green-300 flex items-center justify-between">
            <h1 class="text-lg">Book Reviews</h1>
            <div class="flex items-center gap-4 mr-4"></div>
        </div>
        <div class="w-full h-auto flex items-center justify-center mt-10 px-4">
            <div class="w-3/4 flex items-center justify-between">
                <button
                    class="flex justify-center items-center bg-transparent text-green-400 dark:text-white px-4 py-2 hover:text-gray-300 dark:hover:text-gray-400 disabled:cursor-not-allowed"
                    :disabled="books.current_page === 1"
                    @click="() => pagination(books.current_page - 1)"
                >
                    <span class="material-symbols-outlined">chevron_left</span>
                    <span class="text-lg font-bold">Previous</span>
                </button>
                <button
                    class="flex justify-center items-center bg-transparent text-green-400 dark:text-white px-4 py-2 hover:text-gray-300 dark:hover:text-gray-400 disabled:cursor-not-allowed"
                    :disabled="books.current_page >= books.last_page"
                    @click="() => pagination(books.current_page + 1)"
                >
                    <span class="text-lg font-bold">Next</span>
                    <span class="material-symbols-outlined">chevron_right</span>
                </button>
            </div>
        </div>

        <div class="w-full flex items-center justify-center mt-10 px-4">
            <div v-if="books.data.length" class="w-3/4 overflow-y-auto">
                <div @click="() => navigateDetails(book.id)" v-for="book in books.data" :key="book.id" class="h-auto border border-green-600 dark:border-green-300 rounded-lg shadow-lg shadow-green-500 my-10 mx-3 p-6 hover:bg-green-300 dark:hover:bg-green-600 cursor-pointer">
                    <div class="flex justify-between items-center p-2">
                        <span class="font-bold text-3xl text-black dark:text-white">{{book.title}}</span>
                        <span class="text-2xl text-black dark:text-white">3.5</span>
                    </div>
                    <div class="flex justify-between items-center p-2">
                        <span class="text-gray-500 dark:text-gray-300 text-lg">by {{book.author}}</span>
                        <span class="text-gray-500 dark:text-gray-300 text-lg">out of 5 reviews</span>
                    </div>
                    <div class="flex justify-between items-center p-2">
                        <span class="text-gray-500 dark:text-gray-300 text-sm font-bold">Created at:&nbsp;{{moment(book.created_at).format('DD/MM/YYYY HH:mm')}}</span>
                        <span class="text-gray-500 dark:text-gray-300 text-sm font-bold">Updated at:&nbsp;{{moment(book.updated_at).format('DD/MM/YYYY HH:mm')}}</span>
                    </div>
                </div>
            </div>
            <div v-else>
                <p class="text-gray-500 dark:text-gray-300">No books available.</p>
            </div>
        </div>
    </div>
</template>
