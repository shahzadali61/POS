<script setup lang="ts">
import AdminLayout from '@/layouts/AdminLayout.vue';
import { Head } from '@inertiajs/vue3';
import { ref } from 'vue';
import dayjs from "dayjs";
const isLoading = ref(false);
const formatDate = (date: string) => {
    return date ? dayjs(date).format("DD-MM-YYYY hh:mm A") : "N/A";
};





</script>

<template>
      <div v-if="isLoading" class="loading-overlay" >
        <a-spin size="large" />
    </div>
    <AdminLayout>
    <Head title="Order Create" />

        <a-row class="justify-between">



        </a-row>

    </AdminLayout>
</template>
<style scoped>

</style>
