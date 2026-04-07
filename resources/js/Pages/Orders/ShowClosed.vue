<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    order: { type: Object, required: true },
});
</script>

<template>
    <Head :title="`Order #${order.id}`" />
    <AppLayout>
        <div class="mb-4 flex items-center justify-between">
            <h1 class="text-2xl font-semibold text-gray-800">Order #{{ order.id }} (Closed)</h1>
            <Link href="/orders">
                <v-btn variant="text"><v-icon start icon="mdi-arrow-left" /> Orders</v-btn>
            </Link>
        </div>

        <v-card data-testid="order-closed-card">
            <v-card-text>
                <v-list density="compact">
                    <v-list-item
                        v-for="product in order.products"
                        :key="`p-${product.pivot_id}`"
                        :title="`${product.name} × ${product.quantity}`"
                        :subtitle="`$${(product.price * product.quantity).toFixed(2)}`"
                    />
                    <v-list-item
                        v-for="liquid in order.liquids"
                        :key="`l-${liquid.id}`"
                        :title="liquid.recipe_name"
                        :subtitle="`$${liquid.price.toFixed(2)}`"
                    />
                </v-list>

                <v-divider class="my-3" />

                <dl class="space-y-1 text-sm">
                    <div
                        v-for="discount in order.discounts"
                        :key="discount.pivot_id"
                        class="flex justify-between text-red-600"
                    >
                        <dt>{{ discount.name }}</dt>
                        <dd>-${{ discount.applied.toFixed(2) }}</dd>
                    </div>
                    <div class="flex justify-between border-t pt-2 text-base font-semibold">
                        <dt>Total</dt>
                        <dd>${{ order.total.toFixed(2) }}</dd>
                    </div>
                </dl>

                <v-divider class="my-3" />

                <h3 class="mb-2 font-semibold">Payments</h3>
                <v-list density="compact">
                    <v-list-item
                        v-for="payment in order.payments"
                        :key="payment.id"
                        :title="payment.type"
                        :subtitle="`$${payment.amount.toFixed(2)}`"
                    />
                </v-list>
            </v-card-text>
        </v-card>
    </AppLayout>
</template>
