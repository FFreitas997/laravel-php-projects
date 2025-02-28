<script setup lang="ts">

import { onMounted } from 'vue';
import { BookPagination } from '@/types/book/Book';

const props = defineProps<{ books: BookPagination }>();

onMounted(() => {
    console.log('Books:', props.books);
});
</script>

<template>
    <div>Hello</div>
</template>

<style scoped>

</style>
