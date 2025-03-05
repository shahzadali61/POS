<script setup lang="ts">
import AdminLayout from '@/layouts/AdminLayout.vue';
import { Head, useForm, Link } from '@inertiajs/vue3';
import { message } from 'ant-design-vue';
import { reactive, watch } from 'vue';
import { usePage } from '@inertiajs/vue3';
import { onMounted } from 'vue';

// Define props correctly
const props = defineProps({
    brand: Object,
});
const form = useForm({
    name: props.brand.name,
    description: props.brand?.description || '',
});

onMounted(() => {
    const page = usePage();
    if (page.props.flash?.message) {
        message.success(page.props.flash.message);
    }
    if (page.props.flash?.error) {
        message.error(page.props.flash.error);
    }
});

watch(() => props.brand, (newBrand) => {
    form.name = newBrand.name;
    form.description = newBrand.description || '';
}, { deep: true });

const editBrand = () => {
    form.put(route('admin.brand.update', props.brand.id),{
        onSuccess: () => {
            message.success(usePage().props.flash.success);
        },
    })
};
</script>

<template>
    <Head title="Brand-Edit" />
    <AdminLayout>
        <a-row class="justify-between">
            <!-- Form Section -->
            <a-col :span="11">
            <div class="bg-white shadow-md rounded-lg p-4">
                <h2 class="text-lg font-semibold mb-4">Edit Brand</h2>
                <form @submit.prevent="editBrand()">
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
                        <Link :href="route('admin.brands')">
                            <a-button type="default" class="me-2">Back</a-button>
                        </Link>
                        <a-button type="primary" html-type="submit" :loading="form.processing">
                            {{ form.processing ? 'Please wait...' : 'Update' }}
                        </a-button>

                    </div>
                </form>
            </div>
            </a-col>

        </a-row>
    </AdminLayout>
</template>
