<script setup lang="ts">
import AdminLayout from '@/layouts/AdminLayout.vue';
import { Head, router } from '@inertiajs/vue3';
import { ref, watch, computed } from 'vue';
import { message } from 'ant-design-vue';
const isLoading = ref(false);
const errors = ref<Record<string, string>>({});

const customerName = ref('');
const phoneNumber = ref('');
const paymentMethod = ref('');
const status = ref('completed');

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

const selectedProductId = ref<number | null>(props.products.length > 0 ? props.products[0].id : null);
const selectedPurchaseProduct = ref<number | null>(null);
const quantity = ref<number>(1);
const total = ref<number>(0);
const finalPrice = ref<number>(0);
const discount = ref<number>(0);
const orderItems = ref<{ id: number; name: string; price: number; quantity: number; total: number }[]>([]);

const selectedProduct = computed(() => props.products.find(p => p.id === selectedProductId.value));
const purchaseProducts = computed(() => selectedProduct.value?.purchase_products || []);
const selectedPurchaseProductData = computed(() => purchaseProducts.value.find(pp => pp.id === selectedPurchaseProduct.value));

watch(selectedProductId, () => {
    selectedPurchaseProduct.value = purchaseProducts.value.length > 0 ? purchaseProducts.value[0].id : null;
    quantity.value = 1;
});

watch(selectedPurchaseProduct, () => {
    finalPrice.value = selectedPurchaseProductData.value?.sale_price || 0;
    total.value = finalPrice.value * quantity.value;
});

watch([quantity, finalPrice], () => {
    total.value = finalPrice.value * quantity.value;
});

const addToOrder = () => {
    if (!selectedProduct.value || !selectedPurchaseProductData.value) return;

    const existingItem = orderItems.value.find(item => item.id === selectedPurchaseProductData.value.id);
    if (existingItem) {
        existingItem.quantity += quantity.value;
        existingItem.total = existingItem.quantity * existingItem.price;
    } else {
        orderItems.value.push({
            id: selectedPurchaseProductData.value.id,
            name: selectedProduct.value.name,
            price: finalPrice.value,
            quantity: quantity.value,
            total: total.value
        });
    }

    selectedProductId.value = props.products[0]?.id ?? null;
    selectedPurchaseProduct.value = null;
    quantity.value = 1;
};

const removeItem = (id: number) => {
    orderItems.value = orderItems.value.filter(item => item.id !== id);
};

const totalAmount = computed(() => orderItems.value.reduce((sum, item) => sum + item.total, 0));
const subTotal = computed(() => totalAmount.value - discount.value);

watch(discount, () => {
    if (discount.value > totalAmount.value) discount.value = totalAmount.value;
});

const submitOrder = () => {
    if (orderItems.value.length === 0) {
        message.error('Please add at least one product to the order.');
        return;
    }

    const payload = {
    name: customerName.value,
    phone_number: phoneNumber.value,
    total_price: totalAmount.value,
    discount: discount.value,
    subtotal_price: subTotal.value,
    status: status.value,
    payment_method: paymentMethod.value,
    products: orderItems.value.map(item => ({
        product_id: item.id,
        purchase_id: item.id,
        qty: item.quantity,
        sale_price: item.price,
        total_price: item.total,
    })),
};


isLoading.value = true;
    router.post(route('user.order.store'), payload,  {
        onSuccess: () => {
            orderItems.value = [];
            customerName.value = '';
            phoneNumber.value = '';
            discount.value = 0;
        },
        onError: (errors) => {
            console.error(errors);
            message.error('Failed to create order. Please check inputs.');
        },
        onFinish: () => {
            isLoading.value = false;
        }
    });
};
</script>


    <template>
            <div v-if="isLoading" class="loading-overlay" >
        <a-spin size="large" />
    </div>
        <AdminLayout>
            <Head title="Create Order" />

            <a-row>
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
                                        placeholder="Price"
                                        style="width: 100%"
                                        :options="purchaseProducts.map(pp => ({ value: pp.id, label: `Sale Price: ${pp.sale_price} (Stock: ${pp.remaining_stock})` }))"
                                    />
                                </div>
                            </a-col>

                            <a-col :lg="3" v-if="purchaseProducts.length > 0">
                                <div class="mb-4">
                                    <label class="block">Final Price</label>
                                    <a-input type="number" v-model:value="finalPrice" class="w-full" placeholder="Final Price" />
                                </div>
                            </a-col>

                            <a-col :lg="2" v-if="selectedPurchaseProduct">
                                <div class="mb-4">
                                    <label for="">QTY</label>
                                    <a-input min="1" type="number" v-model:value="quantity" class="w-full" placeholder="Enter Quantity" />
                                </div>
                            </a-col>

                            <a-col :lg="4">
                                <div class="mb-4">
                                    <label for="">Total</label>
                                    <a-input readonly :value="total" class="w-full" placeholder="Total Price" />
                                </div>
                            </a-col>

                            <a-col :lg="2">
                                <div class="mb-4">
                                    <label class="block" for="">Action</label>
                                    <a-button type="primary" @click="addToOrder">ADD</a-button>
                                </div>
                            </a-col>
                        </a-row>
                    </div>
                </a-col>
            </a-row>

            <a-row class="mt-4">
                <a-col :lg="12">
                    <div class="bg-white rounded-lg p-4 shadow-md">
                        <table class="table-auto w-full">
                            <thead>
                                <tr class="text-left">
                                    <th>Product</th>
                                    <th>Price</th>
                                    <th>QTY</th>
                                    <th>Total</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(item, index) in orderItems" :key="index">
                                    <td class="py-2">{{ item.name }}</td>
                                    <td class="py-2">{{ item.price }}</td>
                                    <td class="py-2">{{ item.quantity }}</td>
                                    <td class="py-2">{{ item.total }}</td>
                                    <td class="py-2">
                                        <a-button type="primary" danger @click="removeItem(item.id)">Delete</a-button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </a-col>

                <a-col :lg="12">
                    <div class="bg-white rounded-lg p-4 shadow-md ml-2">
                        <div>
                            <h4 class="mb-2">Total: {{ totalAmount }}</h4>
                            <div class="flex items-center mb-2">
                                <h4>Disc:</h4>
                                <a-input min="0" type="number" v-model:value="discount" class="w-52 ml-3" placeholder="DISC" />
                            </div>
                            <h4 class="mb-2">Sub Total: {{ subTotal }}</h4>
                        </div>
                        <div class="mb-4">
                                <label class="block">Name</label>
                                <a-input v-model:value="customerName" class="mt-2 w-full" placeholder="Name" />
                                <div v-if="errors.name" class="text-red-500">{{ errors.name }}</div>

                            </div>
                        <div class="mb-4">
                                <label class="block">Phone Number</label>
                                <a-input v-model:value="phoneNumber" class="mt-2 w-full" placeholder="Phone Number" />
                                <div v-if="errors.phone_number" class="text-red-500">{{ errors.phone_number }}</div>
                            </div>
                            <a-button type="primary" class="w-full" @click="submitOrder">Generate Order</a-button>
                    </div>

                </a-col>
            </a-row>
        </AdminLayout>
    </template>
