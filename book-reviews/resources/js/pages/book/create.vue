<script setup lang="ts">
import {router, useForm} from "@inertiajs/vue3";
import {ref, watch} from "vue";

const {errors} = defineProps<{ errors: { title: string, author: string } }>()
const showError = ref(false);

const formData = useForm({title: null, author: null})

watch(() => errors, (error: { title: string, author: string }) => {
    showError.value = error && (error.title !== null || error.author !== null)
    if (showError.value)
        setTimeout(() => (showError.value = false), 5000)
})
</script>

<template>
    <div v-if="showError" class="fixed flex flex-wrap justify-end items-center top-0 right-0 m-4 w-1/4">
        <div v-if="errors.title && errors.title.length"
             class="w-full h-auto bg-red-500 dark:bg-red-800 text-white m-4 p-4">
            <p class="text-lg font-bold text-nowrap">{{ errors.title }}</p>
        </div>
        <div v-if="errors.author && errors.author.length"
             class="w-full h-auto bg-red-500 dark:bg-red-800 text-white m-4 p-4">
            <p class="text-lg font-bold text-nowrap">{{ errors.author }}</p>
        </div>
    </div>
    <div class="w-screen h-screen">
        <div
            class="w-full h-16 p-3 bg-green-400 dark:bg-green-800 border-b border-green-100 dark:border-green-300 flex items-center justify-between">
            <h1 @click="router.get(route('books.index'))"
                class="text-lg cursor-pointer hover:text-gray-200 dark:hover:text-gray-400">Book Reviews</h1>
            <div class="flex items-center gap-4 mr-4"></div>
        </div>
        <div class="w-full h-auto flex items-center justify-center mt-10 px-4">
            <div class="w-3/4 flex flex-col items-center justify-center">
                <form
                    class="w-full border border-green-300 dark:border-green-600 shadow-sm shadow-green-300 dark:shadow-green-600"
                    @submit.prevent="formData.post(route('books.store'))">
                    <div class="block m-4 p-4">
                        <input v-model="formData.title" type="text" placeholder="Title"
                               class="w-full h-12 px-4 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-400 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:focus:ring-green-500"/>
                    </div>
                    <div class="block m-4 p-4">
                        <input v-model="formData.author" type="text" placeholder="Author"
                               class="w-full h-12 px-4 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-400 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:focus:ring-green-500"/>
                    </div>
                    <div class="flex justify-end items-center m-4 p-4">
                        <button :disabled="formData.processing" type="submit"
                                class="flex justify-center items-center bg-green-400 dark:bg-green-800 text-white px-4 py-2 rounded-md hover:bg-green-500 dark:hover:bg-green-700 disabled:cursor-not-allowed">
                            <span class="material-symbols-outlined me-3">add</span>
                            <span class="text-lg font-bold">Add Book</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>
