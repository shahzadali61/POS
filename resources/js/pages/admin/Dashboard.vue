<script setup lang="ts">
import { ref, computed } from 'vue';
import { Head } from '@inertiajs/vue3';
import AdminLayout from '@/layouts/AdminLayout.vue';
import { FileProtectOutlined, TaobaoCircleOutlined } from '@ant-design/icons-vue';
import DashboardCard from '@/components/admin/DashboardCard.vue';
import dayjs from 'dayjs';

const props = defineProps({
    brands: Number,
    totalProduct: Number,
    category: Number,
    orders: Array,
    products: Array
});

const filters = ref({
    start_date: null,
    end_date: null,
});

// **Total Sales**
const totalSales = computed(() =>
    props.orders.reduce((sum, order) => sum + Number(order.total_price), 0)
);

// **Monthly Sales**
const monthlySales = computed(() => {
    const thisMonth = dayjs().month();
    return props.orders
        .filter(order => dayjs(order.created_at).month() === thisMonth)
        .reduce((sum, order) => sum + Number(order.total_price), 0);
});

// **Weekly Sales**
const weeklySales = computed(() => {
    const startOfWeek = dayjs().startOf('week');
    return props.orders
        .filter(order => dayjs(order.created_at).isAfter(startOfWeek))
        .reduce((sum, order) => sum + Number(order.total_price), 0);
});

// **Today's Sales**
const todaySales = computed(() => {
    const today = dayjs().format('YYYY-MM-DD');
    return props.orders
        .filter(order => dayjs(order.created_at).format('YYYY-MM-DD') === today)
        .reduce((sum, order) => sum + Number(order.total_price), 0);
});

// **Filtered Revenue (Custom Date Range)**
const filteredRevenue = computed(() => {
    if (!filters.value.start_date || !filters.value.end_date) {
        return totalSales.value;
    }

    const start = dayjs(filters.value.start_date);
    const end = dayjs(filters.value.end_date);

    return props.orders
        .filter(order => {
            const orderDate = dayjs(order.created_at);
            return orderDate.isAfter(start.subtract(1, 'day')) && orderDate.isBefore(end.add(1, 'day'));
        })
        .reduce((sum, order) => sum + Number(order.total_price), 0);
});
</script>

<template>
    <AdminLayout>
        <Head title="Dashboard" />

        <!-- Custom Date Filter -->
        <div style="background-color: #ececec; padding: 20px">
            <a-row :gutter="[16, 16]" justify="start">
                <a-col :lg="6" :md="8" :sm="12" :xs="24">
                    <a-date-picker v-model:value="filters.start_date" placeholder="Start Date" />
                </a-col>
                <a-col :lg="6" :md="8" :sm="12" :xs="24">
                    <a-date-picker v-model:value="filters.end_date" placeholder="End Date" />
                </a-col>
            </a-row>
        </div>

        <!-- Sales Summary -->
        <div style="background-color: #ececec; padding: 20px">
            <a-row :gutter="[16, 16]">
                <a-col :lg="6" :md="8" :sm="12" :xs="24">
                    <DashboardCard title="Total Sale" :value="totalSales" :icon="FileProtectOutlined" bgColor="bg-green-800" />
                </a-col>
                <a-col :lg="6" :md="8" :sm="12" :xs="24">
                    <DashboardCard title="Monthly Sale" :value="monthlySales" :icon="FileProtectOutlined" bgColor="bg-blue-800" />
                </a-col>
                <a-col :lg="6" :md="8" :sm="12" :xs="24">
                    <DashboardCard title="Weekly Sale" :value="weeklySales" :icon="FileProtectOutlined" bgColor="bg-orange-800" />
                </a-col>
                <a-col :lg="6" :md="8" :sm="12" :xs="24">
                    <DashboardCard title="Today's Sale" :value="todaySales" :icon="FileProtectOutlined" bgColor="bg-red-800" />
                </a-col>
            </a-row>
        </div>

        <!-- Custom Date Filtered Revenue -->
        <div style="background-color: #ececec; padding: 20px">
            <a-row :gutter="[16, 16]">
                <a-col :lg="6" :md="8" :sm="12" :xs="24">
                    <DashboardCard title="Filtered Revenue" :value="filteredRevenue" :icon="FileProtectOutlined" bgColor="bg-purple-800" />
                </a-col>
            </a-row>
        </div>

        <!-- Latest Products -->
        <div style="background-color: #ececec; padding: 20px">
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
