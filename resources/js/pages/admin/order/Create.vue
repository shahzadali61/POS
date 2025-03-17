<script setup lang="ts">
import AdminLayout from '@/layouts/AdminLayout.vue';
import { Head } from '@inertiajs/vue3';
import { ref } from 'vue';

// Props from Laravel (Passed from OrderController)
defineProps<{
    products: {
        id: number;
        name: string;
    }[];
}>();

const selectedProductId = ref<number | null>(null);
</script>
<template>
    <AdminLayout>
        <Head title="Order Create" />

        <a-row class="justify-between">
            <a-col :lg="18">
                <div class="bg-white rounded-lg p-4 shadow-md">
                    <a-row>
                        <a-col :lg="8">
                            <div class="mb-4">
                                <label class="block">Select Product</label>
                                <!-- Searchable Product Dropdown -->
                                <a-select
                                    v-model:value="selectedProductId"
                                    show-search
                                    placeholder="Search and Select Product"
                                    option-filter-prop="label"
                                    style="width: 100%"
                                    :options="products.map(p => ({ value: p.id, label: p.name }))"
                                />
                            </div>
                        </a-col>
                    </a-row>
                </div>
            </a-col>
        </a-row>
    </AdminLayout>
</template>
