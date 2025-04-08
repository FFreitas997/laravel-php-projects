<script setup lang="ts">
import {Book, BookPagination} from "@/types/book/Book";
import {router} from "@inertiajs/vue3";
import {reactive} from "vue";
import debounce from "debounce";
import StarRating from 'vue-star-rating'

const searchDebounce = debounce(search, 500)

const {books} = defineProps<{ books: BookPagination }>();
let {page, size, title, filter} = reactive({page: 1, size: 10, title: null, filter: 'latest'})

function pagination(currentPage: number) {
    page = currentPage
    router.reload({data: {page: currentPage, title, size}})
}

function search() {
    page = 1
    router.reload({data: {page, title, size}})
}

function navigateDetails(id: number) {
    router.get(route('books.show', {id}))
}

function averageRating(book: Book): number {
    if (!book || isNaN(book.reviews_avg_rating)) return 0
    return Math.round(book.reviews_avg_rating)
}

function handleView(filter: string) {
    page = 1
    router.reload({data: {page, title, size, filter}})
}

function reset() {
    page = 1
    title = null
    filter = 'latest'
    router.reload({data: {page, title, size, filter}})
}
</script>

<template>
    <div class="relative w-screen h-screen">
        <div
            class="w-full h-16 p-3 bg-green-400 dark:bg-green-800 border-b border-green-100 dark:border-green-300 flex items-center justify-between">
            <h1 @click="router.get(route('books.index'))"
                class="text-lg cursor-pointer hover:text-gray-200 dark:hover:text-gray-400"
            >
                Book Reviews
            </h1>

            <div class="flex items-center gap-4 mr-4">
                <button
                    class="flex justify-center items-center bg-transparent text-green-400 dark:text-white px-4 py-2 hover:text-gray-300 dark:hover:text-gray-400 disabled:cursor-not-allowed"
                    @click="() => router.get(route('books.create'))">
                    <span class="material-symbols-outlined me-3">add</span>
                    <span class="text-lg font-bold">Add Book</span>
                </button>
            </div>

        </div>

        <div class="w-full h-auto flex items-center justify-center my-10 p-5">
            <div class="w-3/4 flex items-center justify-between gap-6">
                <input
                    type="text"
                    class="w-full h-16 text-lg text-black dark:text-white bg-gray-300 dark:bg-gray-600 border border-green-400 dark:border-green-800 rounded-lg px-4 focus:outline-none focus:ring focus:ring-green-300 dark:focus:ring-green-600"
                    placeholder="Search by title or author"
                    @input="searchDebounce"
                    v-model="title">

                <button
                    class="inline-flex gap-6 cursor-pointer p-4 bg-green-400 dark:bg-green-800 text-white rounded-lg hover:bg-green-300 dark:hover:bg-green-600"
                    @click="reset">
                    <span class="material-symbols-outlined">mop</span>
                    <span class="text-lg font-bold">Reset</span>
                </button>

            </div>
        </div>

        <div class="w-full h-auto flex items-center justify-center mt-10 px-4 overflow-x-auto">
            <div class="w-3/4 flex items-center">

                <button
                    class="flex justify-center items-center bg-transparent text-green-400 dark:text-white px-4 py-2 hover:text-gray-300 dark:hover:text-gray-400 disabled:cursor-not-allowed"
                    :disabled="books.current_page === 1"
                    @click="() => pagination(books.current_page - 1)"
                >
                    <span class="material-symbols-outlined">chevron_left</span>
                    <span class="text-lg font-bold">Previous</span>
                </button>

                <div class="flex-1 flex gap-3 items-center justify-evenly">

                    <button
                        class="cursor-pointer p-4 text-nowrap bg-green-400 dark:bg-green-800 text-white rounded-lg hover:bg-green-300 dark:hover:bg-green-600"
                        @click="handleView('latest')">
                        Latest
                    </button>

                    <button
                        class="cursor-pointer p-4 text-nowrap bg-green-400 dark:bg-green-800 text-white rounded-lg hover:bg-green-300 dark:hover:bg-green-600"
                        @click="handleView('popular_last_month')">
                        Popular Last Month
                    </button>

                    <button
                        class="cursor-pointer p-4 text-nowrap bg-green-400 dark:bg-green-800 text-white rounded-lg hover:bg-green-300 dark:hover:bg-green-600"
                        @click="handleView('popular_last_6month')">
                        Popular Last 6 Months
                    </button>

                    <button
                        class="cursor-pointer p-4 text-nowrap bg-green-400 dark:bg-green-800 text-white rounded-lg hover:bg-green-300 dark:hover:bg-green-600"
                        @click="handleView('highest_rated_last_month')">
                        Highest Rated Last Month
                    </button>

                    <button
                        class="cursor-pointer p-4 text-nowrap bg-green-400 dark:bg-green-800 text-white rounded-lg hover:bg-green-300 dark:hover:bg-green-600"
                        @click="handleView('highest_rated_last_6month')">
                        Highest Rated Last 6 Months
                    </button>

                </div>

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
            <div v-if="books.data && books.data.length" class="w-3/4 overflow-y-auto">
                <div @click="() => navigateDetails(book.id)" v-for="book in books.data" :key="book.id"
                     class="h-auto border border-green-600 dark:border-green-300 rounded-lg shadow-lg shadow-green-500 my-10 mx-3 p-6 hover:bg-green-300 dark:hover:bg-green-600 cursor-pointer">
                    <div class="flex justify-between items-center p-2">
                        <span class="font-bold text-3xl text-black dark:text-white">{{ book.title }}</span>
                        <star-rating :animate="true" :star-size="25" :show-rating="false" :read-only="true" :rating="averageRating(book)" />
                    </div>
                    <div class="flex justify-between items-center p-2">
                        <span class="text-gray-500 dark:text-gray-300 text-lg">by {{ book.author }}</span>
                        <span class="text-gray-500 dark:text-gray-300 text-lg">out of {{book.reviews_count }} reviews</span>
                    </div>
                </div>
            </div>
            <div v-else>
                <p class="text-gray-500 dark:text-gray-300">No books available.</p>
            </div>
        </div>
    </div>
</template>
