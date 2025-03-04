<script setup lang="ts">
import AdminLayout from '@/layouts/AdminLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';

// Define props correctly
defineProps({
    brands: Object,
});

const form = useForm({
    name: '',
    description: '',
});

const saveBrand = () => {
    form.post(route('admin.brand.store'), {
        onSuccess: () => form.reset(),
    });
};
</script>

<template>
    <Head title="Brand" />
    <AdminLayout>
        <a-row class="">
            <!-- Form Section -->
            <a-col :span="10">
                <form @submit.prevent="saveBrand()">
                    <div class="mb-4">
                        <label class="block">Name</label>
                        <a-input class="mt-2 w-full" v-model:value="form.name" placeholder="Enter Name" />
                        <div v-if="form.errors.name" class="text-red-500">{{ form.errors.name }}</div>
                    </div>
                    <div class="mb-4">
                        <label class="block">Description</label>
                        <a-textarea
                            v-model:value="form.description"
                            class="mt-2 w-full"
                            placeholder="Description"
                            :auto-size="{ minRows: 2, maxRows: 5 }"
                        />
                        <div v-if="form.errors.description" class="text-red-500">{{ form.errors.description }}</div>
                    </div>
                    <div>
                        <a-button type="primary" html-type="submit">Save</a-button>
                    </div>
                </form>
            </a-col>

            <!-- Table Section -->
            <a-col :span="12">
                <div class="bg-white p-4 shadow-md rounded-lg">
                    <h2 class="text-lg font-semibold mb-4">Brand List</h2>
                    <div class="overflow-x-auto">
                        <table class="w-full border border-gray-300 rounded-lg">
                            <thead class="bg-gray-100">
                                <tr class="border-b">
                                    <th class="px-4 py-2 text-left">ID</th>
                                    <th class="px-4 py-2 text-left">Name</th>
                                    <th class="px-4 py-2 text-left">Description</th>
                                    <th class="px-4 py-2 text-left">Created At</th>
                                    <th class="px-4 py-2 text-left">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(item, index) in brands.data" :key="index" class="border-b hover:bg-gray-50">
                                    <td class="px-4 py-2">{{ item.id }}</td>
                                    <td class="px-4 py-2">{{ item.name }}</td>
                                    <td class="px-4 py-2">{{ item.description }}</td>
                                    <td class="px-4 py-2">{{ item.created_at }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                     <!-- Pagination -->
                     <div class="mt-4 flex justify-between">
                        <a-button
                            :disabled="!brands.links.prev"
                            @click="$inertia.get(brands.links.prev)"
                        >Previous</a-button>
                        <a-button
                            :disabled="!brands.links.next"
                            @click="$inertia.get(brands.links.next)"
                        >Next</a-button>
                    </div>

                </div>
            </a-col>
        </a-row>
    </AdminLayout>
</template>
