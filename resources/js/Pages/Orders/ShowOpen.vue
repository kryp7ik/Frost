<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head, Link, router, useForm, usePage } from '@inertiajs/vue3';
import { computed, ref } from 'vue';

const props = defineProps({
    order: { type: Object, required: true },
    discounts: { type: Array, default: () => [] },
    instances: { type: Array, default: () => [] },
    recipes: { type: Array, default: () => [] },
    liquidOptions: { type: Object, default: () => ({}) },
});

const page = usePage();
const isManager = computed(() => page.props.auth?.user?.is_manager);

const customerForm = useForm({ phone: '' });
const paymentForm = useForm({ type: 'cash', amount: '' });
const discountForm = useForm({ discount: null });
const productForm = useForm({ products: [{ instance: null, quantity: 1 }] });
const liquidForm = useForm({
    liquids: [{
        recipe: null,
        size: 30,
        nicotine: 0,
        vg: 60,
        menthol: 0,
        salt: false,
        extra: false,
    }],
});

const subtotal = computed(() => {
    let total = 0;
    for (const p of props.order.products) {
        total += p.price * p.quantity;
    }
    for (const l of props.order.liquids) {
        total += l.price;
    }
    return total;
});

function setCustomer() {
    customerForm.post(`/orders/${props.order.id}/customer`);
}

function addPayment() {
    paymentForm.post(`/orders/${props.order.id}/payment`);
}

function addDiscount() {
    discountForm.post(`/orders/${props.order.id}/add-discount`);
}

function addProduct() {
    productForm.post(`/orders/${props.order.id}/add-product`, {
        onSuccess: () => productForm.reset(),
    });
}

function addLiquid() {
    liquidForm.post(`/orders/${props.order.id}/add-liquid`, {
        onSuccess: () => liquidForm.reset(),
    });
}

function quantityUpdate(pivotId, direction) {
    router.get(`/orders/${props.order.id}/quantity-update`, { pid: pivotId, inc: direction });
}

function removeProduct(pid) {
    router.get(`/orders/${props.order.id}/remove-product/${pid}`);
}

function removeLiquid(lid) {
    router.get(`/orders/${props.order.id}/remove-liquid/${lid}`);
}

function duplicateLiquid(lid) {
    router.get(`/orders/duplicate-liquid/${lid}`);
}

function removeDiscount(did) {
    router.get(`/orders/${props.order.id}/remove-discount/${did}`);
}

function deletePayment(paymentId) {
    if (confirm('Remove this payment?')) {
        router.get(`/orders/payment/${paymentId}/delete`);
    }
}

function deleteOrder() {
    if (confirm('Delete this order?')) {
        router.get(`/orders/${props.order.id}/delete`);
    }
}
</script>

<template>
    <Head :title="`Order #${order.id}`" />
    <AppLayout>
        <div class="mb-4 flex items-center justify-between">
            <h1 class="text-2xl font-semibold text-gray-800">Order #{{ order.id }}</h1>
            <div class="flex gap-2">
                <Link href="/orders">
                    <v-btn variant="text"><v-icon start icon="mdi-arrow-left" /> Orders</v-btn>
                </Link>
                <v-btn color="error" variant="tonal" data-testid="order-delete" @click="deleteOrder">
                    <v-icon start icon="mdi-delete" /> Delete Order
                </v-btn>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-4 lg:grid-cols-3">
            <div class="lg:col-span-2 space-y-4">
                <!-- Items Card -->
                <v-card data-testid="order-items-card">
                    <v-card-title>Items</v-card-title>
                    <v-card-text>
                        <p v-if="!order.products.length && !order.liquids.length" class="text-sm text-gray-500">
                            No items yet. Add products or liquids to this order.
                        </p>

                        <!-- Products list with quantity controls -->
                        <v-list v-if="order.products.length" density="compact" class="mb-2">
                            <v-list-item
                                v-for="product in order.products"
                                :key="`p-${product.pivot_id}`"
                                :subtitle="`$${(product.price * product.quantity).toFixed(2)}`"
                            >
                                <template #title>
                                    <span>{{ product.name }}</span>
                                </template>
                                <template #append>
                                    <div class="flex items-center gap-1">
                                        <v-btn
                                            size="x-small"
                                            variant="text"
                                            icon="mdi-minus"
                                            :data-testid="`order-product-minus-${product.pivot_id}`"
                                            @click="quantityUpdate(product.pivot_id, 'minus')"
                                        />
                                        <span class="mx-1 text-sm font-medium">{{ product.quantity }}</span>
                                        <v-btn
                                            size="x-small"
                                            variant="text"
                                            icon="mdi-plus"
                                            :data-testid="`order-product-plus-${product.pivot_id}`"
                                            @click="quantityUpdate(product.pivot_id, 'plus')"
                                        />
                                        <v-btn
                                            size="x-small"
                                            variant="text"
                                            color="error"
                                            icon="mdi-delete"
                                            @click="removeProduct(product.pivot_id)"
                                        />
                                    </div>
                                </template>
                            </v-list-item>
                        </v-list>

                        <!-- Liquids list with duplicate/delete -->
                        <v-list v-if="order.liquids.length" density="compact" class="mb-2">
                            <v-list-item
                                v-for="liquid in order.liquids"
                                :key="`l-${liquid.id}`"
                                :title="`${liquid.recipe_name} (${liquid.size}ml, ${liquid.nicotine}mg)`"
                                :subtitle="`$${liquid.price.toFixed(2)}`"
                            >
                                <template #append>
                                    <v-btn
                                        size="x-small"
                                        variant="text"
                                        color="success"
                                        icon="mdi-content-copy"
                                        :data-testid="`order-liquid-duplicate-${liquid.id}`"
                                        @click="duplicateLiquid(liquid.id)"
                                    />
                                    <v-btn
                                        size="x-small"
                                        variant="text"
                                        color="error"
                                        icon="mdi-delete"
                                        @click="removeLiquid(liquid.id)"
                                    />
                                </template>
                            </v-list-item>
                        </v-list>

                        <v-divider class="my-3" />

                        <!-- Add Product -->
                        <h3 class="mb-2 text-sm font-semibold text-gray-700">Add Product</h3>
                        <div class="flex items-start gap-2">
                            <v-autocomplete
                                v-model="productForm.products[0].instance"
                                :items="instances"
                                item-title="label"
                                item-value="id"
                                label="Product"
                                class="flex-1"
                                hide-details
                                density="compact"
                                data-testid="order-add-product-select"
                            />
                            <v-text-field
                                v-model.number="productForm.products[0].quantity"
                                label="Qty"
                                type="number"
                                min="1"
                                hide-details
                                density="compact"
                                style="max-width: 80px;"
                                data-testid="order-add-product-qty"
                            />
                            <v-btn
                                color="primary"
                                size="small"
                                :loading="productForm.processing"
                                :disabled="!productForm.products[0].instance"
                                data-testid="order-add-product-submit"
                                @click="addProduct"
                            >
                                Add
                            </v-btn>
                        </div>

                        <v-divider class="my-3" />

                        <!-- Add Liquid -->
                        <h3 class="mb-2 text-sm font-semibold text-gray-700">Add Liquid</h3>
                        <div class="grid grid-cols-2 gap-2 sm:grid-cols-3 lg:grid-cols-4">
                            <v-select
                                v-model="liquidForm.liquids[0].recipe"
                                :items="recipes"
                                item-title="name"
                                item-value="id"
                                label="Recipe"
                                hide-details
                                density="compact"
                                class="col-span-2"
                                data-testid="order-add-liquid-recipe"
                            />
                            <v-select
                                v-model="liquidForm.liquids[0].size"
                                :items="liquidOptions.sizes || []"
                                label="Size"
                                hide-details
                                density="compact"
                                data-testid="order-add-liquid-size"
                            />
                            <v-select
                                v-model="liquidForm.liquids[0].nicotine"
                                :items="liquidOptions.nicotine || []"
                                label="Nicotine"
                                hide-details
                                density="compact"
                                data-testid="order-add-liquid-nicotine"
                            />
                            <v-select
                                v-model="liquidForm.liquids[0].vg"
                                :items="liquidOptions.vg || []"
                                label="VG"
                                hide-details
                                density="compact"
                                data-testid="order-add-liquid-vg"
                            />
                            <v-select
                                v-model="liquidForm.liquids[0].menthol"
                                :items="liquidOptions.menthol || []"
                                label="Menthol"
                                hide-details
                                density="compact"
                                data-testid="order-add-liquid-menthol"
                            />
                            <v-checkbox
                                v-model="liquidForm.liquids[0].salt"
                                label="Salt"
                                hide-details
                                density="compact"
                                data-testid="order-add-liquid-salt"
                            />
                            <v-checkbox
                                v-model="liquidForm.liquids[0].extra"
                                label="Extra"
                                hide-details
                                density="compact"
                                data-testid="order-add-liquid-extra"
                            />
                        </div>
                        <v-btn
                            color="primary"
                            size="small"
                            class="mt-2"
                            :loading="liquidForm.processing"
                            :disabled="!liquidForm.liquids[0].recipe"
                            data-testid="order-add-liquid-submit"
                            @click="addLiquid"
                        >
                            Add Liquid
                        </v-btn>
                    </v-card-text>
                </v-card>

                <!-- Discounts Card -->
                <v-card data-testid="order-discounts-card">
                    <v-card-title>Discounts</v-card-title>
                    <v-card-text>
                        <v-list v-if="order.discounts.length" density="compact" class="mb-3">
                            <v-list-item
                                v-for="discount in order.discounts"
                                :key="discount.pivot_id"
                                :title="discount.name"
                                :subtitle="`-$${discount.applied.toFixed(2)}`"
                            >
                                <template #append>
                                    <v-btn size="small" variant="text" color="error" icon="mdi-close" @click="removeDiscount(discount.pivot_id)" />
                                </template>
                            </v-list-item>
                        </v-list>

                        <div class="flex items-end gap-2">
                            <v-select
                                v-model="discountForm.discount"
                                :items="discounts"
                                item-title="name"
                                item-value="id"
                                label="Apply Discount"
                                class="flex-1"
                                data-testid="order-discount-select"
                            />
                            <v-btn
                                color="primary"
                                :loading="discountForm.processing"
                                :disabled="!discountForm.discount"
                                data-testid="order-discount-submit"
                                @click="addDiscount"
                            >
                                Apply
                            </v-btn>
                        </div>
                    </v-card-text>
                </v-card>
            </div>

            <div class="space-y-4">
                <!-- Customer Card -->
                <v-card data-testid="order-customer-card">
                    <v-card-title>Customer</v-card-title>
                    <v-card-text>
                        <div v-if="order.customer" class="text-sm">
                            <p class="font-semibold">{{ order.customer.name }}</p>
                            <p class="text-gray-500">{{ order.customer.phone }}</p>
                            <p class="text-gray-500">{{ order.customer.points }} points</p>
                        </div>
                        <p v-else class="mb-2 text-sm text-gray-500">No customer attached.</p>
                        <div class="mt-2 flex items-end gap-2">
                            <v-text-field
                                v-model="customerForm.phone"
                                label="Phone"
                                hide-details
                                density="compact"
                                data-testid="order-customer-phone"
                            />
                            <v-btn
                                size="small"
                                color="primary"
                                :loading="customerForm.processing"
                                data-testid="order-customer-submit"
                                @click="setCustomer"
                            >
                                Set
                            </v-btn>
                        </div>
                    </v-card-text>
                </v-card>

                <!-- Total Card -->
                <v-card data-testid="order-total-card">
                    <v-card-title>Total</v-card-title>
                    <v-card-text>
                        <dl class="space-y-1 text-sm">
                            <div class="flex justify-between">
                                <dt>Subtotal</dt>
                                <dd>${{ subtotal.toFixed(2) }}</dd>
                            </div>
                            <div class="flex justify-between text-base font-semibold">
                                <dt>Order Total</dt>
                                <dd>${{ order.total.toFixed(2) }}</dd>
                            </div>
                        </dl>
                    </v-card-text>
                </v-card>

                <!-- Payment Card -->
                <v-card data-testid="order-payment-card">
                    <v-card-title>Payment</v-card-title>
                    <v-card-text>
                        <!-- Existing payments -->
                        <v-list v-if="order.payments.length" density="compact" class="mb-3">
                            <v-list-item
                                v-for="payment in order.payments"
                                :key="payment.id"
                                :title="payment.type"
                                :subtitle="`$${payment.amount.toFixed(2)}`"
                            >
                                <template v-if="isManager" #append>
                                    <v-btn
                                        size="x-small"
                                        variant="text"
                                        color="error"
                                        icon="mdi-delete"
                                        :data-testid="`order-payment-delete-${payment.id}`"
                                        @click="deletePayment(payment.id)"
                                    />
                                </template>
                            </v-list-item>
                        </v-list>

                        <v-select
                            v-model="paymentForm.type"
                            :items="[
                                { title: 'Cash', value: 'cash' },
                                { title: 'Card', value: 'card' },
                            ]"
                            label="Type"
                            hide-details
                            density="compact"
                            class="mb-2"
                            data-testid="order-payment-type"
                        />
                        <v-text-field
                            v-model="paymentForm.amount"
                            label="Amount"
                            type="number"
                            step="0.01"
                            prefix="$"
                            hide-details
                            density="compact"
                            class="mb-2"
                            data-testid="order-payment-amount"
                        />
                        <v-btn
                            block
                            color="success"
                            :loading="paymentForm.processing"
                            data-testid="order-payment-submit"
                            @click="addPayment"
                        >
                            Take Payment
                        </v-btn>
                    </v-card-text>
                </v-card>
            </div>
        </div>
    </AppLayout>
</template>
