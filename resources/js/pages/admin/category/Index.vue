<script setup lang="ts">
import AdminLayout from '@/layouts/AdminLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { usePage } from '@inertiajs/vue3';
import { message, Modal } from 'ant-design-vue';
import { ref, onMounted } from 'vue';

import dayjs from "dayjs";


const formatDate = (date: string) => {
    return date ? dayjs(date).format("DD-MM-YYYY hh:mm A") : "N/A";
};

// 🔔 Using the following code to display Ant Design toast notifications 👇
onMounted(() => {
    const page = usePage();
    if (page.props.flash?.message) {
        message.success(page.props.flash.message);
    }
    if (page.props.flash?.error) {
        message.error(page.props.flash.error);
    }
});

const columns = [
  { title: 'Sr.', dataIndex: 'id', key: 'id' },
  { title: 'Name', dataIndex: 'name', key: 'name' },
  { title: 'Description', dataIndex: 'description', key: 'description' },
  { title: 'Created At', dataIndex: 'created_at', key: 'created_at' },
  { title: 'Action', dataIndex: 'action', key: 'action' },
];

defineProps({
    categories:Object,
})

const form =useForm({
    name: '',
    description: '',
})
const editForm = useForm({
    id: null,
    name: '',
    description: '',
});
const saveCategory=()=>{
    form.post(route('user.category.store'),{
        onSuccess:()=>{
            form.reset();
            message.success(usePage().props.flash.success);
        }
    })
}
const deleteCategory =(id:number)=>{
    Modal.confirm({
        title: 'Are you sure you want to delete this Category?',
        content: 'This action cannot be undone.',
        okText: 'Yes, Delete',
        okType: 'danger',
        cancelText: 'Cancel',
        onOk() {
            form.delete(route('user.category.delete', id), {
                onSuccess: () =>{
            message.success(usePage().props.flash.success);
        },
            });
        },
    });
};
const isEditModalVisible = ref(false);

const openEditModal = (category: any) => {
    editForm.id = category.id;
    editForm.name = category.name;
    editForm.description = category.description;
    isEditModalVisible.value = true;
};

// Update category
const updateCategory = () => {
    editForm.put(route('user.category.update', editForm.id), {
        onSuccess: () => {
            isEditModalVisible.value = false;
            message.success(usePage().props.flash.success);
        }
    });
};

</script>

<template>

    <Head title="Category" />
    <AdminLayout>

        <a-row class="justify-between">
            <a-col :span="11">

                <div class="bg-white rounded-lg p-4 shadow-md ">

                    <h2 class="text-lg font-semibold mb-4">Create Category</h2>
                    <form @submit.prevent="saveCategory()">
                        <div class="mb-4">
                            <label class="block">Name</label>
                            <a-input v-model:value="form.name" class="mt-2 w-full" placeholder="Enter Name" />
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
                        <a-button type="primary" html-type="submit" >
                        Save
                    </a-button>
                    </div>
                    </form>
                </div>
            </a-col>
            <a-col :span="11">

                <div class="bg-white rounded-lg p-4 shadow-md ">

                    <h2 class="text-lg font-semibold mb-4">Category List</h2>
                    <a-table :columns="columns" :data-source="categories.data" rowKey="id">
                <template #bodyCell="{ column, record  }">
                <template v-if="column.dataIndex === 'id'">
                    <a>{{ record.id }}</a>
                </template>
                <template v-if="column.dataIndex === 'name'">
                    <a>{{ record.name }}</a>
                </template>
                <template v-else-if="column.dataIndex === 'created_at'">
                    {{ formatDate(record.created_at) }}
                </template>
                <template v-else-if="column.dataIndex === 'description'">
                    {{ record.description ?? 'N/A' }}
                </template>
                <template v-else-if="column.dataIndex === 'action'">
                    <a-button type="link" @click="openEditModal(record)">Edit</a-button>
                    <a-button  danger @click="deleteCategory(record.id)">Delete</a-button>
                 </template>
                </template>
            </a-table>

                </div>
            </a-col>


        </a-row>


        <!-- Edit Category Modal -->
       <!-- Edit Category Modal -->
<a-modal v-model:visible="isEditModalVisible" title="Edit Category" @cancel="isEditModalVisible = false" :footer="null" >
    <form @submit.prevent="updateCategory()">
        <div class="mb-4">
            <label class="block">Name</label>
            <a-input v-model:value="editForm.name" class="mt-2 w-full" placeholder="Enter Name" />
            <div v-if="editForm.errors.name" class="text-red-500">{{ editForm.errors.name }}</div>
        </div>
        <div class="mb-4">
            <label class="block">Description</label>
            <a-textarea
                v-model:value="editForm.description"
                class="mt-2 w-full"
                placeholder="Description"
                :auto-size="{ minRows: 2, maxRows: 5 }"
            />
            <div v-if="editForm.errors.description" class="text-red-500">{{ editForm.errors.description }}</div>
        </div>
        <div class="text-right">
            <a-button type="default" @click="isEditModalVisible = false">Cancel</a-button>
            <a-button type="primary" html-type="submit" class="ml-2">Update</a-button>
        </div>
    </form>
</a-modal>





    </AdminLayout>
</template>
