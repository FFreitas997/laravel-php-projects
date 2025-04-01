<script setup lang="ts">
import { BookPagination } from "@/types/book/Book";

const { books } = defineProps<{ books: BookPagination }>();
</script>

<template>
    <h1>Books List</h1>
</template>

<style scoped></style>
