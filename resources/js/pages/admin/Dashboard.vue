<script setup lang="ts">
import AdminLayout from '@/layouts/AdminLayout.vue';
import { Head } from '@inertiajs/vue3';
import { FileProtectOutlined } from '@ant-design/icons-vue';
import DashboardCard from '@/components/admin/DashboardCard.vue';

</script>

<template>
    <AdminLayout>
        <Head title="Dashboard" />
        <div style="background-color: #ececec; padding: 20px">
            <a-row :gutter="10">
                <a-col :span="6">
                    <DashboardCard
                        title="Brand"
                        :value="33"
                        :icon="FileProtectOutlined"
                        bgColor="bg-yellow-800"
                    />
                </a-col>
            </a-row>
        </div>
    </AdminLayout>
</template>
