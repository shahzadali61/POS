<script setup lang="ts">
import AdminLayout from '@/layouts/AdminLayout.vue';
import { Head } from '@inertiajs/vue3';
import dayjs from "dayjs";

const formatDate = (date: string) => {
    return date ? dayjs(date).format("DD-MM-YYYY hh:mm A") : "N/A";
};

const columns = [
    { title: 'ID', dataIndex: 'id', key: 'id' },
    { title: 'Name', dataIndex: 'name', key: 'name' },
    { title: 'Phone No', dataIndex: 'phone_no', key: 'phone_no' },
    { title: 'SubTotal', dataIndex: 'subtotal', key: 'subtotal' },
    { title: 'Discount (%)', dataIndex: 'discount', key: 'discount' },
    { title: 'Total', dataIndex: 'total', key: 'total' },
    { title: 'Created At', dataIndex: 'created_at', key: 'created_at' },
];

defineProps({
    orders: Object, // ✅ Ensure correct prop name
});
</script>

<template>
    <AdminLayout>
    <Head title="Order List" />
        <a-row>
            <a-col :span="24">
                <div class="bg-white rounded-lg p-4 shadow-md">
                    <div class="mb-4 flex items-center justify-between">
                        <h2 class="text-lg font-semibold">Order List</h2>


                    </div>

                    <a-table v-if="orders" :columns="columns" :data-source="orders.data" rowKey="id">
                        <template #bodyCell="{ column, record,index  }">
                            <template v-if="column.dataIndex === 'id'">
                                {{ index + 1 }}
                            </template>
                            <template v-else-if="column.dataIndex === 'name'">
                                {{ record.name }}
                            </template>
                            <template v-else-if="column.dataIndex === 'phone_no'">
                                {{ record.phone_number }}
                            </template>
                            <template v-else-if="column.dataIndex === 'subtotal'">

                                {{ Math.floor(record.subtotal_price)}}
                            </template>
                            <template v-else-if="column.dataIndex === 'discount'">
                                {{ Math.floor(record.discount) }}
                                </template>
                            <template v-else-if="column.dataIndex === 'total'">
                                {{ Math.floor(record.total_price)}}
                            </template>
                            <template v-else-if="column.dataIndex === 'created_at'">
                                {{ formatDate(record.created_at) }}
                            </template>
                        </template>
                    </a-table>

                </div>
            </a-col>
        </a-row>
    </AdminLayout>
</template>
