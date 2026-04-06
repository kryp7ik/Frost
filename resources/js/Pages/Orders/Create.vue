<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const form = useForm({});

function createOrder() {
    form.post('/orders/create');
}
</script>

<template>
    <Head title="New Order" />
    <AppLayout>
        <h1 class="mb-4 text-2xl font-semibold text-gray-800">New Order</h1>
        <v-card max-width="480" data-testid="order-create-card">
            <v-card-text class="text-center">
                <p class="mb-4 text-sm text-gray-600">
                    Start a new order. You'll be taken to the order screen where
                    you can add products, liquids and process payment.
                </p>
                <v-btn
                    color="primary"
                    size="large"
                    block
                    :loading="form.processing"
                    data-testid="order-create-submit"
                    @click="createOrder"
                >
                    <v-icon start icon="mdi-cart-plus" /> Start Order
                </v-btn>
                <Link href="/orders" class="mt-3 block text-sm text-gray-500">Cancel</Link>
            </v-card-text>
        </v-card>
    </AppLayout>
</template>
