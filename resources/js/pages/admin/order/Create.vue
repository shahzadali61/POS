<script setup lang="ts">
import AdminLayout from '@/layouts/AdminLayout.vue';
import { Head, usePage } from '@inertiajs/vue3';
import { ref, watch, computed } from 'vue';

// Props from Laravel (Passed from OrderController)
const props = defineProps<{
    products: {
        id: number;
        name: string;
        purchase_products: {
            id: number;
            sale_price: number;
            stock: number;
            remaining_stock: number;
            description: string | null;
        }[];
    }[];
}>();

const selectedProductId = ref<number | null>(null);
const selectedPurchaseProduct = ref<number | null>(null);
const quantity = ref<number>(1);
const total = ref<number>(0);
const finalPrice = ref<number>(0);

// Set the first product as default selection
if (props.products.length > 0) {
    selectedProductId.value = props.products[0].id;
}

const selectedProduct = computed(() => {
    return props.products.find(product => product.id === selectedProductId.value);
});

const purchaseProducts = computed(() => {
    return selectedProduct.value?.purchase_products || [];
});

const selectedPurchaseProductData = computed(() => {
    return purchaseProducts.value.find(pp => pp.id === selectedPurchaseProduct.value);
});

watch(selectedProductId, () => {
    selectedPurchaseProduct.value = null;
    quantity.value = 1;
    if (purchaseProducts.value.length > 0) {
        selectedPurchaseProduct.value = purchaseProducts.value[0].id;
    }
});

watch(selectedPurchaseProduct, () => {
    if (selectedPurchaseProductData.value) {
        finalPrice.value = selectedPurchaseProductData.value.sale_price; // Set finalPrice here
        total.value = finalPrice.value * quantity.value;
    } else {
        finalPrice.value = 0;
        total.value = 0;
    }
});

watch([quantity, finalPrice], () => {
    if (selectedPurchaseProductData.value) {
        total.value = finalPrice.value * quantity.value;
    }
});

</script>

<template>
    <AdminLayout>
        <Head title="Create Order" />

        <a-row class="">
            <a-col :lg="24">
                <div class="bg-white rounded-lg p-4 shadow-md">
                    <a-row class="gap-2">
                        <a-col :lg="5">
                            <div class="mb-4">
                                <label class="block">Select Product</label>
                                <a-select
                                    v-model:value="selectedProductId"
                                    show-search
                                    placeholder="Search and Select Product"
                                    option-filter-prop="label"
                                    style="width: 100%"
                                    :options="props.products.map(p => ({ value: p.id, label: p.name }))"
                                />
                            </div>
                        </a-col>

                        <a-col :lg="5" v-if="purchaseProducts.length > 0">
                            <div class="mb-4">
                                <label class="block">Select Sale Price</label>
                                <a-select
                                    v-model:value="selectedPurchaseProduct"
                                    placeholder=" Price"
                                    style="width: 100%"
                                    :options="purchaseProducts.map(pp => ({ value: pp.id, label: `Sale Price: ${pp.sale_price} (Stock: ${pp.remaining_stock})` }))"
                                />
                            </div>
                        </a-col>
                        <a-col :lg="3" v-if="purchaseProducts.length > 0">
                            <div class="mb-4">
                                <label class="block">Final Price</label>
                                <a-input type="number" v-model:value="finalPrice" class="w-full"
                                    placeholder="Final Price" />
                            </div>
                        </a-col>

                        <a-col :lg="2" v-if="selectedPurchaseProduct">
                            <div class="mb-4">
                                <label for="">QTY</label>
                                <a-input min="0" type="number" v-model:value="quantity" class="w-full"
                                    placeholder="Enter Quantity" />
                                <!-- <div v-if="selectedPurchaseProductData">
                                    <p>
                                        Sale Price: {{ selectedPurchaseProductData.sale_price }}
                                    </p>
                                    <p>
                                        Stock: {{ selectedPurchaseProductData.remaining_stock }}
                                    </p>
                                </div> -->
                            </div>
                        </a-col>
                        <a-col :lg="4">
                            <div class="mb-4">
                                <label for="">Total</label>
                                <a-input readonly :value="total" class="w-full"
                                    placeholder="Total Price" />
                            </div>
                        </a-col>
                        <a-col :lg="2">
                            <div class="mb-4">
                                <label class="block" for="">Total</label>
                                <a-button type="primary">ADD</a-button>
                            </div>
                        </a-col>
                    </a-row>
                </div>
            </a-col>
        </a-row>
    </AdminLayout>
</template>
