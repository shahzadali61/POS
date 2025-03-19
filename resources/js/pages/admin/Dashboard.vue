<script setup lang="ts">
import AdminLayout from '@/layouts/AdminLayout.vue';
import { Head } from '@inertiajs/vue3';
import { FileProtectOutlined } from '@ant-design/icons-vue';
import DashboardCard from '@/components/admin/DashboardCard.vue';
defineProps({
    brands: Number,
    product: Number,
    category: Number,
});

</script>

<template>
    <AdminLayout>
        <Head title="Dashboard" />
        <div style="background-color: #ececec; padding: 20px">
            <a-row :gutter="10">
                <a-col :span="6">
                    <DashboardCard
                        title="Brands"
                        :value="brands"
                        :icon="FileProtectOutlined"
                        bgColor="bg-yellow-800"
                    />

                </a-col>
                <a-col :span="6">
                    <DashboardCard
                        title="Products"
                        :value="product"
                        :icon="FileProtectOutlined"
                        bgColor="bg-yellow-800"
                    />

                </a-col>
                <a-col :span="6">
                    <DashboardCard
                        title="Categories"
                        :value="category"
                        :icon="FileProtectOutlined"
                        bgColor="bg-yellow-800"
                    />

                </a-col>
            </a-row>
        </div>
    </AdminLayout>
</template>
