<script setup lang="ts">
import AdminLayout from '@/layouts/AdminLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { Link } from '@inertiajs/vue3';
import { message, Modal } from 'ant-design-vue';
import type { TableColumnsType } from 'ant-design-vue';

const columns = [
  {
    title: 'Sr.',
    dataIndex: 'id',
    key: 'id',
  },
  {
    title: 'Name',
    dataIndex: 'name',
    key: 'name',
  },
  {
    title: 'Description',
    dataIndex: 'description',
    key: 'description',
  },
  {
    title: 'Created At',
    dataIndex: 'created_at',
    key: 'created_at',
  },
  {
    title: 'Action',
    dataIndex: 'action',
    key: 'action',
  },


];
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
        onSuccess: () => {
            form.reset();
             message.success(usePage().props.flash.success);
        },
    });
};
const deleteBrand = (id: number) => {
    Modal.confirm({
        title: 'Are you sure you want to delete this brand?',
        content: 'This action cannot be undone.',
        okText: 'Yes, Delete',
        okType: 'danger',
        cancelText: 'Cancel',
        onOk() {
            form.delete(route('admin.brand.delete', id), {
                onSuccess: () => message.success('Brand deleted successfully!'),
            });
        },
    });
};
</script>

<template>
    <Head title="Brand" />
    <AdminLayout>
        <a-row class="justify-between">
            <!-- Form Section -->
            <a-col :span="11">
            <div class="bg-white shadow-md rounded-lg p-4">
                <h2 class="text-lg font-semibold mb-4">Create Brand</h2>
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
            </div>
            </a-col>

            <!-- Table Section -->
            <a-col :span="11">
                <div class="bg-white p-4 shadow-md rounded-lg">
                    <h2 class="text-lg font-semibold mb-4">Brand List</h2>
                <a-table :columns="columns" :data-source="brands.data" rowKey="id">
                <template #bodyCell="{ column, record  }">
                <template v-if="column.dataIndex === 'id'">
                    <a>{{ record.id }}</a>
                </template>
                <template v-if="column.dataIndex === 'name'">
                    <a>{{ record.name }}</a>
                </template>
                <template v-else-if="column.dataIndex === 'description'">
                    {{ record.description }}
                </template>
                <template v-else-if="column.dataIndex === 'action'">
                    <Link :href="route('admin.brand.edit', record.id)" class="text-blue-500 hover:underline">Edit</Link>
                 </template>
                </template>
            </a-table>
            </div>
            </a-col>

        </a-row>
    </AdminLayout>
</template>
