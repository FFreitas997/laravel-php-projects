<script setup lang="ts">
import {Book} from "@/types/book/Book";
import {router, useForm} from "@inertiajs/vue3";

const {book, errors} = defineProps<{ book: Book, errors: {content: string, rating: string} }>();
const formData = useForm({content: null, rating: null})
</script>

<template>
    <div v-if="errors" class="fixed flex flex-wrap justify-end items-center top-0 right-0 m-4 w-1/4">
        <div v-if="errors.content && errors.content.length" class="w-full h-auto bg-red-500 dark:bg-red-800 text-white m-4 p-4">
            <p class="text-lg font-bold text-nowrap">{{ errors.content }}</p>
        </div>
        <div v-if="errors.rating && errors.rating.length" class="w-full h-auto bg-red-500 dark:bg-red-800 text-white m-4 p-4">
            <p class="text-lg font-bold text-nowrap">{{ errors.rating }}</p>
        </div>
    </div>

    <div class=" w-screen h-screen">
        <div class="w-full h-16 p-3 bg-green-400 dark:bg-green-800 border-b border-green-100 dark:border-green-300 flex items-center justify-between">
            <h1 @click="router.get(route('books.index'))" class="text-lg cursor-pointer hover:text-gray-200 dark:hover:text-gray-400">Book Reviews</h1>
            <div class="flex items-center gap-4 mr-4"></div>
        </div>

        <div class="w-full h-auto flex items-center justify-center mt-10">
            <div class="w-3/4 flex items-center justify-between gap-6">
                <span class="text-4xl font-bold text-black dark:text-white">Create a Review for {{ book.title }}</span>
            </div>
        </div>

        <div class="w-full h-auto flex items-center justify-center mt-10 px-4">
            <div class="w-3/4 flex flex-col items-center justify-center">
                <form class="w-full border border-green-300 dark:border-green-600 shadow-sm shadow-green-300 dark:shadow-green-600"
                      @submit.prevent="formData.post(route('books.reviews.store', {id: book.id}))"
                >
                    <div class="block m-4 p-4">
                        <textarea rows="10" v-model="formData.content" type="text" placeholder="Write your review here ..."
                               class="w-full h-auto px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-400 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:focus:ring-green-500"/>
                    </div>

                    <div class="block m-4 p-4">
                        <input v-model="formData.rating" type="number" placeholder="Rating (1-5)"
                               class="w-full h-12 px-4 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-400 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:focus:ring-green-500"/>
                    </div>

                    <div class="flex justify-end items-center m-4 p-4">
                        <button class="flex justify-center items-center bg-green-400 dark:bg-green-800 text-white px-4 py-2 rounded-md hover:bg-green-500 dark:hover:bg-green-700 disabled:cursor-not-allowed"
                                :disabled="formData.processing" type="submit"
                        >
                            <span class="material-symbols-outlined me-3">add</span>
                            <span class="text-lg font-bold">Add Review</span>
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>

</template>
