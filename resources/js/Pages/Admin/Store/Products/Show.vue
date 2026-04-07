<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    product: { type: Object, required: true },
    stores: { type: Array, default: () => [] },
});

const headers = [
    { title: 'Store', key: 'store' },
    { title: 'Price', key: 'price' },
    { title: 'Stock', key: 'stock' },
    { title: 'Redline', key: 'redline' },
    { title: 'Active', key: 'active' },
    { title: '', key: 'actions', sortable: false, align: 'end' },
];
</script>

<template>
    <Head :title="product.name" />
    <AppLayout>
        <div class="mb-4 flex items-center justify-between">
            <h1 class="text-2xl font-semibold text-gray-800">{{ product.name }}</h1>
            <div class="flex gap-2">
                <Link :href="`/admin/store/products/${product.id}/edit`">
                    <v-btn variant="tonal"><v-icon start icon="mdi-pencil" /> Edit</v-btn>
                </Link>
                <Link href="/admin/store/products/index">
                    <v-btn variant="text"><v-icon start icon="mdi-arrow-left" /> Back</v-btn>
                </Link>
            </div>
        </div>

        <v-card class="mb-4" data-testid="product-details-card">
            <v-card-text>
                <dl class="grid grid-cols-2 gap-3 text-sm">
                    <dt class="font-semibold">SKU</dt>
                    <dd>{{ product.sku }}</dd>
                    <dt class="font-semibold">Category</dt>
                    <dd>{{ product.category }}</dd>
                    <dt class="font-semibold">Cost</dt>
                    <dd>${{ Number(product.cost).toFixed(2) }}</dd>
                </dl>
            </v-card-text>
        </v-card>

        <v-card data-testid="product-instances-card">
            <v-card-title>Store Instances</v-card-title>
            <v-data-table
                :headers="headers"
                :items="product.instances"
                item-value="id"
                density="comfortable"
            >
                <template #item.store="{ item }">
                    {{ stores.find((s) => s.id === item.store)?.name || item.store }}
                </template>
                <template #item.price="{ item }">${{ Number(item.price).toFixed(2) }}</template>
                <template #item.active="{ item }">
                    <v-icon
                        :icon="item.active ? 'mdi-check-circle' : 'mdi-close-circle'"
                        :color="item.active ? 'success' : 'grey'"
                    />
                </template>
                <template #item.actions="{ item }">
                    <Link :href="`/admin/store/products/instance/${item.id}/edit`">
                        <v-btn size="small" variant="text" icon="mdi-pencil" />
                    </Link>
                </template>
            </v-data-table>
        </v-card>
    </AppLayout>
</template>
