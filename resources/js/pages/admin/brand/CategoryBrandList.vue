<script setup lang="ts">
import AdminLayout from '@/layouts/AdminLayout.vue';
import { Head, Link , usePage, useForm} from '@inertiajs/vue3';
import { message, Modal } from 'ant-design-vue';
import dayjs from "dayjs";
import { ref, onMounted } from 'vue';
const isLoading = ref(false);

onMounted(() => {
    const page = usePage();
    if (page.props.flash?.message) {
        message.success(page.props.flash.message);
    }
    if (page.props.flash?.error) {
        message.error(page.props.flash.error);
    }
});

// Format date function
const formatDate = (date: string) => {
    return date ? dayjs(date).format("DD-MM-YYYY hh:mm A") : "N/A";
};

// Define table columns
const columns = [
    { title: 'ID', dataIndex: 'id', key: 'id' },
    { title: 'Name', dataIndex: 'name', key: 'name' },
    { title: 'Description', dataIndex: 'description', key: 'description' },
    { title: 'Category', dataIndex: 'category', key: 'category' },
    { title: 'Created At', dataIndex: 'created_at', key: 'created_at' },
    { title: 'Action', dataIndex: 'action', key: 'action' },
];

// Receive brands as a prop from Inertia
defineProps({
    brands: Object,
    category: Object,
});
const form = useForm({});
const deleteBrand = (id: number) => {
    Modal.confirm({
        title: 'Are you sure you want to delete this Brand?',
        content: 'This action cannot be undone.',
        okText: 'Yes, Delete',
        okType: 'danger',
        cancelText: 'Cancel',
        onOk() {
            isLoading.value = true;
            form.delete(route('user.brand.delete', id), {
                onSuccess: () => {
                    message.success(usePage().props.flash.success);
                },
                onFinish: () => {
           isLoading.value = false;
        }
            });
        },
    });
};

</script>

<template>
    <AdminLayout>
        <Head title="Brand List" />
        <a-row>
            <a-col :span="24">
                <div class="bg-white rounded-lg p-4 shadow-md">
                    <div class="mb-4 flex items-center justify-between">
                        <h2 class="text-lg font-semibold">Brand List - {{ category.name }}</h2>

                        <Link :href="route('user.categories')">
                            <a-button type="default">Back</a-button>
                        </Link>
                    </div>

                    <!-- Display table -->
                    <a-table v-if="brands" :columns="columns" :data-source="brands.data" rowKey="id" bordered>
                        <template #bodyCell="{ column, record, index }">
                            <template v-if="column.dataIndex === 'id'">
                                {{ index + 1 }}
                            </template>
                            <template v-if="column.dataIndex === 'name'">
                                {{ record.name}}
                            </template>
                            <template v-if="column.dataIndex === 'description'">
                                {{ record.description ? record.description : 'N/A'}}
                            </template>
                            <template v-if="column.dataIndex === 'category'">
                                {{ record.category.slug}}
                            </template>

                            <template v-else-if="column.dataIndex === 'created_at'">
                                {{ formatDate(record.created_at) }}
                            </template>
                            <template v-else-if="column.dataIndex === 'action'">
                            <a-tooltip placement="top">
                                    <template #title>Delete</template>
                                    <a-button type="link"  @click="deleteBrand(record.id)"><i class="fa fa-trash text-red-500" aria-hidden="true"></i></a-button>
                                </a-tooltip>
                            </template>

                        </template>
                    </a-table>
                </div>
            </a-col>
        </a-row>
    </AdminLayout>
</template>
