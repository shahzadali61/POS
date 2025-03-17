<script setup lang="ts">
import AdminLayout from '@/layouts/AdminLayout.vue';
import { Head } from '@inertiajs/vue3';
import { ref } from 'vue';
// import dayjs from "dayjs";
const isLoading = ref(false);
// const formatDate = (date: string) => {
//     return date ? dayjs(date).format("DD-MM-YYYY hh:mm A") : "N/A";
// };





</script>

<template>
      <div v-if="isLoading" class="loading-overlay" >
        <a-spin size="large" />
    </div>
    <AdminLayout>
    <Head title="Order Create" />

        <a-row class="justify-between">
        <a-col :lg="18">
            <div class="bg-white rounded-lg p-4 shadow-md ">
            <a-row>
            <a-col :lg="8">
                <div class="mb-4">
                            <label class="block">Select Product</label>
                            <a-input class="mt-2 w-full" placeholder="Select Product" />
                        </div>

            </a-col>


            </a-row>

        </div>


        </a-col>



        </a-row>

    </AdminLayout>
</template>
<style scoped>

</style>
