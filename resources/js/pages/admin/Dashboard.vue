<script setup lang="ts">
import AdminLayout from '@/layouts/AdminLayout.vue';
import { Head } from '@inertiajs/vue3';
import { FileProtectOutlined, TaobaoCircleOutlined } from '@ant-design/icons-vue';
import DashboardCard from '@/components/admin/DashboardCard.vue';

const props = defineProps({
    brands: Number,
    totalProduct: Number,
    category: Number,
    totalRevenue: [Number, String],
    products: Array,
});
</script>

<template>
    <AdminLayout>
        <Head title="Dashboard" />

        <div style="background-color: #ececec; padding: 20px">
            <a-row :gutter="[16, 16]">
                <a-col :lg="6" :md="8" :sm="12" :xs="24">
                    <DashboardCard title="Total Sale" :value="Number(props.totalRevenue)" :icon="FileProtectOutlined" bgColor="bg-green-800" />
                </a-col>
                <a-col :lg="6" :md="8" :sm="12" :xs="24">
                    <DashboardCard title="Brands" :value="props.brands" :icon="FileProtectOutlined" bgColor="bg-yellow-800" />
                </a-col>
                <a-col :lg="6" :md="8" :sm="12" :xs="24">
                    <DashboardCard title="Products" :value="props.totalProduct" :icon="FileProtectOutlined" bgColor="bg-yellow-800" />
                </a-col>
                <a-col :lg="6" :md="8" :sm="12" :xs="24">
                    <DashboardCard title="Categories" :value="props.category" :icon="FileProtectOutlined" bgColor="bg-yellow-800" />
                </a-col>
            </a-row>
        </div>
        <div style="background-color: #ececec; padding: 20px">
            <a-row :gutter="[16, 16]">
                <a-col :lg="24" :md="24" :sm="24" :xs="24">
                    <h2 class="text-2xl">Latest Products</h2>
                </a-col>
                <a-col :lg="6" :md="8" :sm="12" :xs="24" v-for="product in props.products" :key="product.id">
                    <DashboardCard
                        :title="'Stock (' + product.total_stock + ')'"
                        :value="product.name"
                        :icon="TaobaoCircleOutlined"
                        bgColor="bg-sky-900"
                    />
                </a-col>
            </a-row>
        </div>
    </AdminLayout>
</template>
