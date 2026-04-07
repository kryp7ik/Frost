<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    productInstances: { type: Array, default: () => [] },
});

const headers = [
    { title: 'Product', key: 'product_name' },
    { title: 'Store', key: 'store' },
    { title: 'Stock', key: 'stock' },
    { title: 'Redline', key: 'redline' },
    { title: 'Price', key: 'price' },
];
</script>

<template>
    <Head title="Redline Alerts" />
    <AppLayout>
        <div class="mb-4 flex items-center justify-between">
            <h1 class="text-2xl font-semibold text-gray-800">
                <v-icon class="mr-2 text-amber-500" icon="mdi-alert-circle" />
                Redline Alerts
            </h1>
            <Link href="/admin/store/products/index">
                <v-btn variant="text"><v-icon start icon="mdi-arrow-left" /> Back</v-btn>
            </Link>
        </div>

        <v-card data-testid="redline-card">
            <v-card-text>
                <p v-if="!productInstances.length" class="text-sm text-gray-600">
                    Everything is above its redline — great job!
                </p>
                <v-data-table
                    v-else
                    :headers="headers"
                    :items="productInstances"
                    item-value="id"
                    density="comfortable"
                >
                    <template #item.price="{ item }">${{ Number(item.price).toFixed(2) }}</template>
                </v-data-table>
            </v-card-text>
        </v-card>
    </AppLayout>
</template>
