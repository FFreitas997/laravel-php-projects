<script setup lang="ts">

import {Book} from "@/types/book/Book";
import {router} from "@inertiajs/vue3";
import moment from "moment";
import {computed} from "vue";
import {Review} from "@/types/review/Review";
import StarRating from 'vue-star-rating'

const {book} = defineProps<{ book: Book }>();
const createdAt = computed(computedCreatedAt)
const updatedAt = computed(computedUpdatedAt)

function computedCreatedAt(): string {
    return moment(book.created_at).format('DD/MM/YYYY HH:mm')
}

function computedUpdatedAt(): string {
    return moment(book.updated_at).format('DD/MM/YYYY HH:mm')
}

function updateAtReview(review: Review): string {
    return moment(review.updated_at).format('DD/MM/YYYY HH:mm')
}

</script>

<template>
    <div class="relative w-screen h-screen">
        <div class="w-full h-16 p-3 bg-green-400 dark:bg-green-700 border-b border-green-100 dark:border-green-300 flex items-center justify-between">

            <h1 @click="router.get(route('books.index'))"
                class="text-lg cursor-pointer hover:text-gray-200 dark:hover:text-gray-400">
                Book Reviews
            </h1>

            <div class="flex items-center gap-4 mr-4">
                <button class="flex justify-center items-center bg-transparent text-green-900 dark:text-green-200 px-4 py-2 hover:text-gray-200 dark:hover:text-gray-400 disabled:cursor-not-allowed"
                    @click="() => router.get(route('books.create'))">
                    <span class="material-symbols-outlined me-3">add</span>
                    <span class="text-lg font-bold">Add Book</span>
                </button>
                <button class="flex justify-center items-center bg-transparent text-green-900 dark:text-green-200 px-4 py-2 hover:text-gray-200 dark:hover:text-gray-400 disabled:cursor-not-allowed"
                        @click="() => router.delete(route('books.destroy', {id: book.id}))">
                    <span class="material-symbols-outlined me-3">delete</span>
                    <span class="text-lg font-bold">Delete Book</span>
                </button>
                <button class="flex justify-center items-center bg-transparent text-green-900 dark:text-green-200 px-4 py-2 hover:text-gray-200 dark:hover:text-gray-400 disabled:cursor-not-allowed"
                        @click="() => router.get(route('books.reviews.create', {id: book.id}))">
                    <span class="material-symbols-outlined me-3">edit</span>
                    <span class="text-lg font-bold">Create Review</span>
                </button>
            </div>

        </div>

        <div class="w-full h-auto flex items-center justify-center mt-10">
            <div class="w-3/4 flex items-center justify-between gap-6">
                <span class="text-4xl font-bold text-black dark:text-white">{{ book.title }}</span>
                <span class="text-2xl font-bold text-black dark:text-white">By: {{ book.author }}</span>
            </div>
        </div>

        <div class="w-full h-auto flex items-center justify-center mt-5">
            <div class="w-3/4 flex items-center justify-between gap-6">
                <span class="text-2xl font-bold text-black dark:text-white">{{ book.reviews_count }} reviews</span>
            </div>
        </div>

        <div class="w-full h-auto flex items-center justify-center mt-5">
            <div class="w-3/4 flex items-center justify-between gap-6">
                <span class="text-sm text-black dark:text-white">created: {{ createdAt }}</span>
                <span class="text-sm text-black dark:text-white">updated: {{ updatedAt }}</span>
            </div>
        </div>

        <div class="w-full flex items-center justify-center mt-10">
            <div class="w-3/4">
                <h3 class="text-xl text-black dark:text-white font-bold cursor-pointer hover:text-gray-200 dark:hover:text-gray-400">Reviews</h3>
            </div>
        </div>

        <div class="w-full flex items-center justify-center">
            <div class="w-3/4 overflow-y-auto" v-if="book.reviews && book.reviews.length" >
                <div class="h-auto my-10 p-4 border border-green-600 dark:border-green-300 rounded-lg shadow-lg shadow-green-500   hover:bg-green-300 dark:hover:bg-green-600 "
                     v-for="review in book.reviews" :key="review.id"
                >
                    <div class="flex justify-between items-center p-2">
                        <star-rating :animate="true" :star-size="25" :show-rating="false" :read-only="true" v-model:rating="review.rating" />
                        <span class="text-sm font-bold text-black dark:text-white">{{ updateAtReview(review) }}</span>
                    </div>

                    <div class="w-full p-2">
                        <p class="block text-wrap text-justify text-xl text-black dark:text-white">{{ review.content }}</p>
                    </div>

                </div>
            </div>
            <div v-else>
                <p class="text-gray-500 dark:text-gray-300">No reviews available.</p>
            </div>
        </div>

    </div>
</template>
