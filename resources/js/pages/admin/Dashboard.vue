<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import AdminLayout from '@/layouts/AdminLayout.vue';
import SaleManagement from '@/components/admin/SaleManagement.vue';
import { TaobaoCircleOutlined } from '@ant-design/icons-vue';
import DashboardCard from '@/components/admin/DashboardCard.vue';

const props = defineProps({
    brands: Number,
    totalProduct: Number,
    category: Number,
    orders: Array,
    products: Array
});
</script>

<template>
    <AdminLayout>
        <Head title="Dashboard" />
        <SaleManagement :orders="props.orders" />
        <!-- Latest Products -->
        <div class="mt-5"  style="background-color: #ececec; padding: 20px">
            <a-row :gutter="[16, 16]">
                <a-col :lg="24" :md="24" :sm="24" :xs="24">
                    <h2 class="text-2xl">Latest Products</h2>
                </a-col>
                <a-col :lg="6" :md="8" :sm="12" :xs="24" v-for="product in props.products" :key="product.id">
                    <DashboardCard
                        :title="'Stock (' + product.total_stock + ')' "
                        :value="product.name"
                        :icon="TaobaoCircleOutlined"
                        bgColor="bg-sky-900"
                    />
                </a-col>
            </a-row>
        </div>
    </AdminLayout>
</template>
