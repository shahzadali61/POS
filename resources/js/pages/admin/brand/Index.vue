<script setup lang="ts">
import AdminLayout from "@/layouts/AdminLayout.vue";
import { Head, Link, usePage, useForm } from "@inertiajs/vue3";
import { message, Modal } from "ant-design-vue";
import dayjs from "dayjs";
import { watch } from 'vue';
import { ref } from "vue";
const isLoading = ref(false);
const page = usePage();

watch(() => page.props.flash, (flash) => {
    if (flash?.success) {
        message.success(flash.success);
    }
    if (flash?.error) {
        message.error(flash.error);
    }
});

const columns = [
  { title: "Sr.", dataIndex: "id", key: "id" },
  { title: "Name", dataIndex: "name", key: "name" },
  { title: "Description", dataIndex: "description", key: "description" },
  { title: "Category", dataIndex: "category", key: "category" },
  { title: "Created At", dataIndex: "created_at", key: "created_at" },
  { title: "Action", dataIndex: "action", key: "action" },
];
const formatDate = (date: string) => {
  return date ? dayjs(date).format("DD-MM-YYYY hh:mm A") : "N/A";
};

// Define props correctly
defineProps({
  brands: Object,
});

const form = useForm({
  name: "",
  description: "",
});
const editForm = useForm({
  id: null,
  name: "",
  description: "",
});
const productForm = useForm({
  id: null,
  name: "",
  description: "",
  brand_id: null,
});
const deleteBrand = (id: number) => {
  Modal.confirm({
    title: "Are you sure you want to delete this Brand?",
    content: "This action cannot be undone.",
    okText: "Yes, Delete",
    okType: "danger",
    cancelText: "Cancel",
    onOk() {
      isLoading.value = true;
      form.delete(route("user.brand.delete", id), {
        onSuccess: () => {

        },
        onFinish: () => {
          isLoading.value = false;
        },
      });
    },
  });
};
const isEditModalVisible = ref(false);
const isproductModalVisible = ref(false);
const selectedBrandName = ref("");

const openEditModal = (brands: any) => {
  editForm.id = brands.id;
  editForm.name = brands.name;
  editForm.description = brands.description;
  isEditModalVisible.value = true;
};
// Update brand
const updateBrand = () => {
  isLoading.value = true;
  editForm.put(route("user.brand.update", editForm.id), {
    onSuccess: () => {
      isEditModalVisible.value = false;
    },
    onFinish: () => {
      isLoading.value = false;
    },
  });
};
const openProductModal = (record: any) => {
  selectedBrandName.value = record.name;
  productForm.brand_id = record.id;
  isproductModalVisible.value = true;
};
const saveProduct = () => {
  isLoading.value = true;
  productForm.post(route("user.product.store"), {
    onSuccess: () => {
      productForm.reset();
      isproductModalVisible.value = false;
    },
    onFinish: () => {
      isLoading.value = false;
    },
  });
};
</script>
<template>
  <AdminLayout>
    <Head title="Brands" />
    <a-row>
      <a-col :span="24">
        <div class="bg-white p-4 shadow-md rounded-lg">
          <div class="mb-4 flex items-center justify-between">
            <h2 class="text-lg font-semibold mb-4">Brand List</h2>
            <Link :href="route('user.brand-log')">
              <a-button type="default">Brand Logs</a-button>
            </Link>
          </div>
          <a-table :columns="columns" :data-source="brands.data" rowKey="id">
            <template #bodyCell="{ column, record, index }">
              <template v-if="column.dataIndex === 'id'">
                {{ index + 1 }}
              </template>
              <template v-if="column.dataIndex === 'name'">
                <a>{{ record.name }}</a>
              </template>
              <template v-else-if="column.dataIndex === 'description'">
                {{ record.description ? record.description : "N/A" }}
              </template>
              <template v-else-if="column.dataIndex === 'category'">
                {{ record.category?.name || "N/A" }}
              </template>
              <template v-else-if="column.dataIndex === 'created_at'">
                {{ formatDate(record.created_at) }}
              </template>
              <template v-else-if="column.dataIndex === 'action'">
                <a-tooltip placement="top">
                  <template #title>Delete</template>
                  <a-button type="link" @click="deleteBrand(record.id)"
                    ><i class="fa fa-trash text-red-500" aria-hidden="true"></i
                  ></a-button>
                </a-tooltip>
                <a-tooltip placement="top">
                  <template #title>Edit</template>
                  <a-button type="link" @click="openEditModal(record)"
                    ><i
                      class="fa fa-pencil-square-o text-s text-green-500"
                      aria-hidden="true"
                    ></i
                  ></a-button>
                </a-tooltip>
                <a-tooltip placement="top">
                  <template #title>Add Product</template>
                  <a-button type="link" @click="openProductModal(record)"
                    ><i class="fa fa-product-hunt text-green-500" aria-hidden="true"></i
                  ></a-button>
                </a-tooltip>
                <a-tooltip placement="top">
                  <template #title>Product list</template>
                  <Link
                    :href="route('user.related-product-list', record.slug)"
                    class="text-blue-500 hover:underline"
                    ><i class="fa fa-list text-slate-800" aria-hidden="true"></i
                  ></Link>
                </a-tooltip>
              </template>
            </template>
          </a-table>
        </div>
      </a-col>
    </a-row>
    <!-- Edit Category Modal -->
    <a-modal
      v-model:visible="isEditModalVisible"
      title="Edit Brand"
      @cancel="isEditModalVisible = false"
      :footer="null"
    >
      <form @submit.prevent="updateBrand()">
        <div class="mb-4">
          <label class="block">Name</label>
          <a-input
            v-model:value="editForm.name"
            class="mt-2 w-full"
            placeholder="Enter Name"
          />
          <div v-if="editForm.errors.name" class="text-red-500">
            {{ editForm.errors.name }}
          </div>
        </div>
        <div class="mb-4">
          <label class="block">Description</label>
          <a-textarea
            v-model:value="editForm.description"
            class="mt-2 w-full"
            placeholder="Description"
            :auto-size="{ minRows: 2, maxRows: 5 }"
          />
          <div v-if="editForm.errors.description" class="text-red-500">
            {{ editForm.errors.description }}
          </div>
        </div>
        <div class="text-right">
          <a-button type="default" @click="isEditModalVisible = false">Cancel</a-button>
          <a-button type="primary" html-type="submit" class="ml-2">Update</a-button>
        </div>
      </form>
    </a-modal>
    <!-- Product Modal  -->
    <a-modal
      v-model:visible="isproductModalVisible"
      title="Add Brand  "
      @cancel="isproductModalVisible = false"
      :footer="null"
    >
      <h4 class="text-md">Brand ({{ selectedBrandName }})</h4>
      <form @submit.prevent="saveProduct()">
        <a-input
          v-model:value="productForm.brand_id"
          class="mt-2 w-full"
          placeholder="Enter Name"
        />
        <div class="mb-4">
          <label class="block">Name</label>
          <a-input
            v-model:value="productForm.name"
            class="mt-2 w-full"
            placeholder="Enter Name"
          />
          <div v-if="productForm.errors.name" class="text-red-500">
            {{ productForm.errors.name }}
          </div>
        </div>
        <div class="mb-4">
          <label class="block">Description</label>
          <a-textarea
            v-model:value="productForm.description"
            class="mt-2 w-full"
            placeholder="Description"
            :auto-size="{ minRows: 2, maxRows: 5 }"
          />
          <div v-if="productForm.errors.description" class="text-red-500">
            {{ productForm.errors.description }}
          </div>
        </div>
        <div class="text-right">
          <a-button type="default" @click="isproductModalVisible = false"
            >Cancel</a-button
          >
          <a-button type="primary" html-type="submit" class="ml-2">Save</a-button>
        </div>
      </form>
    </a-modal>
  </AdminLayout>
</template>
